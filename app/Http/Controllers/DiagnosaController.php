<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Aturan;

class DiagnosaController extends Controller
{
    // Menampilkan form diagnosa
    public function index()
    {
        $gejalas = Gejala::all();
        return view('home', compact('gejalas'));
    }

    // Memproses hasil diagnosa
//     public function prosesDiagnosa(Request $request)
// {
//     $inputGejala = $request->input('gejala', []);

//     $penyakits = Penyakit::with('gejalas')->get();

//     $hasilDiagnosa = false; // default: tidak ditemukan
//     $popupStatus = 'error';
//     $popupMessage = 'Diagnosa gagal. Penyakit tidak ditemukan berdasarkan gejala anda.';

//     foreach ($penyakits as $penyakit) {
//         $gejalaPenyakit = $penyakit->gejalas->pluck('kode_gejala')->toArray();
    
//         // Skip jika penyakit belum memiliki aturan gejala
//         if (empty($gejalaPenyakit)) {
//             continue;
//         }
    
//         if (!array_diff($gejalaPenyakit, $inputGejala)) {
//             $hasilDiagnosa = [
//                 'penyakit' => $penyakit,
//                 'gejalas' => Gejala::whereIn('kode_gejala', $inputGejala)->get(),
//             ];
//             $popupStatus = 'success';
//             $popupMessage = 'Diagnosa berhasil! Penyakit ditemukan.';
//             break;
//         }
//     }    

//     $gejalas = Gejala::all();
//     return view('home', compact('gejalas', 'hasilDiagnosa', 'popupStatus', 'popupMessage'));
// }
public function prosesDiagnosa(Request $request)
{
    $inputGejala = $request->input('gejala', []);

    // Ambil semua kode penyakit yang ada di aturan
    $kodePenyakitList = Aturan::pluck('kode_penyakit')->unique();

    $hasilDiagnosa = false;
    $popupStatus = 'error';
    $popupMessage = 'Diagnosa gagal. Penyakit tidak ditemukan berdasarkan gejala anda.';

    foreach ($kodePenyakitList as $kodePenyakit) {
        // Ambil semua gejala yang menjadi syarat untuk penyakit ini
        $kodeGejalaAturan = Aturan::where('kode_penyakit', $kodePenyakit)->pluck('kode_gejala')->toArray();

        // Jika semua gejala aturan ada di input user → penyakit cocok
        if (!array_diff($kodeGejalaAturan, $inputGejala)) {
            $penyakit = Penyakit::where('kode_penyakit', $kodePenyakit)->first();
            $hasilDiagnosa = [
                'penyakit' => $penyakit,
                'gejalas' => Gejala::whereIn('kode_gejala', $inputGejala)->get(),
            ];
            $popupStatus = 'success';
            $popupMessage = 'Diagnosa berhasil! Penyakit ditemukan.';
            break; // Ambil penyakit pertama yang cocok
        }
    }

    $gejalas = Gejala::all();
    return view('home', compact('gejalas', 'hasilDiagnosa', 'popupStatus', 'popupMessage'));
}





}
