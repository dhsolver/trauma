<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

class CourseController extends AdminController {

    public function __construct()
    {
    }

    public function index() {
        return view('admin.courses.index');
    }
}
