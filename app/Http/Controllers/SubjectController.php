<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return view('subjects.index');
    }

    public function data()
    {
        $subjects = Subject::with(['major', 'teacher'])->orderBy('id', 'desc')->get();

        $data = $subjects->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'major_name' => $item->major->name ?? '-',
                'teacher_name' => $item->teacher->name ?? '-',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $majors = Major::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('subjects.add', compact('majors', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:tbl_subjects,code'],
            'name' => ['required', 'string', 'max:255'],
            'id_major' => ['nullable', 'exists:tbl_majors,id'],
            'id_teacher' => ['nullable', 'exists:tbl_teachers,id'],
            'description' => ['nullable', 'string'],
        ]);

        Subject::create([
            'creation_time' => now(),
            'create_id' => auth()->id(),
            'archived' => 0,
            'id_user' => auth()->id(),
            'code' => $validated['code'],
            'name' => $validated['name'],
            'id_major' => $validated['id_major'] ?? null,
            'id_teacher' => $validated['id_teacher'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $majors = Major::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('subjects.edit', compact('subject', 'majors', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:tbl_subjects,code,'.$id],
            'name' => ['required', 'string', 'max:255'],
            'id_major' => ['nullable', 'exists:tbl_majors,id'],
            'id_teacher' => ['nullable', 'exists:tbl_teachers,id'],
            'description' => ['nullable', 'string'],
        ]);

        $subject->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'code' => $validated['code'],
            'name' => $validated['name'],
            'id_major' => $validated['id_major'] ?? null,
            'id_teacher' => $validated['id_teacher'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $subject = Subject::findOrFail($id);
        $subject->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}