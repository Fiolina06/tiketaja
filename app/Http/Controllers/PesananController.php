<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\User;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pesanan = Pesanan::with('user','kategori')->get();
        return $pesanan;
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
        $kode = mt_rand(1000,9999); // generate random kode 
        $getId = Auth::id();
        $table = pesanan::create([
            "id_user" => $getId,
            "id_kategori" => $request->id_kategori,
            "tanggal" => $request->tanggal,
            "kode" => $kode,
            "status" => 0
        ]);
        
        $kategori = kategori::where('id', $request->id_kategori)->first();
        if($kategori){
            $kategori->jumlah = ($kategori->jumlah - 1);
            $kategori->save();
        }

        return response()->json([
            'success' => 201,
            'message' => "Pesanan berhasil disimpan", 
            'data' => $table
        ],
          201  
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pesanan = tiket::where('id', $id)->first();
        if ($pesanan){
            return response()->json([
                'status' => 200,
                'data' => $pesanan
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'data' => 'Data tiket dengan id ' . $id . ' tidak ditemukan '
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pesanan = pesanan::where('id', $id)->first();
        if($pesanan){
            $pesanan->id_kategori = $request->id_kategori ? $request->id_kategori : $pesanan->id_kategori;
            $pesanan->save();
            return response()->json([
                'status' => 200,
                'message' => "Data tiket berhasil diubah", 
                'data' => $tiket
            ], 200);
            
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data dengan id ' . $id . ' tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesanan = pesanan::where('id', $id)->first();
        if($pesanan){
            $pesanan->delete();
            return response()->json([
                'status' => 200,
                'message' => "Data tiket berhasil dihapus", 
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data dengan Id ' . $id . ' tidak ditemukan' 
            ], 404);
        }
    
    }
}
