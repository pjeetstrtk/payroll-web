<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate(['emp_id' => 'required|string', 'password' => 'required|string']);
        $user = User::find($cred['emp_id']);

        if (! $user || $user->is_active !== 'Y' || ! $user->user_pass) {
            return back()->withInput($request->only('emp_id'))->withErrors(['emp_id' => 'User information is not correct']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showPassword()
    {
        return view('auth.password');
    }

    /** Same rules as the original frmChangePass (5-15 chars, upper, lower, digit, special). */
    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', 'min:5', 'max:15', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/', 'confirmed'],
        ], [
            'new_password.regex' => 'Password should contain a lower case letter, an upper case letter, a number and a special character.',
        ]);
        $user = $request->user();
        if (! Hash::check($data['current_password'], $user->user_pass)) {
            return back()->withErrors(['current_password' => "Old Password doesn't Match"]);
        }
        DB::table('tbl_user')->where('emp_id', $user->emp_id)->update(['user_pass' => Hash::make($data['new_password'])]);

        return back()->with('status', 'Password Changed Successfully');
    }
}
