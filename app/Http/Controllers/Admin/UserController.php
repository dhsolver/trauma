<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\User;
use App\Http\Requests\Admin\UserRequest;
use Datatables;


class UserController extends AdminController
{
    public function __construct()
    {
    }

    public function index()
    {
        $students = User::where('role', 'student')
            ->where('admin', 0)
            ->where('approval', 'approved')
            ->get();

        $faculties = User::where('role', 'faculty')
            ->where('admin', 0)
            ->where('approval', 'approved')
            ->get();

        return view('admin.users.index', compact('students', 'faculties'));
    }

    public function approve(User $user)
    {
        if ($user->role === 'student') {
            $user->approval = 'approved';
            $user->save();

            session()->flash('userMessage', 'User has been approved!');
        }
        return redirect()->action('Admin\DashboardController@index');
    }

    public function reject(User $user)
    {
        if ($user->role === 'student') {
            $user->approval = 'rejected';
            $user->save();

            session()->flash('userMessage', 'User has been rejected!');
        }
        return redirect()->action('Admin\DashboardController@index');
    }


    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $password = $request->password;
        if (!empty($password)) {
            $user->password = bcrypt($password);
        }

        $user->update($request->except('password', 'password_confirmation'));

        session()->flash('userMessage', 'User has been updated!');
        return redirect()->action('Admin\UserController@edit', $user);
    }
}
