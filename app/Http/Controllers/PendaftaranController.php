<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Pembayaran;
use Midtrans\Snap;
use Illuminate\Support\Facades\Validator;


class PendaftaranController extends Controller
{
    public function daftar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'required|digits:16|numeric',
            'kk' => 'required|digits:16|numeric',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'akte' => 'nullable|string|max:50',
            'tinggi' => 'required|numeric|min:30|max:300',
            'berat' => 'required|numeric|min:1|max:300',
            'agama' => 'required|string|max:50',
            'kewarganegaraan' => 'required|string|max:50',
            'jumlah_saudara' => 'nullable|integer|min:0|max:20',
            'berkebutuhan_khusus' => 'required|in:Y,T',
            'jarak' => 'nullable|string|max:50',
            'alamat' => 'required|string|max:500',
            'waktu' => 'nullable|string|max:50',
        ], [
            // Pesan error dalam Bahasa Indonesia
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-Laki atau Perempuan.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            'kk.required' => 'No KK wajib diisi.',
            'kk.digits' => 'No KK harus terdiri dari 16 angka.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tinggi.required' => 'Tinggi badan wajib diisi.',
            'tinggi.numeric' => 'Tinggi badan harus berupa angka.',
            'berat.required' => 'Berat badan wajib diisi.',
            'agama.required' => 'Agama wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
        ]);
    
        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Siswa::updateOrCreate(
            ['user_id' => auth()->id()], 
            [ 
                'user_id' => auth()->id(),
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nik' => $request->nik,
                'kk' => $request->kk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'akte' => $request->akte,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'agama' => $request->agama,
                'kewarganegaraan' => $request->kewarganegaraan,
                'jumlah_saudara' => $request->jumlah_saudara,
                'berkebutuhan_khusus' => $request->berkebutuhan_khusus,
                'jarak' => $request->jarak,
                'alamat' => $request->alamat,
                'waktu' => $request->waktu,
            ]
        );

        // Redirect ke route pendaftaran
        return redirect()->route('orang-tua');
    }


    public function pendaftaran()
    {
        
        $siswa = Siswa::where('user_id', auth()->id())->first();
        return view('pendaftaran.dashboard', compact('siswa'));
    }

    public function orang_tua(){
        $siswa = Siswa::where('user_id', auth()->id())->first();

        return view('pendaftaran.orang-tua', compact('siswa'));
    }

    public function daftar_ortu(Request $request){

        $request->validate([
            // Validasi untuk Ayah
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'required|digits:16|numeric',
            'pendidikan_ayah' => 'required|string|max:100',
            'pekerjaan_ayah' => 'required|string|max:100',
            'email_ayah' => 'nullable|email|max:255',
            'tempat_lahir_ayah' => 'required|string|max:100',
            'tanggal_lahir_ayah' => 'required|date|before:today',
            'no_telp_ayah' => 'required|digits_between:10,15|numeric',
            'penghasilan_ayah' => 'required|in:1,2,3,4,5',
    
            // Validasi untuk Ibu
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'required|digits:16|numeric',
            'pendidikan_ibu' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:100',
            'email_ibu' => 'nullable|email|max:255',
            'tempat_lahir_ibu' => 'required|string|max:100',
            'tanggal_lahir_ibu' => 'required|date|before:today',
            'no_telp_ibu' => 'required|digits_between:10,15|numeric',
            'penghasilan_ibu' => 'required|in:1,2,3,4,5',
        ], [
            // Pesan error custom
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus tepat 16 angka.',
            'digits_between' => ':attribute harus antara 10 hingga 15 angka.',
            'email' => ':attribute harus berupa email yang valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before' => ':attribute harus sebelum hari ini.',
            'in' => ':attribute yang dipilih tidak valid.',
        ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

    

        Siswa::updateOrCreate(
            ['user_id' => auth()->id()], 
            [ 
                'user_id' => auth()->id(),
                'nama_ayah' => $request->nama_ayah,
                'nik_ayah' => $request->nik_ayah,
                'tempat_lahir_ayah' => $request->tempat_lahir_ayah,
                'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'email_ayah' => $request->email_ayah,
                'no_telp_ayah' => $request->no_telp_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'nik_ibu' => $request->nik_ibu,
                'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
                'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'email_ibu' => $request->email_ibu,
                'no_telp_ibu' => $request->no_telp_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
            ]
        );
        return redirect()->route('dokumen');
    }

    public function dokumen(){
        $berkas = Siswa::where('user_id', auth()->id())->first();
        
        return view('pendaftaran.berkas', compact('berkas'));
    }

    public function daftar_berkas(Request $request){

        // Validasi file
        $request->validate([
            'file_akta_kelahiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_foto' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'file_imunisasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'file_akta_kelahiran.required' => 'File Akta Kelahiran wajib diunggah.',
            'file_akta_kelahiran.mimes' => 'File Akta Kelahiran harus berupa PDF, JPG,jpeg, atau PNG.',
            'file_akta_kelahiran.max' => 'Ukuran File Akta Kelahiran tidak boleh lebih dari 2MB.',
            'file_kk.required' => 'File KK wajib diunggah.',
            'file_kk.mimes' => 'File KK harus berupa PDF, JPG, atau PNG.',
            'file_kk.max' => 'Ukuran File KK tidak boleh lebih dari 2MB.',
            'file_foto.required' => 'File Foto wajib diunggah.',
            'file_foto.mimes' => 'File Foto harus berupa JPG atau PNG.',
            'file_foto.max' => 'Ukuran File Foto tidak boleh lebih dari 1MB.',
            'file_imunisasi.mimes' => 'File Imunisasi harus berupa PDF, JPG, atau PNG.',
            'file_imunisasi.max' => 'Ukuran File Imunisasi tidak boleh lebih dari 2MB.',
        ]);
            
        // Lokasi folder penyimpanan
        $destinationPath = public_path('berkas');
        // Inisialisasi nama file untuk penyimpanan di database
        $aktaKelahiranName = $kkName = $fotoName = $imunisasiName = null;

        // Simpan file jika ada
        if ($request->hasFile('file_akta_kelahiran')) {
            $aktaKelahiranName = time() . '_akta.' . $request->file('file_akta_kelahiran')->getClientOriginalExtension();
            $request->file('file_akta_kelahiran')->move($destinationPath, $aktaKelahiranName);
        }

        if ($request->hasFile('file_kk')) {
            $kkName = time() . '_kk.' . $request->file('file_kk')->getClientOriginalExtension();
            $request->file('file_kk')->move($destinationPath, $kkName);
        }

        if ($request->hasFile('file_foto')) {
            $fotoName = time() . '_foto.' . $request->file('file_foto')->getClientOriginalExtension();
            $request->file('file_foto')->move($destinationPath, $fotoName);
        }

        if ($request->hasFile('file_imunisasi')) {
            $imunisasiName = time() . '_imunisasi.' . $request->file('file_imunisasi')->getClientOriginalExtension();
            $request->file('file_imunisasi')->move($destinationPath, $imunisasiName);
        }

        $berkas = Siswa::where('user_id', auth()->id())->first();

        

        // Update atau buat data siswa
        Siswa::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'file_akta_kelahiran' => $aktaKelahiranName ? 'berkas/' . $aktaKelahiranName : $berkas->file_akta_kelahiran,
                'file_kk'             => $kkName ? 'berkas/' . $kkName : $berkas->file_kk,
                'file_foto'           => $fotoName ? 'berkas/' . $fotoName : $berkas->file_foto,
                'file_imunisasi'      => $imunisasiName ? 'berkas/' . $imunisasiName : $berkas->file_imunisasi,
            ]
        );

        return redirect()->route('informasi');
    }

    public function pendaftaran_admin()
    {
        
        $siswa = Siswa::where('user_id', auth()->id())->first();
        $users = User::all();
        return view('pendaftaran.dashboard-admin', compact('siswa','users'));
    }

    public function daftar_admin(Request $request)
    {    
        
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'required|digits:16|numeric',
            'kk' => 'required|digits:16|numeric',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'akte' => 'nullable|string|max:50',
            'tinggi' => 'required|numeric|min:30|max:300',
            'berat' => 'required|numeric|min:1|max:300',
            'agama' => 'required|string|max:50',
            'kewarganegaraan' => 'required|string|max:50',
            'jumlah_saudara' => 'nullable|integer|min:0|max:20',
            'berkebutuhan_khusus' => 'required|in:Y,T',
            'jarak' => 'nullable|string|max:50',
            'alamat' => 'required|string|max:500',
            'waktu' => 'nullable|string|max:50',
        ], [
            // Pesan error dalam Bahasa Indonesia
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-Laki atau Perempuan.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            'kk.required' => 'No KK wajib diisi.',
            'kk.digits' => 'No KK harus terdiri dari 16 angka.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tinggi.required' => 'Tinggi badan wajib diisi.',
            'tinggi.numeric' => 'Tinggi badan harus berupa angka.',
            'berat.required' => 'Berat badan wajib diisi.',
            'agama.required' => 'Agama wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
        ]);
    
        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $request->validate([
            // Validasi untuk Ayah
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'required|digits:16|numeric',
            'pendidikan_ayah' => 'required|string|max:100',
            'pekerjaan_ayah' => 'required|string|max:100',
            'email_ayah' => 'nullable|email|max:255',
            'tempat_lahir_ayah' => 'required|string|max:100',
            'tanggal_lahir_ayah' => 'required|date|before:today',
            'no_telp_ayah' => 'required|digits_between:10,15|numeric',
            'penghasilan_ayah' => 'required|in:1,2,3,4,5',
    
            // Validasi untuk Ibu
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'required|digits:16|numeric',
            'pendidikan_ibu' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:100',
            'email_ibu' => 'nullable|email|max:255',
            'tempat_lahir_ibu' => 'required|string|max:100',
            'tanggal_lahir_ibu' => 'required|date|before:today',
            'no_telp_ibu' => 'required|digits_between:10,15|numeric',
            'penghasilan_ibu' => 'required|in:1,2,3,4,5',
        ], [
            // Pesan error custom
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus tepat 16 angka.',
            'digits_between' => ':attribute harus antara 10 hingga 15 angka.',
            'email' => ':attribute harus berupa email yang valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before' => ':attribute harus sebelum hari ini.',
            'in' => ':attribute yang dipilih tidak valid.',
        ]);

        // Validasi file
        $request->validate([
            'file_akta_kelahiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_foto' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'file_imunisasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'file_akta_kelahiran.required' => 'File Akta Kelahiran wajib diunggah.',
            'file_akta_kelahiran.mimes' => 'File Akta Kelahiran harus berupa PDF, JPG,jpeg, atau PNG.',
            'file_akta_kelahiran.max' => 'Ukuran File Akta Kelahiran tidak boleh lebih dari 2MB.',
            'file_kk.required' => 'File KK wajib diunggah.',
            'file_kk.mimes' => 'File KK harus berupa PDF, JPG, atau PNG.',
            'file_kk.max' => 'Ukuran File KK tidak boleh lebih dari 2MB.',
            'file_foto.required' => 'File Foto wajib diunggah.',
            'file_foto.mimes' => 'File Foto harus berupa JPG atau PNG.',
            'file_foto.max' => 'Ukuran File Foto tidak boleh lebih dari 1MB.',
            'file_imunisasi.mimes' => 'File Imunisasi harus berupa PDF, JPG, atau PNG.',
            'file_imunisasi.max' => 'Ukuran File Imunisasi tidak boleh lebih dari 2MB.',
        ]);


        
        $destinationPath = public_path('berkas');
        
        $aktaKelahiranName = $kkName = $fotoName = $imunisasiName = null;

        if ($request->hasFile('file_akta_kelahiran')) {
            $aktaKelahiranName = time() . '_akta.' . $request->file('file_akta_kelahiran')->getClientOriginalExtension();
            $request->file('file_akta_kelahiran')->move($destinationPath, $aktaKelahiranName);
        }

        if ($request->hasFile('file_kk')) {
            $kkName = time() . '_kk.' . $request->file('file_kk')->getClientOriginalExtension();
            $request->file('file_kk')->move($destinationPath, $kkName);
        }

        if ($request->hasFile('file_foto')) {
            $fotoName = time() . '_foto.' . $request->file('file_foto')->getClientOriginalExtension();
            $request->file('file_foto')->move($destinationPath, $fotoName);
        }

        if ($request->hasFile('file_imunisasi')) {
            $imunisasiName = time() . '_imunisasi.' . $request->file('file_imunisasi')->getClientOriginalExtension();
            $request->file('file_imunisasi')->move($destinationPath, $imunisasiName);
        }

        $user_id = (int) $request->user;
        // dd($user_id);
        Siswa::updateOrCreate(
            ['user_id' => $user_id], 
            [ 
                'user_id' => $user_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nik' => $request->nik,
                'kk' => $request->kk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'akte' => $request->akte,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'agama' => $request->agama,
                'kewarganegaraan' => $request->kewarganegaraan,
                'jumlah_saudara' => $request->jumlah_saudara,
                'berkebutuhan_khusus' => $request->berkebutuhan_khusus,
                'jarak' => $request->jarak,
                'alamat' => $request->alamat,
                'waktu' => $request->waktu,
                'nama_ayah' => $request->nama_ayah,
                'nik_ayah' => $request->nik_ayah,
                'tempat_lahir_ayah' => $request->tempat_lahir_ayah,
                'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'email_ayah' => $request->email_ayah,
                'no_telp_ayah' => $request->no_telp_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'nik_ibu' => $request->nik_ibu,
                'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
                'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'email_ibu' => $request->email_ibu,
                'no_telp_ibu' => $request->no_telp_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
                'file_akta_kelahiran' =>  'berkas/' . $aktaKelahiranName ,
                'file_kk'             =>  'berkas/' . $kkName ,
                'file_foto'           =>  'berkas/' . $fotoName ,
                'file_imunisasi'      =>  'berkas/' . $imunisasiName ,
            ]
        );

        // Redirect ke route pendaftaran
        return redirect()->route('validation');
    }

    public function pendaftaran_admin_edit($id)
    {
        $siswa = Siswa::where('id', $id)->first();
        $users = User::all();
        return view('pendaftaran.dashboard-admin-edit', compact('siswa','users'));
    }

    public function daftar_admin_edit(Request $request)
    {    

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'required|digits:16|numeric',
            'kk' => 'required|digits:16|numeric',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'akte' => 'nullable|string|max:50',
            'tinggi' => 'required|numeric|min:30|max:300',
            'berat' => 'required|numeric|min:1|max:300',
            'agama' => 'required|string|max:50',
            'kewarganegaraan' => 'required|string|max:50',
            'jumlah_saudara' => 'nullable|integer|min:0|max:20',
            'berkebutuhan_khusus' => 'required|in:Y,T',
            'jarak' => 'nullable|string|max:50',
            'alamat' => 'required|string|max:500',
            'waktu' => 'nullable|string|max:50',
        ], [
            // Pesan error dalam Bahasa Indonesia
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-Laki atau Perempuan.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            'kk.required' => 'No KK wajib diisi.',
            'kk.digits' => 'No KK harus terdiri dari 16 angka.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tinggi.required' => 'Tinggi badan wajib diisi.',
            'tinggi.numeric' => 'Tinggi badan harus berupa angka.',
            'berat.required' => 'Berat badan wajib diisi.',
            'agama.required' => 'Agama wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
        ]);
    
        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $request->validate([
            // Validasi untuk Ayah
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'required|digits:16|numeric',
            'pendidikan_ayah' => 'required|string|max:100',
            'pekerjaan_ayah' => 'required|string|max:100',
            'email_ayah' => 'nullable|email|max:255',
            'tempat_lahir_ayah' => 'required|string|max:100',
            'tanggal_lahir_ayah' => 'required|date|before:today',
            'no_telp_ayah' => 'required|digits_between:10,15|numeric',
            'penghasilan_ayah' => 'required|in:1,2,3,4,5',
    
            // Validasi untuk Ibu
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'required|digits:16|numeric',
            'pendidikan_ibu' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:100',
            'email_ibu' => 'nullable|email|max:255',
            'tempat_lahir_ibu' => 'required|string|max:100',
            'tanggal_lahir_ibu' => 'required|date|before:today',
            'no_telp_ibu' => 'required|digits_between:10,15|numeric',
            'penghasilan_ibu' => 'required|in:1,2,3,4,5',
        ], [
            // Pesan error custom
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus tepat 16 angka.',
            'digits_between' => ':attribute harus antara 10 hingga 15 angka.',
            'email' => ':attribute harus berupa email yang valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before' => ':attribute harus sebelum hari ini.',
            'in' => ':attribute yang dipilih tidak valid.',
        ]);

        // Validasi file
        $request->validate([
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            'file_imunisasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'file_akta_kelahiran.mimes' => 'File Akta Kelahiran harus berupa PDF, JPG,jpeg, atau PNG.',
            'file_akta_kelahiran.max' => 'Ukuran File Akta Kelahiran tidak boleh lebih dari 2MB.',
            'file_kk.mimes' => 'File KK harus berupa PDF, JPG, atau PNG.',
            'file_kk.max' => 'Ukuran File KK tidak boleh lebih dari 2MB.',
            'file_foto.mimes' => 'File Foto harus berupa JPG atau PNG.',
            'file_foto.max' => 'Ukuran File Foto tidak boleh lebih dari 1MB.',
            'file_imunisasi.mimes' => 'File Imunisasi harus berupa PDF, JPG, atau PNG.',
            'file_imunisasi.max' => 'Ukuran File Imunisasi tidak boleh lebih dari 2MB.',
        ]);

        $destinationPath = public_path('berkas');
        
        $aktaKelahiranName = $kkName = $fotoName = $imunisasiName = null;

        if ($request->hasFile('file_akta_kelahiran')) {
            $aktaKelahiranName = time() . '_akta.' . $request->file('file_akta_kelahiran')->getClientOriginalExtension();
            $request->file('file_akta_kelahiran')->move($destinationPath, $aktaKelahiranName);
        }

        if ($request->hasFile('file_kk')) {
            $kkName = time() . '_kk.' . $request->file('file_kk')->getClientOriginalExtension();
            $request->file('file_kk')->move($destinationPath, $kkName);
        }

        if ($request->hasFile('file_foto')) {
            $fotoName = time() . '_foto.' . $request->file('file_foto')->getClientOriginalExtension();
            $request->file('file_foto')->move($destinationPath, $fotoName);
        }

        if ($request->hasFile('file_imunisasi')) {
            $imunisasiName = time() . '_imunisasi.' . $request->file('file_imunisasi')->getClientOriginalExtension();
            $request->file('file_imunisasi')->move($destinationPath, $imunisasiName);
        }

        $berkas = Siswa::where('id', $request->id)->first();
        $user_id = (int) $request->user;
        // dd($request->id);
        Siswa::updateOrCreate(
            ['id' => $request->id], 
            [ 
                'user_id' => $user_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nik' => $request->nik,
                'kk' => $request->kk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'akte' => $request->akte,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'agama' => $request->agama,
                'kewarganegaraan' => $request->kewarganegaraan,
                'jumlah_saudara' => $request->jumlah_saudara,
                'berkebutuhan_khusus' => $request->berkebutuhan_khusus,
                'jarak' => $request->jarak,
                'alamat' => $request->alamat,
                'waktu' => $request->waktu,
                'nama_ayah' => $request->nama_ayah,
                'nik_ayah' => $request->nik_ayah,
                'tempat_lahir_ayah' => $request->tempat_lahir_ayah,
                'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'email_ayah' => $request->email_ayah,
                'no_telp_ayah' => $request->no_telp_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'nik_ibu' => $request->nik_ibu,
                'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
                'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'email_ibu' => $request->email_ibu,
                'no_telp_ibu' => $request->no_telp_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
                'file_akta_kelahiran' => $aktaKelahiranName ? 'berkas/' . $aktaKelahiranName : $berkas->file_akta_kelahiran,
                'file_kk'             => $kkName ? 'berkas/' . $kkName : $berkas->file_kk,
                'file_foto'           => $fotoName ? 'berkas/' . $fotoName : $berkas->file_foto,
                'file_imunisasi'      => $imunisasiName ? 'berkas/' . $imunisasiName : $berkas->file_imunisasi,
            ]
        );

        // Redirect ke route pendaftaran
        return redirect()->route('validation');
    }

}
