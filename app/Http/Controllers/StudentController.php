<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Major;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }


 public function data()
{
    $students = Student::with(['schoolClass', 'major'])->orderBy('id', 'desc')->get();

    $data = $students->map(function ($item) {
        return [
            'id' => $item->id,
            'nis' => $item->nis,
            'name' => $item->name,
            'class_name' => $item->schoolClass->name ?? '-',
            'major_name' => $item->major->name ?? '-',
            'gender' => $item->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            'birth_date' => $item->birth_date ? \Carbon\Carbon::parse($item->birth_date)->translatedFormat('d M Y') : '-',
            'status' => $item->status == 1 ? 'Aktif' : 'Non-Aktif',
        ];
    });

    return response()->json(['data' => $data]);
}

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();
        return view('students.add', compact('classes', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:tbl_students,nis'],
            'name' => ['required', 'string', 'max:255'],
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'id_major' => ['required', 'exists:tbl_majors,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = null;
        if ($request->hasFile('img_url')) {
            $imgName = now()->format('YmdHis').'-'.$request->file('img_url')->getClientOriginalName();
            $request->file('img_url')->move(public_path('uploads/students'), $imgName);
        }

        Student::create([
            'creation_time' => now(),
            'create_id' => auth()->id(),
            'archived' => 0,
            'id_user' => auth()->id(),
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'img_url' => $imgName,
            'id_class' => $validated['id_class'],
            'id_major' => $validated['id_major'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => 1,
        ]);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();
        return view('students.edit', compact('student', 'classes', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:tbl_students,nis,'.$id],
            'name' => ['required', 'string', 'max:255'],
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'id_major' => ['required', 'exists:tbl_majors,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = $student->img_url;
        if ($request->hasFile('img_url')) {
            $imgName = now()->format('YmdHis').'-'.$request->file('img_url')->getClientOriginalName();
            $request->file('img_url')->move(public_path('uploads/students'), $imgName);
        }

        $student->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'img_url' => $imgName,
            'id_class' => $validated['id_class'],
            'id_major' => $validated['id_major'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
            if (auth()->user()->role != 1) {
        abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
    }
        $student = Student::findOrFail($id);
        $student->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}