<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Validator;
use PDF;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $maxid = Siswa::max('id')+1;

        $siswa = Siswa::all();
        $jurusan = Jurusan::all();
        $kode = 'PPDB'. '/'. $maxid. '/'. $bulan. '/'. $tahun;
        return view('siswa.index', compact('siswa', 'jurusan', 'kode'));
    }

    public function data()
    {
     

        $siswa = Siswa::orderBy('id', 'desc')->get();

        return datatables()
            ->of($siswa)
            ->addIndexColumn()
            ->addColumn('jurusan_id', function($siswa){
                return !empty($siswa->jurusan->nama) ? $siswa->jurusan->nama : '-';
            })
            ->addColumn('aksi', function($siswa){
                return '
                <div class="btn-group">
                    <button onclick="editData(`' .route('siswa.update', $siswa->id). '`)" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`' .route('siswa.destroy', $siswa->id). '`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    <a href="'.route('siswa.pdf', $siswa->id).'" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-print"></i></a>
                </div>
                ';
                // <a href="'.route('siswa.pdf', $siswa->id).'" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-print"></i></a>
            })
            ->rawColumns(['aksi', 'jurusan_id'])
            ->make(true);
    }

    public function pdf($id)
    {
        $siswa = Siswa::find($id);

        $pdf = PDF::loadview('siswa.pdf', compact('siswa'));
        return $pdf->stream('siswa.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'jurusan_id' => 'required',
            'alamat' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $siswa = Siswa::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jurusan_id' => $request->jurusan_id,
            'alamat' => $request->alamat
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan',
            'data' => $siswa
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $siswa = Siswa::find($id);
        return response()->json($siswa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $siswa = Siswa::find($id);
        return view ('siswa.index', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);
        $siswa->nama = $request->nama;
        $siswa->jurusan_id = $request->jurusan_id;
        $siswa->alamat = $request->alamat;
        $siswa->update();

        return response()->json('Data Berhasil Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        $siswa->delete();

        return redirect('siswa');
    }
}
