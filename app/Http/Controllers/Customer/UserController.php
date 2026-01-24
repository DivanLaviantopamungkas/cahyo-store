<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('customer.pages.login');
    }

    /**
     * Handle login request (WEB)
     */
    public function login(Request $request)
    {
        // Validation dengan pesan bahasa Indonesia
        $validator = Validator::make($request->all(), [
            'whatsapp' => 'required|string',
            'password' => 'required|string',
        ], [
            'whatsapp.required' => 'Nomor WhatsApp harus diisi',
            'whatsapp.string' => 'Nomor WhatsApp harus berupa teks',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa teks',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Format whatsapp (hapus spasi dan karakter khusus)
        $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);

        // Find user
        $user = User::where('whatsapp', $whatsapp)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Nomor WhatsApp belum terdaftar')
                ->withInput();
        }

        if ($user->is_active == 0) {
            return redirect()->back()
                ->with('error', 'Akun tidak aktif. Hubungi admin.')
                ->withInput();
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password salah')
                ->withInput();
        }

        Auth::login($user, $request->has('remember'));

        // Cek pending checkout setelah login berhasil
        if (session()->has('pending_checkout')) {
            $pendingData = session('pending_checkout');
            $params = http_build_query([
                'nominal_id' => $pendingData['nominal_id'] ?? null,
                'phone' => $pendingData['phone'] ?? null,
                'customer_id' => $pendingData['customer_id'] ?? null
            ]);

            session()->forget('pending_checkout');

            return redirect()->route('checkout.create', [
                'product_slug' => $pendingData['product_slug']
            ]) . ($params ? '?' . $params : '');
        }

        return redirect()->intended('/')
            ->with('success', 'Login berhasil! Selamat datang ' . $user->name);

        // Redirect to intended page or home
        return redirect()->intended('/')
            ->with('success', 'Login berhasil! Selamat datang ' . $user->name);
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('customer.pages.register');
    }

    /**
     * Handle register request (WEB)
     */
    public function register(Request $request)
    {
        // Format whatsapp
        $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
        $request->merge(['whatsapp' => $whatsapp]);

        // Validation dengan pesan bahasa Indonesia
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'whatsapp' => 'required|string|unique:users,whatsapp|regex:/^[0-9]{10,15}$/',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 100 karakter',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi',
            'whatsapp.string' => 'Nomor WhatsApp harus berupa teks',
            'whatsapp.unique' => 'Nomor WhatsApp sudah terdaftar',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid (10-15 digit angka)',
            'password.required' => 'Password wajib diisi',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan periksa form kembali');
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'whatsapp' => $whatsapp,
            'password' => Hash::make($request->password),
            'balance' => 0.00,
            'is_active' => 1,
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect('/')
            ->with('success', 'Pendaftaran berhasil! Selamat datang ' . $user->name);
    }

    // Claude
    public function profile()
    {
        return view('customer.pages.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Format whatsapp
        $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'whatsapp' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,whatsapp,' . $user->id,
            'email' => 'nullable|email|max:100|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama maksimal 100 karakter',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid (10-15 digit angka)',
            'whatsapp.unique' => 'Nomor WhatsApp sudah digunakan',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal memperbarui profile');
        }

        // Update user
        $user->update([
            'name' => $request->name,
            'whatsapp' => $whatsapp,
            'email' => $request->email,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'Profile berhasil diperbarui');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal mengubah password');
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password lama tidak sesuai');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('user.index')
            ->with('success', 'Password berhasil diubah');
    }

    /**
     * Handle logout (WEB)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Logout berhasil');
    }
}
