<?php namespace App\Http\Controllers;

// use App\Http\Requests\Request;
// use App\Http\Controllers\Controller;
use App\User;
use App\Course;

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
        if (!$course->published) {
            return response()->view('errors.404');
        }

        session()->put('url.intended', \Request::url());

        return view('courses.show', compact('course', 'faculties'));
    }
}
