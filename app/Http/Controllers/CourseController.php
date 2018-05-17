<?php namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\CourseKey;
use App\CourseModuleDocument;
use App\UsersCoursesRegistration;
use App\Http\Requests\CourseRegisterRequest;
use Illuminate\Support\Facades\DB;
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

    public function show($slug)
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

        return view('courses.show', compact('course', 'faculties', 'registration'));
    }

    public function register(Course $course, CourseRegisterRequest $request)
    {
        $user = Auth::user();
        $alreadyRegistered = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!empty($alreadyRegistered)) {
            return response()->json([
                'key' => 'You are already registered for this course.'
            ], 422);
        }

        $courseKey = CourseKey::where('course_id', $course->id)
            ->where('key', $request->key)
            ->where('redeemed', false)
            ->where('enabled', true)
            ->first();

        if (empty($courseKey)) {
            return response()->json([
                'key' => 'Invalid course key.'
            ], 422);
        }

        DB::transaction(function () use($course, $courseKey, $user) {
            $registration = new UsersCoursesRegistration();
            $registration->user_id = $user->id;
            $registration->course_id = $course->id;
            $registration->method = 'key';
            $registration->reference = $courseKey->key;
            $registration->registered_at = Carbon::now()->toDateTimeString();
            $registration->save();

            $courseKey->redeemed = true;
            $courseKey->redeemed_at = Carbon::now()->toDateTimeString();
            $courseKey->redeemed_user_id = $user->id;
            $courseKey->save();
        });

        session()->flash('courseMessage', 'You have succssfully registered for this course!');
        return response()->json([
            'success' => true,
            'redirect' => action('CourseController@show', [
                'course' => $course,
            ])
        ]);
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
            ->first();

        if (empty($registration)) {
            session()->flash('courseMessage', 'You need to register for the course to browse.');
            return redirect()->action('CourseController@show', $course);
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
}
