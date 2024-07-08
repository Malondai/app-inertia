<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return Inertia::render('User/index', ['users' => $user]);
    }

    public function create()
    {
        $user = null;
        return Inertia::render('User/create', ['users' => $user]);
    }

    public function edit(Request $request)
    {
        $user = User::find($request->id);
        return Inertia::render('User/create', ['users' => $user]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if (isset($request->id)) {
                $user = User::find($request->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();
            } else {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();
            }

            DB::commit();
            return redirect('user');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->delete();
            DB::commit();
            return redirect('user');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
