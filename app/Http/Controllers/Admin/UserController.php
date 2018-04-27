<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\User;
use App\Http\Requests\Admin\UserRequest;

class UserController extends AdminController
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $id = $request->id;
        $email = $request->email;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $approval = $request->approval;
        $role = $request->role;

        if (empty($id)) {
            $users = User::where('id', '>', 0);
        } else {
            $users = User::where('id', $id);
        }

        if (!empty($email)) $users = $users->where('email', 'like', "%$email%");
        if (!empty($first_name)) $users = $users->where('first_name', 'like', "%$first_name%");
        if (!empty($approval)) $users = $users->whereIn('approval', array_values($approval));
        if (!empty($role)) $users = $users->whereIn('role', array_values($role));

        $users = $users->orderBy('role', 'asc')->get();
        // $students = User::where('role', 'student')
        //     ->where('admin', 0)
        //     ->where('approval', 'approved')
        //     ->get();

        // $faculties = User::where('role', 'faculty')
        //     ->where('admin', 0)
        //     ->where('approval', 'approved')
        //     ->get();

        return view('admin.users.index', compact('users'));
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

    public function deny(User $user)
    {
        if ($user->role === 'student') {
            $user->approval = 'denied';
            $user->save();

            session()->flash('userMessage', 'User has been denied!');
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
