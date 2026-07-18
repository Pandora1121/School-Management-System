<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        return view('classes.index');
    }

    public function data()
    {
        $classes = SchoolClass::with('major')->orderBy('id', 'desc')->get();

        $data = $classes->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'major_name' => $item->major->name ?? '-',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $majors = Major::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('classes.add', compact('majors', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:tbl_classes,code'],
            'name' => ['required', 'string', 'max:255'],
            'id_major' => ['required', 'exists:tbl_majors,id'],
            'id_wali_kelas' => ['nullable', 'exists:tbl_teachers,id'],
            'description' => ['nullable', 'string'],
        ]);

        SchoolClass::create([
            'creation_time' => now(),
            'create_id' => auth()->id(),
            'archived' => 0,
            'id_user' => auth()->id(),
            'code' => $validated['code'],
            'name' => $validated['name'],
            'id_major' => $validated['id_major'],
            'id_wali_kelas' => $validated['id_wali_kelas'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Kelas berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        $majors = Major::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('classes.edit', compact('class', 'majors', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:tbl_classes,code,'.$id],
            'name' => ['required', 'string', 'max:255'],
            'id_major' => ['required', 'exists:tbl_majors,id'],
            'id_wali_kelas' => ['nullable', 'exists:tbl_teachers,id'],
            'description' => ['nullable', 'string'],
        ]);

        $class->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'code' => $validated['code'],
            'name' => $validated['name'],
            'id_major' => $validated['id_major'],
            'id_wali_kelas' => $validated['id_wali_kelas'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Kelas berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $class = SchoolClass::findOrFail($id);
        $class->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}