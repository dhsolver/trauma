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

        if ($request->export) {
            echo $this->handleUsersCSV($users);
            die;
        }

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

    private function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    private function handleUsersCSV($users) {
        $this->download_send_headers('users.csv');

        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Date of Birth', 'Account Type', 'Status'));
        foreach ($users as $user) {
            fputcsv($output, array($user->id, $user->first_name, $user->last_name, $user->email, $user->birthday, $user->role, $user->approval));
        }

        fclose($output);
        return ob_get_clean();
    }
}
