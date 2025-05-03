<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Aturan;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
    $gejalas = Gejala::paginate(20, ['*'], 'gejala_page');
    $penyakits = Penyakit::paginate(15, ['*'], 'penyakit_page');
    $aturans = Aturan::paginate(20, ['*'], 'aturan_page');
    $users = User::paginate(5, ['*'], 'user_page');
    $allPenyakits = Penyakit::all();
    $allGejalas = Gejala::all();

        return view('dashboard', compact('gejalas', 'penyakits', 'aturans', 'users', 'allPenyakits', 'allGejalas'));
    }
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->back()->with('success', 'Registrasi berhasil. Selamat datang, ' . $user->name . '!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Registrasi gagal. Silakan coba lagi.');
    }
}

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        $message = 'Halo, ' . $user->name . '! Kamu berhasil login.';

        if ($user->is_admin) {
            return redirect()->route('dashboard')->with('success', $message);
        }

        return redirect()->route('home')->with('success', $message);
    }

    return redirect()->back()->withErrors(['login' => 'Email atau password salah.']);
}




public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home')->with('success', 'Kamu telah berhasil logout.');
}
public function storeGejala(Request $request)
{
    $request->validate([
        'kode_gejala' => 'required|unique:gejala,kode_gejala',
        'detail_gejala' => 'required',
    ]);

    Gejala::create([
        'kode_gejala' => strtoupper($request->kode_gejala),
        'detail_gejala' => $request->detail_gejala,
    ]);

    return redirect()->back()->with('success', 'Gejala berhasil ditambahkan!');
}
public function destroyGejala($id)
{
    $gejala = Gejala::findOrFail($id);
    $gejala->delete();

    return redirect()->route('dashboard')->with('success', 'Data gejala berhasil dihapus!');
}
public function updateGejala(Request $request, $id)
{
    $request->validate([
        'kode_gejala' => 'required|unique:gejala,kode_gejala,' . $id,
        'detail_gejala' => 'required'
    ]);

    $gejala = Gejala::findOrFail($id);
    $gejala->kode_gejala = $request->kode_gejala;
    $gejala->detail_gejala = $request->detail_gejala;
    $gejala->save();

    return redirect()->back()->with('success', 'Gejala berhasil diperbarui.');
}
public function storePenyakit(Request $request)
{
    $request->validate([
        'kode_penyakit' => 'required|unique:penyakit,kode_penyakit',
        'nama_penyakit' => 'required',
        'solusi' => 'required',
    ]);

    Penyakit::create([
        'kode_penyakit' => strtoupper($request->kode_penyakit),
        'nama_penyakit' => $request->nama_penyakit,
        'solusi' => $request->solusi,
    ]);

    return back()->with('success', 'Data penyakit berhasil ditambahkan.');
}

public function updatePenyakit(Request $request, $id)
{
    $request->validate([
        'kode_penyakit' => 'required|unique:penyakit,kode_penyakit,' . $id,
        'nama_penyakit' => 'required',
        'solusi' => 'required',
    ]);

    $penyakit = Penyakit::findOrFail($id);
    $penyakit->kode_penyakit = $request->kode_penyakit;
    $penyakit->nama_penyakit = $request->nama_penyakit;
    $penyakit->solusi = $request->solusi;
    $penyakit->save();

    return redirect()->back()->with('success', 'Penyakit berhasil diperbarui.');
}


public function destroyPenyakit($id)
{
    $penyakit = Penyakit::findOrFail($id);
    $penyakit->delete();

    return redirect()->route('dashboard')->with('success', 'Data penyakit berhasil dihapus!');
}
// public function storeAturan(Request $request)
// {
//     $request->validate([
//         'kode_penyakit' => 'required|exists:penyakit,kode_penyakit',
//         'kode_gejala' => 'required|exists:gejala,kode_gejala',
//     ]);

//     // Cek jika sudah ada
//     $exists = Aturan::where('kode_penyakit', $request->kode_penyakit)
//                     ->where('kode_gejala', $request->kode_gejala)
//                     ->exists();

//     if ($exists) {
//         return back()->with('error', 'Aturan ini sudah ada.');
//     }

//     Aturan::create([
//         'kode_penyakit' => strtoupper($request->kode_penyakit),
//         'kode_gejala' => strtoupper($request->kode_gejala),
//     ]);

//     return back()->with('success', 'Aturan berhasil ditambahkan.');
// }
public function storeAturan(Request $request)
{
    $request->validate([
        'kode_penyakit' => 'required|exists:penyakit,kode_penyakit',
        'kode_gejala' => 'required|array|min:1',
        'kode_gejala.*' => 'exists:gejala,kode_gejala',
    ]);

    $kodePenyakit = strtoupper($request->kode_penyakit);
    $kodeGejalas = array_map('strtoupper', $request->kode_gejala);

    $inserted = 0;
    foreach ($kodeGejalas as $kodeGejala) {
        $exists = Aturan::where('kode_penyakit', $kodePenyakit)
                        ->where('kode_gejala', $kodeGejala)
                        ->exists();

        if (!$exists) {
            Aturan::create([
                'kode_penyakit' => $kodePenyakit,
                'kode_gejala' => $kodeGejala,
            ]);
            $inserted++;
        }
    }

    if ($inserted > 0) {
        return back()->with('success', "$inserted aturan berhasil ditambahkan.");
    } else {
        return back()->with('error', 'Semua kombinasi aturan sudah ada.');
    }
}


public function updateAturan(Request $request, $id)
{
    $request->validate([
        'kode_penyakit' => 'required|exists:penyakit,kode_penyakit',
        'kode_gejala' => 'required|exists:gejala,kode_gejala',
    ]);

    $exists = Aturan::where('kode_penyakit', $request->kode_penyakit)
                    ->where('kode_gejala', $request->kode_gejala)
                    ->where('id', '!=', $id)
                    ->exists();

    if ($exists) {
        return back()->withErrors(['kombinasi' => 'Aturan kode penyakit dan gejala sudah ada.'])->withInput();
    }

    $aturan = Aturan::findOrFail($id);
    $aturan->update([
        'kode_penyakit' => strtoupper($request->kode_penyakit),
        'kode_gejala' => strtoupper($request->kode_gejala),
    ]);

    return back()->with('success', 'Data aturan berhasil diperbarui.');
}


public function destroyAturan($id)
{
    $aturan = Aturan::findOrFail($id);
    $aturan->delete();

    return back()->with('success', 'Aturan berhasil dihapus.');
}
public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'is_admin' => 'required|boolean',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Hash password
        'is_admin' => $request->is_admin,
    ]);

    return back()->with('success', 'User berhasil ditambahkan.');
}
public function destroyUser($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return back()->with('success', 'User berhasil dihapus.');
}










}
