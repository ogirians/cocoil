<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use Spatie\Permission\Models\Permission;
use App\User;
use DB;
use File;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['gudang'])->orderBy('created_at', 'DESC')->Admin();
    //$gudang = gudang::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $users = $users->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $users = $users->paginate(10);
        return new UserCollection($users);
    }

    public function userLists()
    {
        $user = User::where('role', '!=', null)->get();
        return new UserCollection($user);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|string',
            'gudang_id' => 'required|exists:gudangs,id',
            'photo' => 'required|image'
        ]);

        DB::beginTransaction();
        try {
            $name = NULL;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/admin', $name);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
                'photo' => $name,
                'gudang_id' => $request->gudang_id,
                'role' => 1
            ]);
            $user->assignRole('admin');
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()], 200);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'required|email',
            'password' => 'nullable|min:6|string',
            'gudang_id' => 'required|exists:gudangs,id',
            'photo' => 'nullable|image'
        ]);

        try {
            $user = User::find($id);
            $password = $request->password != '' ? bcrypt($request->password):$user->password;
            $filename = $user->photo;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                File::delete(storage_path('app/public/couriers/' . $filename));
                $filename = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/couriers', $filename);
            }

            $user->update([
                'name' => $request->name,
                'password' => $password,
                'photo' => $filename,
                'gudang_id' => $request->gudang_id
            ]);
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'data' => $e->getMessage()], 200);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        File::delete(storage_path('app/public/couriers/' . $user->photo));
        $user->delete();
        return response()->json(['status' => 'success']);
    }

    public function getUserLogin()
    {
        $user = request()->user();
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (request()->user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        $user['permission'] = $permissions;
        return response()->json(['status' => 'success', 'data' => $user]);
    }
}
