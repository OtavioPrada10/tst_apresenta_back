<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'cpf' => 'required|unique:users',
            'type' => 'required|in:Física,Jurídica',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'cpf' => $request->cpf,
            'type' => $request->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'cpf' => 'sometimes|required|unique:users,cpf,' . $id,
            'type' => 'sometimes|required|in:Física,Jurídica',
            'phone' => 'sometimes|nullable|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string',
        ]);

        $user = User::findOrFail($id);

        $data = $request->only(['name', 'cpf', 'type', 'phone', 'email']);
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
