<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\User;
use Spatie\Permission\Models\Permission;
use DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $role = Role::orderBy('name', 'ASC')->get();
        return view('users.create', compact('role'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:75',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::firstOrCreate([
            'email' => $request->email
        ], [
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'status' => true
        ]);

        $user->assignRole($request->role);
        return redirect(route('users.index'))->with(['success' => 'User : <strong>' . $user->name . '</strong> Ditambahkan']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:75',
            'email' => 'required|email|exists:users,email',
            'password' => 'nullable|min:6',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        $user = User::findOrFail($id);

        $password = !empty($request->password) ? bcrypt($request->password):$user->password;
        $user->update([
            'name' => $request->name,
            'password' => $password
        ]);
        return redirect(route('users.index'))->with(['success' => 'User : <strong>' . $user->name . '</strong> Diperbaharui']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with(['success' => 'User : <strong>' . $user->name . '</strong> Dihapus']);
    }

    public function rolePermission(Request $request)
    {
        $role = $request->get('role');

        // set variable null
        $permissions = null;
        $hasPermission = null;

        // amil data role
        $roles = Role::all()->pluck('name');

        //apabila parameter role terpenuhi
        if (!empty($role)) {
        //select role berdasarkan nama, ini sejenis dengan method find()
        $getRole = Role::findByName($role);

        //Query untuk mengambil permission yang telah dimiliki oleh role terkait
        $hasPermission = DB::table('role_has_permissions')
            ->select('permissions.name')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_id', $getRole->id)->get()->pluck('name')->all();

        //Mengambil data permission
        $permissions = Permission::all()->pluck('name');
        }

        return view('users.role_permission', compact('roles', 'permissions', 'hasPermission'));
    }

    public function addPermission(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:permissions'
        ]);

        $permissions = Permission::firstOrCreate([
            'name' => $request->name
        ]);

        return redirect()->back();
    }

    public function setRolePermission(Request $request, $role)
    {
         // select role berdasarkan nama
         $role = Role::findByName($role);

         //fungsi syncPermission akan menghapus semua permission yang dimiliki role tersebut
         //kemudian di-assign kembali sehingga tidak terjadi duplicate data
         $role->syncPermissions($request->permission);
         return redirect()->back()->with(['success' => 'Permission untuk role berhasil diperbarui!']);
    }

    public function roles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all()->pluck('name');
        return view('users.roles', compact('user', 'roles'));
    }

    public function setRole(Request $request, $id)
    {
        $this->validate($request, [
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);
        //menggunakan syncRoles agar terlebih dahulu menghapus semua role yang dimiliki
        //kemudian di-set kembali agar tidak terjadi duplicate
        $user->syncRoles($request->role);
        return redirect()->back()->with(['success' => 'Role berhasil diubah']);
    }

    public function updateStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'User status updated successfully.']);
    }
}
