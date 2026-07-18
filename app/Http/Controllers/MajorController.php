<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MajorController extends Controller
{
    public function index()
    {
        return view('majors.index');
    }

    public function data()
    {
        $majors = Major::orderBy('id', 'desc')->get();
        return response()->json(['data' => $majors]);
    }

    public function create()
    {
        return view('majors.add');
    }

    private function sanitizeFileName($file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $cleanName = Str::slug($originalName);
        return now()->format('YmdHis').'-'.$cleanName.'.'.$extension;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = null;
        if ($request->hasFile('img_url')) {
            $imgName = $this->sanitizeFileName($request->file('img_url'));
            $request->file('img_url')->move(public_path('uploads/majors'), $imgName);
        }

        Major::create([
            'creation_time' => now(),
            'create_id' => auth()->id(),
            'archived' => 0,
            'id_user' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'img_url' => $imgName,
        ]);

        return response()->json(['success' => true, 'message' => 'Jurusan berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $major = Major::findOrFail($id);
        return view('majors.edit', compact('major'));
    }

    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = $major->img_url;
        if ($request->hasFile('img_url')) {
            $imgName = $this->sanitizeFileName($request->file('img_url'));
            $request->file('img_url')->move(public_path('uploads/majors'), $imgName);
        }

        $major->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'img_url' => $imgName,
        ]);

        return response()->json(['success' => true, 'message' => 'Jurusan berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $major = Major::findOrFail($id);
        $major->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}