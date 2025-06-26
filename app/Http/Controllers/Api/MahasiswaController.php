<?php

namespace App\Http\Controllers\Api;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MahasiswaResource;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::latest()->paginate(5);

        return new MahasiswaResource(true, 'List Data Mahasiswa', $mahasiswas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'nim'       => 'required',
            'prodi'     => 'required',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/mahasiswas', $image->hashName());
        
        $mahasiswa = Mahasiswa::create([
            'nama'      => $request->nama,
            'nim'       => $request->nim,
            'prodi'     => $request->prodi,
            'image'     => $image->hashName(),
        ]);

        return new MahasiswaResource(true, 'Data Mahasiswa Berhasil Ditambahkan!', $mahasiswa);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        return new MahasiswaResource(true, 'Detail Data Mahasiswa !', $mahasiswa);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'nim'       => 'required',
            'prodi'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
   
        $mahasiswa = Mahasiswa::find($id);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/mahasiswas', $image->hashName());

            Storage::delete('public/mahasiswas/' . basename($mahasiswa->image));

            $mahasiswa->update([
                'nama'      => $request->nama,
                'nim'       => $request->nim,
                'prodi'     => $request->prodi,
                'image'     => $image->hashName(),
            ]);
        } else {
            $mahasiswa->update([
                'nama'      => $request->nama,
                'nim'       => $request->nim,
                'prodi'     => $request->prodi,
            ]);
        }

        return new MahasiswaResource(true, 'Data Mahasiswa Berhasil Diubah!', $mahasiswa);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        
        Storage::delete('public/mahasiswas/'.basename($mahasiswa->image));

        $mahasiswa->delete();

        return new MahasiswaResource(true, 'Data Mahasiswa Berhasil Dihapus!', null);
    }
}