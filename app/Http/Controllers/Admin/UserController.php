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
        if (empty($request->id)) {
            $users = User::where('id', '>', 0);
        } else {
            $users = User::where('id', $request->id);
        }

        if (!empty($request->email)) $users = $users->where('email', 'like', "%$request->email%");
        if (!empty($request->first_name)) $users = $users->where('first_name', 'like', "%$request->first_name%");
        if (!empty($request->last_name)) $users = $users->where('last_name', 'like', "%$request->last_name%");
        if (!empty($request->approval)) $users = $users->whereIn('approval', array_values($request->approval));
        if (!empty($request->role)) $users = $users->whereIn('role', array_values($request->role));

        $users = $users->orderBy('role', 'asc')->get();

        $managers = User::whereIn('role', ['faculty', 'admin'])
            ->orderBy('role', 'asc')
            ->get();

        return view('admin.users.index', compact('users', 'managers'));
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
