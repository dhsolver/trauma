<?php namespace App\Http\Controllers\Admin;

use App\User;
use App\Organization;
use App\Course;
use App\Jobs\SendInvitationEmail;
use App\Jobs\SendApprovedEmail;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\OrganizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends AdminController
{
    public function index(Request $request)
    {
        $organizations = Organization::where('id', '>', 0)->get();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create() {
        return view('admin.organizations.create');
    }

    public function store(OrganizationRequest $request)
    {
        $organization = new Organization($request->all());
        $organization->save();

        // session()->flash('message', 'Organization has been created!');
        return redirect()->action('Admin\OrganizationController@index');
    }

    public function edit(Organization $organization)
    {
        $users = User::where('role', 'faculty')
            ->orWhere('role', 'manager')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        if (empty($organization->assigned_users)) {
            $organization->assigned_users = [];
        }

        $courses = Course::where('organization_id', $organization->id)->get();

        return view('admin.organizations.edit', compact('organization', 'courses', 'users'));
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->all());

        // session()->flash('userMessage', 'User has been updated!');
        return redirect()->action('Admin\OrganizationController@edit', $organization);
    }

    public function updateAssignedUsers(Organization $organization, Request $request)
    {
        $assigned_users = $organization->assigned_users;
        if (empty($assigned_users)) {
            $assigned_users = [];
        }
        $user_id = $request->user_id;
        if (in_array($user_id, $assigned_users)) {
            $key = array_search($user_id, $assigned_users);
            unset($assigned_users[$key]);
        }
        else {
            $assigned_users[] = $user_id;
        }

        $organization->assigned_users = $assigned_users;
        $organization->save();
        
        return response()->json
        ([
            'success' => true,
            'redirect' => action('Admin\OrganizationController@edit', [
                'organization' => $organization
            ])
        ]);
    }
}
