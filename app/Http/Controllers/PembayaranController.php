<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pesanan;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembayaran = pembayaran::with('pesanan')->get();
        return $pembayaran;
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
        $pesanan = pesanan::where('kode', $request->kode)->first();
        if($pesanan){
            $date = date('Y-m-d'); 
            $table = pembayaran::create([
                "id_pesanan" => $pesanan->id,
                "tanggal" => $date,
                "metode_pembayaran" => $request->metode_pembayaran
            ]);
            $pesanan->status = 1;
            $pesanan->save();
            return response()->json([
                'success' => 201,
                'message' => "Pembayaran berhasil", 
                'data' => $table
            ],
              201  
                );
        } else {
            return response()->json([
                'success' => 401,
                'message' => "Id Pesanan tidak ditemukan", 
            ],
              401  
                );
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pembayaran = pembayaran::where('id', $id)->first();
        if ($pembayaran){
            return response()->json([
                'status' => 200,
                'data' => $pembayaran
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
