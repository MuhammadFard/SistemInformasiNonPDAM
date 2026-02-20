<?php

namespace App\Http\Controllers\Admin;

use App\Models\KwhCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KwhCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = KwhCategory::all();
        return view('admin.kwh-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kwh-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'daya' => 'required|string|unique:kwh_categories,daya',
            'tarif_bulanan' => 'required|numeric|min:0',
        ]);

        KwhCategory::create($request->only('daya','tarif_bulanan'));

        return redirect()->route('admin.kwh-categories.index')->with('success','Kategori Daya berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KwhCategory $kwhCategory)
    {
        return view('admin.kwh-categories.edit', compact('kwhCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KwhCategory $kwhCategory)
    {
        $request->validate([
            'daya' => 'required|string|unique:kwh_categories,daya,' . $kwhCategory->kwh_category_id . ',kwh_category_id',
            'tarif_bulanan' => 'required|numeric|min:0',
        ]);

        $kwhCategory->update($request->only('daya','tarif_bulanan'));

        return redirect()->route('admin.kwh-categories.index')->with('success','Kategori Daya berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KwhCategory $kwhCategory)
    {
        $kwhCategory->delete();
        return redirect()->route('admin.kwh-categories.index')->with('success','Kategori Daya berhasil dihapus.');
    }
}
