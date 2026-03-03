<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'email' => 'required|email:rfc',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        // Kiểm tra user tồn tại
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại trong hệ thống',
            ])->withInput($request->only('email'));
        }

        // Kiểm tra user bị ban
        if ($user->status === 'banned') {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa',
            ])->withInput($request->only('email'));
        }

        // Kiểm tra user inactive
        if ($user->status === 'inactive') {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn chưa được kích hoạt',
            ])->withInput($request->only('email'));
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Mật khẩu không chính xác',
            ])->withInput($request->only('email'));
        }

        // Ghi lại thông tin đăng nhập
        $user->recordLogin($request->ip());

        // Đăng nhập user
        Auth::login($user, $request->has('remember'));

        // Redirect theo vai trò: admin vào admin dashboard, user thường vào audits
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return redirect()->route('audits.index')->with('success', 'Đăng nhập thành công!');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất');
    }

    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Gửi email reset mật khẩu
     */
    public function sendResetLink(Request $request)
    {
        // Validate email
        $validated = $request->validate([
            'email' => 'required|email:rfc',
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
        ]);

        // Kiểm tra user tồn tại
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            // Không tiết lộ thông tin (bảo mật)
            return back()->with('status', 'Nếu email tồn tại, bạn sẽ nhận được email reset mật khẩu');
        }

        // Gửi reset link
        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Email reset mật khẩu đã được gửi');
        }

        return back()->withErrors(['email' => 'Không thể gửi email reset mật khẩu']);
    }

    /**
     * Hiển thị form reset mật khẩu
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Xử lý reset mật khẩu
     */
    public function resetPassword(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'email' => 'required|email:rfc',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Reset password
        $status = Password::reset($validated, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại. Vui lòng đăng nhập');
        }

        return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn']);
    }
}
