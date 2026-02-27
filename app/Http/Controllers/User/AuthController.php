<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('User/Login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Allow login by phone or email
        $field = filter_var($credentials['phone'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (! Auth::attempt([$field => $credentials['phone'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['phone' => 'Invalid credentials.'])->onlyInput('phone');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'));
    }

    public function showRegister(): Response
    {
        return Inertia::render('User/Register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|regex:/^[6-9]\d{9}$/|unique:users,phone',
            'email'    => 'nullable|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'email'    => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ── Profile ───────────────────────────────────────────────

    public function profile(): Response
    {
        return Inertia::render('User/Profile', [
            'user' => auth('web')->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth('web')->user();

        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'nullable|email|unique:users,email,' . $user->id,
            'phone'            => 'required|string|regex:/^[6-9]\d{9}$/|unique:users,phone,' . $user->id,
            'address'          => 'nullable|string|max:500',
            'city'             => 'nullable|string|max:100',
            'pincode'          => 'nullable|string|max:10',
            'current_password' => 'nullable|string',
            'password'         => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Verify current password if trying to change password
        if (! empty($data['password'])) {
            if (empty($data['current_password']) || ! Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $updateData = [
            'name'    => $data['name'],
            'email'   => $data['email'] ?? null,
            'phone'   => $data['phone'],
            'address' => $data['address'] ?? null,
            'city'    => $data['city']    ?? null,
            'pincode' => $data['pincode'] ?? null,
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }
}