<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Hiển thị form Login  (Trường hợp khách tự gõ URL login)
    public function showLogin()
    {
        if(Auth::check()) return redirect('/');
        return view('auth.login');
    }

    // Hiển thị form Register 
    public function showRegister()
    {
        if(Auth::check()) return redirect('/');
        return view('auth.register');
    }

    // Xử lý việc Log In
    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập Email.',
            'password.required' => 'Vui lòng nhập Mật khẩu.'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            // Xử lý Phân quyền
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập Admin thành công!');
            }
            
            // Nếu là người dùng bình thường
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công, chào mừng trở lại!');
        }

        return back()->withErrors([
            'auth' => 'Email hoặc mật khẩu không chính xác, vui lòng thử lại.',
        ])->onlyInput('email');
    }

    // Xử lý việc Register
    public function registerProcess(Request $request)
    {
        // Chuẩn hoá đầu vào
        if ($request->has('email')) {
            $request->merge([
                'email' => strtolower(trim($request->email))
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải dài hơn 6 ký tự.'
        ]);

        // Tạo user mới, Role mặc định trong DB đã là 'user'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        // Đăng nhập luôn sau khi đăng ký
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Tạo tài khoản thành công! Phá đảo Crocs mệt nghỉ nhé.');
    }

    // Xử lý việc Log Out
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Đã đăng xuất.');
    }
}
