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
        // get 20 soal angka random unique

        $soalAngka = Soal::where('kategori', 'Numeric')
            ->inRandomOrder()
            ->limit(20)
            ->get();
        //get 20 soal verbal random
        $soalVerbal = Soal::where('kategori', 'Verbal')
            ->inRandomOrder()
            ->limit(20)
            ->get();
        //get 20 soal Logika random
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
        // $ujianSoalListId = [];
        $soalIds = UjianSoalList::pluck('soal_id');

        // dd($soalIds);

        // foreach ($ujianSoalList as $soal) {
        //     array_push($ujianSoalListId, $soal->soal_id);
        // }

        $soal = Soal::whereIn('id', $soalIds)
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

    //jawab soal
    public function jawabSoal(Request $request)
    {
        $validatedData = request()->validate([
            'soal_id' => 'required',
            'jawaban' => 'required',
        ]);

        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id', $ujian->id)
            ->where('soal_id', $validatedData['soal_id'])
            ->first();

        $soal = Soal::where('id', $validatedData['soal_id'])->first();

        //cek jawaban
        if ($soal->kunci == $validatedData['jawaban']) {
            $ujianSoalList->kebenaran = true;
            $ujianSoalList->update([
                'kebenaran' => true,
            ]);
        } else {
            $ujianSoalList->kebenaran = false;
            $ujianSoalList->update([
                'kebenaran' => false,
            ]);
        }

        return response()->json([
            'message' => 'Berhasil simpan jawaban',
            'jawaban' => $ujianSoalList->kebenaran,
        ]);
    }
}
