<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Favorite;
use App\Models\Industry;
use App\Models\Philanthropist;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use function PHPUnit\Framework\isNull;

class UsersController extends Controller
{
    public function list()
    {
        $breadcrumbs = [
            ['link' => "admin/users/list", 'name' => "Users"], ['name' => "Add"]
        ];
        $users = User::all();
        $roles = Role::all();
        return view('admin.users.admin-users-list', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function edit(User $user)
    {
        $breadcrumbs = [
            ['link' => "admin/users/list", 'name' => "Users"], ['name' => "Edit"]
        ];

        $roles = Role::all();
        return view('admin.users.admin-users-edit', [
            'title' => 'EDIT USER',
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request)
    {
        $user_id = \request()->id;
        $isValidated = null;
//
//        $arr = [1,2,3,4];
//        $arr[] = 5;
//        array_unshift($arr, 0);
//        $bas = array_shift($arr);
//        $arr2 = ['a' => 'b', 'c' => 'd'];
//        $arr2['e']  = 'f';

//        'email' => [
//        'required','string','email','max:255',
//        Rule::unique('users')->ignore($request->id),

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|exists:roles,key',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user_id
        ];

        if (!is_null($request->password)) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        if (!empty($request->profile_image && $request->hasFile('file'))) {
            $rules['profile_image'] = 'mimes:jpeg,bmp,png';
        }

        $request->validate($rules);

        $updatingUserInfo = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role_key' => $request->role
        ];

        if (!is_null($request->password)) {
            $updatingUserInfo['password'] = Hash::make($request->password);
        }

        if (!is_null($request->profile_image)) {
            $request->file('profile_image')->store('profile-images', 'public');
            $updatingUserInfo['profile_image'] = $request->file('profile_image')->hashName();

//            $image = Image::make($request->file('profile_image'));
//            $image->resize(400, null, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//            Response::make($image->encode('jpeg'));
//            $updatingUserInfo['profile_image'] = $image;
        }

        $updatedUser = User::where('id', $user_id)->update($updatingUserInfo);
        if ($updatedUser) {
            return redirect()->back()->with('success', 'User information updated successfully!');
        } else {
            return redirect()->back()->with('error', 'An error occurred while updating user!');
        }
    }

    public function new()
    {
        $breadcrumbs = [
            ['link' => "admin/users/list", 'name' => "Users"], ['name' => "Add"]
        ];
        $roles = Role::all();
        return view('admin.users.admin-users-new', [
            'breadcrumbs' => $breadcrumbs,
            'roles' => $roles
        ]);
    }

    public function store(Request $request, UserStoreRequest $formRequest)
    {
        $createdUser = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_key' => $request->role
        ]);

        if ($createdUser) {
            return redirect(route('admin.users.list'))->with('success', 'User created successfully.');
        }

        return redirect(redirect()->back()->with('error', 'Something went wrong while creating user.'));
    }

    public function storeWithAjax(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id'
        ]);


        if ($validated) {
            $createdUser = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role
            ]);

            if ($createdUser) {
                return response()->json(['success' => 'User created successfully.']);
            }
        }
        return response()->json(['success' => 'Something went wrong while creating user.']);
    }

    public function delete(Request $request)
    {
        $user_id = $request->id;
        $deletedUser = User::where('id', $user_id)->delete();
        if ($deletedUser) {
            return redirect()->back()->with('success', 'User is deleted successfully!');
        } else {
            return redirect()->back()->with('success', 'Something went wrong while deleting user!');
        }

    }

    public function dashboard()
    {
        $breadcrumbs = [
            ['link' => "admin/dashboard", 'name' => "Index"]
        ];
        $industries = Industry::orderBy('name')->get();
        $statuses = Philanthropist::getPossibleStatuses();
        $philanthropists = Philanthropist::with('business')->orderBy('created_at','desc')->get();
        return view('admin.dashboard', [
            "industries" => $industries,
            "philanthropists" => $philanthropists,
            "statuses" => $statuses,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function memberDashboard()
    {
        $favoriteCount = Favorite::where('user_id', '=', auth()->id())->count();
        $pendingPhilanthropistCount = Philanthropist::where('created_by', '=', auth()->id())->where('status', '=', 'pending')->count();
        $approvedPhilanthropistCount = Philanthropist::where('created_by', '=', auth()->id())->where('status', '=', 'active')->count();
        return view('member.dashboard', [
            'favoriteCount' => $favoriteCount,
            'pendingPhilanthropistCount' => $pendingPhilanthropistCount,
            'approvedPhilanthropistCount' => $approvedPhilanthropistCount
        ]);
    }

    public function memberIndex()
    {
        $user = Auth::user();
        return view('member.my_account', ['user' => $user]);
    }

    public function membersUpdate(Request $request)
    {
        $validationRules = [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'required|email|unique:users,email,' . Auth::id()
        ];

        $values = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email
        ];

        if ($request->password || $request->password_confirmation) {
            $validationRules['password'] = 'required|string|min:8|confirmed';
            $values['password'] = Hash::make($request->password);
        }

        if (!is_null($request->profile_image)) {
            $request->file('profile_image')->store('profile-images', 'public');
            $values['profile_image'] = $request->file('profile_image')->hashName();
        }

        $validated = $request->validate($validationRules);

        $executed = Auth::user()->update($values);
        if ($executed){
            return redirect()->back()->with('success', 'Changes saved successfully.');
        }
        return redirect()->back()->with('error', 'An error occurred while saving changes.');
    }

    public function myAccountIndex(Request $request){
        $user = Auth::user();
        $breadcrumbs = [];
        if($user->hasRole('admin')){
            $breadcrumbs = [
                ['link' => "admin/dashboard", 'name' => "Dashboard"], ['name' => "My Account"]
            ];
            $roles = Role::all();
            return view('admin.users.admin-users-edit', [
                'title' => 'MY ACCOUNT',
                'breadcrumbs' => $breadcrumbs,
                'user' => $user,
                'roles' => $roles
            ]);
        }
    }


}
