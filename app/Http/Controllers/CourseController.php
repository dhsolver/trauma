<?php namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\CourseKey;
use App\CourseModuleDocument;
use App\UsersCoursesRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CourseController extends Controller {
    public function index()
    {
        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        $latestCourses = Course::where('published', 1)
            ->where('enabled', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('courses.index', compact('latestCourses', 'faculties'));
    }

    public function show($slug, Request $request)
    {
        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        $course = Course::findBySlugOrId($slug);
        if (!$course->enabled) {
            return response()->view('errors.404');
        }

        $user = Auth::user();
        $registration = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        return view('courses.show', compact('user', 'course', 'faculties', 'registration'));
    }

    public function browse($slug)
    {
        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        $course = Course::findBySlugOrId($slug);
        if (!$course->enabled) {
            return response()->view('errors.404');
        }

        $user = Auth::user();
        $registration = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('payment_status', 'Completed')
            ->first();

        if (empty($registration)) {
            session()->flash('courseError', 'You need to register or purchase to browse this course.');
            return redirect()->action('CourseController@show', $course->slug);
        }

        return view('courses.browse', compact('course', 'faculties', 'registration'));
    }

    public function trackProgress(Course $course, CourseModuleDocument $courseModuleDocument)
    {
        $user = Auth::user();
        $registration = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        $currentProgress = $registration->progress;
        $documentId = $courseModuleDocument->id;

        if (empty($currentProgress) || !is_array($currentProgress)) {
            $registration->progress = [];
        }

        if (!in_array($documentId, $registration->progress)) {
            $currentProgress[] = $documentId;
        }

        $registration->progress = $currentProgress;
        $registration->save();

        return response()->json([
            'document' => $courseModuleDocument,
            'progress' => count($registration->progress),
            'total' => count($course->getModuleDocuments())
        ], 200);
    }

    public function finish(Course $course)
    {
        $user = Auth::user();
        $registration = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$registration->completed_at) {
            $registration->completed_at = Carbon::now()->toDateTimeString();
            $registration->save();
            session()->flash('courseMessage', 'Congratulations for finishing this course!');
        }

        return redirect()->action('CourseController@show', $course->slug);
    }

    public function myCourses()
    {
        $user = Auth::user();
        $registrations = $user->registrations;

        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        return view('courses.my-courses', compact('registrations', 'faculties'));
    }

    public function calendar(Request $request)
    {
        $includeMonths = $request->has('dt') ? 2 : 3;
        $dt = $request->input('dt', date('Y-m-d'));

        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();


        $currentMonth = date('n', strtotime($dt));
        $monthStart = date('Y-m-01', strtotime($dt));
        $monthEnd = date('Y-m-t', strtotime($dt));
        $searchEndDate = date('Y-m-t', strtotime('+2 month', strtotime($monthEnd))) ;

        $availableCourses = Course::where('published', 1)
            ->where('enabled', 1)
            ->where('online_only', 0)
            ->orderBy('title', 'asc')
            ->get();

        $latestCourses = [];
        $availableDates = [];

        foreach ($availableCourses as $key => $course) {
            // check if there's any intersection between the course's available period and search dates.
            if (($course->getOriginal('date_start') >= $monthStart && $course->getOriginal('date_start') <= $searchEndDate)
                || ($monthStart >= $course->getOriginal('date_start') && $monthStart && $course->getOriginal('date_end'))) {

                // do not show already end courses
                if ($dt < $course->getOriginal('date_end')) {
                    $latestCourses[] = $course;
                }
            }

            $begin = new \DateTime($course->getOriginal('date_start'));
            $end = new \DateTime($course->getOriginal('date_end'));

            for($d = $begin; $d <= $end; $d->modify('+1 day')){
                $j = $d->getTimestamp(); //$d->format('Y-m-d');
                if (!in_array($j, $availableDates)) {
                    $availableDates[] = $j;
                }
            }
        }

        $onlineCourses = Course::where('published', 1)
            ->where('enabled', 1)
            ->where('online_only', 1)
            ->orderBy('title', 'asc')
            ->get();

        return view('courses.calendar', compact('faculties', 'dt', 'latestCourses', 'onlineCourses', 'availableDates', 'currentMonth'));
    }
}
