<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\User;

class DashboardController extends AdminController {

    public function __construct()
    {
        parent::__construct();
        view()->share('type', '');
    }

    public function index()
    {
        $pendingUsers = User::where('role', 'student')
            ->where('admin', 0)
            ->where('approval', 'pending')
            ->get();

        return view('admin.dashboard.index', compact('pendingUsers'));
    }
}
