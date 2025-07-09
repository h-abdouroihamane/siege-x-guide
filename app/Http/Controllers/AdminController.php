<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Operator;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Login');
    }

    public function dashboard(Request $request)
    {
        if (!Auth::user()) {
            return redirect()->route('admin.login');
        }

        return Inertia::render('admin/Dashboard',
            [
                'editRoute' => route('operator.selectForEditing'),
                'createRoute' => route('operator.create'),
                'message' => $request->session()->get('message')
            ]
        );
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

  }
