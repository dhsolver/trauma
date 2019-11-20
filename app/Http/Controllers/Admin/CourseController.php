<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\User;
use App\Organization;
use App\UsersCoursesRegistration;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class CourseController extends AdminController {
    public function index(Request $request) {
        if (empty($request->id)) {
            $courses = Course::where('id', '>', 0);
        } else {
            $courses = Course::where('id', $request->id);
        }
        if (!empty($request->title)) $courses = $courses->where('title', 'like', "%$request->title%");
        if (!empty($request->location)) $courses = $courses->where('location', 'like', "%$request->location%");
        $user = Auth::user();
        $organization_ids = Organization::where('assigned_users', 'like', "%$user->id%")->lists('id')->toArray();
        
        if ($user->role === 'faculty') {
            $courses = $courses->where('instructors', 'like', "%$user->id%")
                ->orWhereIn('organization_id', $organization_ids);
        }
        if ($user->role === 'manager') {
            $courses = $courses->where('organization_id', '=', '0')
                ->orWhereIn('organization_id', $organization_ids);
        }


        $courses = $courses->orderBy('title', 'asc')
            ->get();

        $users = User::all()->keyBy('id')->toArray();

        return view('admin.courses.index', compact('courses', 'users'));
    }

    public function create() {
        return view('admin.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $course = new Course($request->all());
        $user = Auth::user();
        if ($user->role === 'faculty')
            $course->instructors = [$user->id];
        $course->save();

        session()->flash('courseMessage', 'Course has been created!');
        return redirect()->action('Admin\CourseController@index');
    }

    public function edit(Course $course)
    {
        $user = Auth::user();
        if (($user->role === 'faculty' && (!is_array($course->instructors) || !in_array($user->id, $course->instructors)))) {
            return redirect()->action('Admin\CourseController@index');
        }
        
        if ($user->role === 'faculty' || $user->role === 'manager') {
            $organizations = Organization::where('id', '>', '0')
                ->where('assigned_users', 'like', "%$user->id%")
                ->get()
                ->keyBy('id')
                ->toArray();    
        }
        else if ($user->role === 'admin') {
            $organizations = Organization::where('id', '>', '0')
                ->get()
                ->keyBy('id')
                ->toArray();    
        }

        if (empty($course->organization_id)) {
            $faculties = User::where('role', 'faculty')
                ->orderBy('first_name', 'asc')
                ->orderBy('last_name', 'asc')
                ->get()
                ->keyBy('id')
                ->toArray();
        }
        else {
            $organization = Organization::find($course->organization_id);
            $faculties = User::where('role', 'faculty')
                ->whereIn('id', $organization->assigned_users)
                ->orderBy('first_name', 'asc')
                ->orderBy('last_name', 'asc')
                ->get()
                ->keyBy('id')
                ->toArray();
            
            $facultyIds = array_keys($faculties);
            $instructors = [];
            foreach ($course->instructors as $instructor_id) {
                if (in_array($instructor_id, $facultyIds)) {
                    $instructors[] = $instructor_id;
                }
            }
            $course->instructors = $instructors;
        }

        $s3Data = prepareS3Data();

        return view('admin.courses.edit', compact('course', 'faculties', 'organizations', 's3Data'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        if (!$course->published && $request->published) {
            $course->slug = null;
        }
        $instructors = $course->instructors;
        $course->update($request->all());

        if (!$course->online_only) {
            if ($course->date_start > $course->date_end) {
                return redirect()->action('Admin\CourseController@edit', $course)
                            ->withMessage('Please enter correct dates')
                            ->withInput();
            }
        }

        $course->instructors = $instructors;
        $course->save();

        session()->flash('courseMessage', 'Course has been updated!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function updatePhoto(Request $request, Course $course)
    {
        $this->validate($request, [
            'fileKeys' => 'required',
        ]);

        $course->photo = $request->fileKeys[0];
        $course->save();

        session()->flash('courseMessage', 'Course image has been updated!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function delete(Course $course)
    {
        $course->delete();
        session()->flash('courseMessage', 'Course has been deleted!');
        return redirect()->action('Admin\CourseController@index');
    }

    public function disable(Course $course)
    {
        $course->enabled = false;
        $course->published = false;
        $course->save();
        session()->flash('courseMessage', 'Course has been disabled!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function enable(Course $course)
    {
        $course->enabled = true;
        $course->save();
        session()->flash('courseMessage', 'Course has been enabled!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    private function copyPublicFile($path, $newPath)
    {
        if (empty($newPath)) return;
        $fullPath = public_path($newPath);
        if (!File::exists(dirname($fullPath))) {
            File::makeDirectory(dirname($fullPath), 0755, true, true);
        }
        File::copy(public_path($path), $fullPath);
    }

    /*
    // $this->copyChildren($course, $newCourse, 'modules');
    private function copyChildren($object, $newObject, $relation)
    {
        $object->load($relation);
        foreach ($course->getRelations() as $relation => $items){
            foreach ($items as $item){
                unset($item->id);
                $newObject->{$relation}()->create($item->toArray());
            }

        }
    }*/

    public function copy(Course $course)
    {
        $newCourse = $course->replicate();
        $newCourse->title = 'Copy - '.$newCourse->title;
        $newCourse->published = false;
        $newCourse->enabled = true;
        $newCourse->slug = null;

        $newCourse->push();

        foreach ($course->modules as $module => $courseModule) {
            $newCourseModule = $courseModule->replicate();
            $newCourseModule->course_id = $newCourse->id;
            $newCourseModule->push();

            foreach ($courseModule->documents as $document => $courseModuleDocument) {
                $newCourseModuleDocument = $courseModuleDocument->replicate();
                $newCourseModuleDocument->course_module_id = $newCourseModule->id;
                $newCourseModuleDocument->push();
            }
        }

        session()->flash('courseMessage', 'Course has been copied!');
        return redirect()->action('Admin\CourseController@edit', $newCourse);
    }

    public function exportStudents(Course $course)
    {
        $this->download_send_headers('course_students.csv');

        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array(
            'Student Id',
            'Full Name',
            'Email',
            // 'Channel',
            'Registered',
            'Completed',
            'Certified',
        ));
        foreach ($course->registrations as $registration) {
            fputcsv($output, array(
                $registration->user->id,
                $registration->user->first_name.' '.$registration->user->last_name,
                $registration->user->email,
                // $registration->method == 'key' ? 'Course Key' : 'Paypal',
                $registration->registered_at,
                $registration->completed_at,
                $registration->certified_at,
            ));
        }

        fclose($output);
        echo ob_get_clean();
        die;
    }

    public function certifyStudent(Course $course, UsersCoursesRegistration $registration)
    {
        if (empty($registration->certified_at)) {
            if (empty($registration->completed_at)) {
                $registration->completed_at = Carbon::now()->toDateTimeString();
            }
            $registration->certified_at = Carbon::now()->toDateTimeString();
            $registration->save();

            session()->flash('courseMessage', 'You just certified a student for completing this course.');
        }

        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function uncertifyStudent(Course $course, UsersCoursesRegistration $registration)
    {
        if ($registration->certified_at) {
            $registration->certified_at = null;
            $registration->save();

            session()->flash('courseMessage', 'You just uncertified a student for completing this course.');
        }

        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function unregisterStudent(Course $course, UsersCoursesRegistration $registration)
    {
        $registration->delete();
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function myTeachings()
    {
        $user = Auth::user();

        $myCourses = Course::where('published', 1)
            ->where('enabled', 1)
            ->where('instructors', 'like', "%$user->id%")
            ->orderBy('title', 'asc')
            ->get();

        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        return view('admin.courses.my-teachings', compact('myCourses', 'faculties'));
    }

    public function updateInstrcutors(Course $course, Request $request)
    {
        $instructors = $course->instructors;
        $instructor_id = $request->instructor_id;
        if (in_array($instructor_id, $instructors)) {
            $key = array_search($instructor_id, $instructors);
            unset($instructors[$key]);
        }
        else {
            $instructors[] = $instructor_id;
        }

        $course->instructors = $instructors;
        $course->save();
        
        return response()->json
        ([
            'success' => true,
            'redirect' => action('Admin\CourseController@edit', [
                'course' => $course
            ])
        ]);
    }

    public function updateOrganization(Course $course, Request $request)
    {
        $organization_id = $request->organization_id;
        if ($course->organization_id == $organization_id) {
            $course->organization_id = 0;
        }
        else {
            $course->organization_id = $organization_id;
        }

        $course->save();
        
        return response()->json
        ([
            'success' => true,
            'redirect' => action('Admin\CourseController@edit', [
                'course' => $course
            ])
        ]);
    }
}
