<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\User;
use Auth;

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
        $date = date('Y-m-d');
        $kategori = kategori::where('id', $request->id_kategori)->first();
        if($kategori){
            $kategori = kategori::where('id', $request->id_kategori)->first();
            if($kategori->jumlah == 0){
                return response()->json([
                    'success' => 401,
                    'message' => "Stok Habis, Pesanan gagal disimpan", 
                ], 401);
            }
            $total_harga = (($kategori->harga) * ($request->jumlah));
            $kode = mt_rand(1000,9999); // generate random kode 
            $getId = Auth::id();
            $table = pesanan::create([
                "id_user" => $getId,
                "id_kategori" => $request->id_kategori,
                "tanggal" => $date,
                "jumlah" => $request->jumlah,
                "kode" => $kode,
                "total_harga" => $total_harga,
                "status" => 0
            ]);
            
            $kategori = kategori::where('id', $request->id_kategori)->first();
            if($kategori){
                $kategori->jumlah = ($kategori->jumlah - $request->jumlah);
                $kategori->save();
            }

            return response()->json([
                'success' => 201,
                'message' => "Pesanan berhasil disimpan", 
                'data' => $table
            ],
            201  
                );
        } else {
            return response()->json([
                'success' => 401,
                'message' => "Kategori tidak ditemukan", 
            ], 401);
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
        $pesanan = pesanan::where('id', $id)->first();
        if ($pesanan){
            return response()->json([
                'status' => 200,
                'data' => $pesanan
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'data' => 'Data pesanan dengan id ' . $id . ' tidak ditemukan '
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
        $id_user = Auth::id();
        $user = pesanan::where('id_user',$id_user)->first();
        if($user){
            $kategori = kategori::where('id', $request->id_kategori)->first();
                if($kategori){
                    if($kategori->jumlah == 0){
                        return response()->json([
                            'success' => 401,
                            'message' => "Stok Habis, Pesanan gagal disimpan", 
                        ],
                        404  
                            );
                    } else{
                        $pesanan = pesanan::where('id', $id)->first();

                        $jumlah = $pesanan->jumlah;
                        $requestJumlah = $request->jumlah;

                        if($jumlah >= $requestJumlah ){
                            $jmlhPesan = ($requestJumlah) - ($jumlah);
                            $kategori->jumlah = (($kategori->jumlah) - ($jmlhPesan));
                            if($kategori->jumlah < 0){
                                return response()->json([
                                    'success' => 401,
                                    'message' => "Stok tidak mencukupi, Pesanan gagal disimpan", 
                                ],
                                401  
                                    );
                            } else {
                                $kategori->save();
                            }   
                        } 
                        elseif($requestJumlah >= $jumlah){
                            $jmlhPesan = ($jumlah) - ($requestJumlah);
                            $kategori->jumlah = (($kategori->jumlah) + ($jmlhPesan));
                            $kategori->save();
                        }
                        
                        $total_harga = (($kategori->harga) * ($request->jumlah));
                        $pesanan->total_harga = $total_harga;
                        $pesanan->id_kategori = $request->id_kategori;
                        $pesanan->jumlah = $request->jumlah;
                        $pesanan->save();

                        if($pesanan->status == 1){
                            return response()->json([
                                'status' => 401,
                                'message' => "Data tidak bisa diupdate", 
                            ], 401);
                        }


                        return response()->json([
                            'success' => 201,
                            'message' => "Data Pesanan berhasil diupdate", 
                            'data' => $pesanan
                        ],
                          201  
                            );
                    }
                        
                    } else {
                        return response()->json([
                            'status' => 404,
                            'message' => "Data kategori tidak ditemukan", 
                        ], 404);
                    }
        }
        else {
            return response()->json([
                'status' => 401,
                'message' => "Data user tidak valid", 
            ], 401);
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
        $pemesanan = pesanan::where('id', $id)->first();
        $id_kategori = $pemesanan->id_kategori;
        $kategori = kategori::where('id', $id_kategori)->first();
        $pesanan = pesanan::where('id', $id)->first();
        if($pemesanan){
            $kategori->jumlah = (($kategori->jumlah) + ($pemesanan->jumlah));
            $kategori->save();
            $pemesanan->delete();
            return response()->json([
                'status' => 200,
                'message' => "Data pesanan berhasil dihapus", 
            ], 200);
        }
        else {
            return response()->json([
                'status' => 401,
                'message' => "Data pesanan tidak ditemukan", 
            ], 401);
        }
    
    }
}
