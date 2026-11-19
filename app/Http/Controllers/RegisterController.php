<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function view()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Public registration always creates a Student. The Admin sets other roles.
        $studentRole = Role::where('name', 'Student')->first();

        User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $studentRole->id,
            'is_active' => false,   // waits for Admin approval
            'is_admin' => false,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please wait for the Admin to approve your account.');
    }
}
