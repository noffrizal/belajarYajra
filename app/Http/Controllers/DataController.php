<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;


class DataController extends Controller
{
    public function index()
    {
        $data = Data::get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('cek', function($data)
                {
                    $cek ="<input type='checkbox' class='ceks' id='".$data->id."'>";
                    return $cek;
                })
                ->addColumn('aksi', function($data)
                {
                    $button = "<button class='edit btn btn-primary btn-sm' id = '".$data->id."'>Edit</button> ";
                    $button .= "<button class='hapus btn btn-danger btn-sm' id = '".$data->id."'>Hapus</button>";
                    return $button;

                })
                ->rawColumns(['aksi','cek'])
                ->make(true);
        }

        return view('home');

    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = new Data();
        $data->name = $request->nama;
        $data->telp = $request->telp;
        $data->alamat = $request->alamat;
        $simpan = $data->save();

        if ($simpan) {
            return response()->json(['data' => $data, 'text' => 'data berhasil disimpan'],200);
        }else {
            return response()->json(['data' => $data, 'text' => 'data gagal disimpan']);
        }
    }

    public function edits(Request $request)
    {
        $id = $request->id;
        $data = Data::find($id);
        return response()->json(['data' => $data]);
    }

    public function updates(Request $request)
    {

        $id = $request->id;
        $datas = [
            'name' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat
        ];
        $data = Data::find($id);
        $simpan = $data->update($datas);

        if ($simpan) {
            return response()->json(['text' => 'data berhasil diupdate'],200);
        }else {
            return response()->json(['text' => 'data gagal diupdate']);
        }
    }

    public function hapus(Request $request)
    {
        if ($request->multi != null) {
            $data = $request->data;
            foreach ($data as $key) {
                $datas = Data::find($key);
                $datas->delete();
            }
            return response()->json(['text' => 'data berhasil dihapus'],200);

        }else{
            $id = $request->id;
            $data = Data::find($id);
            $hapus = $data->delete();

            if ($hapus) {
                return response()->json(['text' => 'data berhasil dihapus'],200);
            }else {
                return response()->json(['text' => 'data gagal dihapus']);
            }
        }


    }
}
