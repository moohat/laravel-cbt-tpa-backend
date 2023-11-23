<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoalRequest;
use App\Http\Requests\UpdateSoalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Soal;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        $soals = DB::table('soals')
            ->when($request->input('pertanyaan'), function ($query, $pertanyaan) {
                return $query->where('pertanyaan', 'like', '%' . $pertanyaan . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view('pages.soals.index', compact('soals'));
    }

    public function create()
    {
        return view('pages.soals.create');
    }

    public function store(StoreSoalRequest $request)
    {
        $data = $request->all();
        \App\Models\Soal::create($data);
        return redirect()
            ->route('soal.index')
            ->with('success', 'User successfully created');
    }

    public function edit($id)
    {
        $soal = \App\Models\Soal::findOrFail($id);
        return view('pages.soals.edit', compact('soal'));
    }

    public function update(UpdateSoalRequest $request, Soal $soal)
    {
        $data = $request->validated();
        $soal->update($data);
        return redirect()
            ->route('soal.index')
            ->with('success', 'User successfully updated');
    }

    public function destroy(Soal $soal)
    {
        $soal->delete();
        return redirect()->route('soal.index')->with('success', 'User successfully deleted');
    }
}
