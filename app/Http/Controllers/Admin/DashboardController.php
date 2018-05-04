<?php namespace App\Http\Controllers\Admin;

use App\Course;
use App\User;
use App\Http\Controllers\AdminController;


class DashboardController extends AdminController {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $pendingUsers = User::where('role', 'student')
            ->where('approval', 'pending')
            ->get();


        $latestCourses = Course::where('published', 1)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard.index', compact('pendingUsers', 'latestCourses'));
    }
}
