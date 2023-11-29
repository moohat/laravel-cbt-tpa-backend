<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SoalResource;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianSoalList;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //create ujian
    public function createUjian(Request $request)
    {
        // get 20 soal angka random
        $soalAngka = Soal::where('kategori', 'Numeric')
            ->inRandomOrder()
            ->limit(20)
            ->get();
        //get 20 soang verbal random
        $soalVerbal = Soal::where('kategori', 'Verbal')
            ->inRandomOrder()
            ->limit(20)
            ->get();
        //get 20 soang Logika random
        $soalLogika = Soal::where('kategori', 'Logika')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        //cerate ujian
        $ujian = Ujian::create([
            'user_id' => $request->user()->id,
        ]);

        // Soal Angka
        foreach ($soalAngka as $soal) {
            # code...
            UjianSoalList::create(['ujian_id' => $ujian->id, 'soal_id' => $soal->id]);
        }
        //Soal Verbal
        foreach ($soalVerbal as $soal) {
            # code...
            UjianSoalList::create(['ujian_id' => $ujian->id, 'soal_id' => $soal->id]);
        }
        //Soal Logika
        foreach ($soalLogika as $soal) {
            # code...
            UjianSoalList::create(['ujian_id' => $ujian->id, 'soal_id' => $soal->id]);
        }

        return response()->json([
            'message' => 'Berhasil Membuat Ujian',
            'data' => $ujian,
        ]);
        // do something with $soalAngka
    }

    //get list soal by kategori
    public function getListSoalByKategori(Request $request)
    {
        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id', $ujian->id)->get();
        $ujianSoalListId = [];
        // $ujianSoalListId = UjianSoalList::pluck('soal_id', $ujianSoalList);

        foreach ($ujianSoalList as $soal) {
            array_push($ujianSoalListId, $soal->soal_id);
        }

        $soal = Soal::whereIn('id', $ujianSoalListId)
            ->where('kategori', $request->kategori)
            ->get();

        if ($soal->count() < 1) {
            return response()->json([
                'message' => 'Data Soal Tidak Ditemukan, cek kategori anda',
            ]);
        } else {
            return response()->json([
                'message' => 'Berhasil Mendapatkan soal',
                'jumlah soal' => $soal->count(),
                'data' => SoalResource::collection($soal),
            ]);
        }
    }
}
