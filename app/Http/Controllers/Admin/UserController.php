<?php namespace App\Http\Controllers\Admin;

use App\User;
use App\Organization;
use App\Jobs\SendInvitationEmail;
use App\Jobs\SendApprovedEmail;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class UserController extends AdminController
{
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

        $managers = User::whereIn('role', ['faculty', 'admin', 'manager'])
            ->orderBy('role', 'asc')
            ->get();

        return view('admin.users.index', compact('users', 'managers'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $user = new User($request->all());
        $user->save();

        $this->dispatch(new SendInvitationEmail($user));

        session()->flash('userMessage', 'User has been created!');
        return redirect()->action('Admin\UserController@index');
    }

    public function approve(User $user)
    {
        if ($user->approval !== 'approved') {
            $user->approval = 'approved';
            $user->save();

            $this->dispatch(new SendApprovedEmail($user));
            session()->flash('userMessage', 'User has been approved!');
        }

        return redirect()->back();
    }

    public function deny(User $user)
    {
        if ($user->role !== 'denied') {
            $user->approval = 'denied';
            $user->save();

            session()->flash('userMessage', 'User has been denied!');
        }
        return redirect()->back();
    }

    public function edit(User $user)
    {
        $organization = Organization::where('assigned_users', 'like', "%$user->id%")->first();
        return view('admin.users.edit', compact('user', 'organization'));
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

    private function handleUsersCSV($users) {
        $this->download_send_headers('users.csv');

        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('ID',
            'First Name',
            'Last Name',
            'Email',
            'Account Type',
            'Status',
            'Date of Birth',
            'Phone',
            'Address',
            'Apt/Unit',
            'City',
            'State',
            'Zip Code',
            'Hospital/Trauma Center Name',
            'Trauma Center Level',
            'NTDB/NTDS #',
            'TQIP #',
            'Address 1',
            'Address 2',
            'Address 3',
            'City',
            'State',
            'Zip Code',
            'Last 4 of SSN',
            'Credentials',
            'State License #',
        ));
        foreach ($users as $user) {
            fputcsv($output, array(
                $user->id,
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->role,
                $user->approval,
                $user->birthday,
                $user->phone,
                $user->address,
                $user->unit,
                $user->city,
                $user->state,
                $user->zipcode,
                $user->hospital_name,
                $user->hospital_level,
                $user->hospital_ntdb,
                $user->hospital_tqip,
                $user->hospital_address1,
                $user->hospital_address2,
                $user->hospital_address3,
                $user->hospital_city,
                $user->hospital_state,
                $user->hospital_zipcode,
                $user->ssn,
                $user->credentials,
                $user->state_license,
            ));
        }

        fclose($output);
        return ob_get_clean();
    }

    public function exportCourses(User $user) {
        $this->download_send_headers('courses.csv');
        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('ID',
            'First Name',
            'Last Name',
            'Email',
            'Account Type',
            'Status',
            'Date of Birth',
            'Phone',
            'Address',
            'Apt/Unit',
            'City',
            'State',
            'Zip Code',
            'Hospital/Trauma Center Name',
            'Trauma Center Level',
            'NTDB/NTDS #',
            'TQIP #',
            'Address 1',
            'Address 2',
            'Address 3',
            'City',
            'State',
            'Zip Code',
            'Last 4 of SSN',
            'Credentials',
            'State License #',
            'Course ID #',
            'Course Title',
            'Course Date',
            'Course Location',
            'Registered',
            'Completed',
            'Certified',
        ));
        foreach ($user->registrations as $registration) {
            if (!$registration->completed_at) continue;
            $course = $registration->course;
            fputcsv($output, array(
                $user->id,
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->role,
                $user->approval,
                $user->birthday,
                $user->phone,
                $user->address,
                $user->unit,
                $user->city,
                $user->state,
                $user->zipcode,
                $user->hospital_name,
                $user->hospital_level,
                $user->hospital_ntdb,
                $user->hospital_tqip,
                $user->hospital_address1,
                $user->hospital_address2,
                $user->hospital_address3,
                $user->hospital_city,
                $user->hospital_state,
                $user->hospital_zipcode,
                $user->ssn,
                $user->credentials,
                $user->state_license,
                $course->id,
                $course->title,
                $course->online_only ? 'Online' : $course->date_start . ' - ' . $course->date_end,
                $course->location,
                $registration->registered_at,
                $registration->completed_at,
                $registration->certified_at,
            ));
        }
        fclose($output);
        echo ob_get_clean();
        die;
    }
}
