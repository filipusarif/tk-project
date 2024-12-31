<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register pengguna
    public function register(){
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:admin,kepala_sekolah,orang_tua',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'role.in' => 'Role harus salah satu dari: admin, kepala_sekolah, atau orang_tua.',
        ]);
        $role = $request->role ?? 'admin';
        // dd($role);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        return redirect('/login');
        
        // return response()->json(['message' => 'Registrasi berhasil'], 201);
    }

    // Login pengguna
    public function login(){
        return view('auth.login');
    }


    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // if (!Auth::attempt($credentials)) {
        //     throw ValidationException::withMessages([
        //         'email' => ['Email atau password salah.'],
        //     ]);
        // }

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }


        $request->session()->regenerate();

        // Redirect based on role
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard')->with('success', 'Berhasil login!');
            case 'kepala_sekolah':
                return redirect('/kepala/dashboard')->with('success', 'Berhasil login!');
            case 'orang_tua':
                return redirect('/pendaftaran')->with('success', 'Berhasil login!');
            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Role tidak dikenali.']);
        }
    }

    // Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function registeruser(){
        return view('admin.user-add');
    }

    public function registerallpost(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:admin,kepala_sekolah,orang_tua',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'role.in' => 'Role harus salah satu dari: admin, kepala_sekolah, atau orang_tua.',
        ]);
        // $role = $request->role ?? 'orang_tua';
        // dd($role);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/admin/user');
        
        // return response()->json(['message' => 'Registrasi berhasil'], 201);
    }

    public function user(){
        $users = User::all();
        return view('admin.user-list', compact('users'));
    }

    public function user_edit($id)
    {
        
        $user = User::findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }

    public function user_update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'jenis_kelamin' => 'required',
        //     'tanggal_lahir' => 'required|date',
        //     'alamat' => 'required|string',
        // ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:admin,kepala_sekolah,orang_tua',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'role.in' => 'Role harus salah satu dari: admin, kepala_sekolah, atau orang_tua.',
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('user')->with('success', 'Data user berhasil diubah.');
    }

    public function user_destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user')->with('success', 'Data user berhasil dihapus.');
    }
}
