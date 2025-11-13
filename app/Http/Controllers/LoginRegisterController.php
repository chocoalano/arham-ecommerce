<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Throwable;

class LoginRegisterController extends Controller
{
    public function index()
    {
        // Jika sudah login sebagai customer → langsung redirect
        if (Auth::guard('customer')->check()) {
            return redirect()->intended(route('home'));
        }

        return view('login-register');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
            'redirect' => ['nullable', 'url'],
        ]);

        try {
            $remember = (bool) ($validated['remember'] ?? false);

            if (! Auth::guard('customer')->attempt(
                ['email' => $validated['email'], 'password' => $validated['password']],
                $remember
            )) {
                return back()
                    ->withErrors(['email' => 'Email atau password salah'])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            $redirect = $validated['redirect'] ?? route('home');

            return redirect()->intended($redirect);
        } catch (Throwable $e) {
            Log::error('Customer login failed: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.')->withInput();
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'redirect' => ['nullable', 'url'],
        ]);

        try {
            $name = trim($validated['first_name'].' '.($validated['last_name'] ?? ''));

            $customer = Customer::create([
                'name' => $name,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::guard('customer')->login($customer);
            $request->session()->regenerate();

            $redirect = $validated['redirect'] ?? route('home');

            return redirect()->intended($redirect)->with('success', 'Registrasi berhasil');
        } catch (Throwable $e) {
            Log::error('Customer register failed: '.$e->getMessage());

            return back()->with('error', 'Registrasi gagal, silakan coba lagi.')->withInput();
        }
    }
}
