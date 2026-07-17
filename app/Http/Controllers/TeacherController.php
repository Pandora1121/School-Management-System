<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Major;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teachers.index');
    }

  public function data()
{
    $teachers = Teacher::with('major')->orderBy('id', 'desc')->get();

    $data = $teachers->map(function ($item) {
        return [
            'id' => $item->id,
            'nip' => $item->nip,
            'name' => $item->name,
            'major_name' => $item->major->name ?? '-',
            'gender' => $item->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            'birth_date' => $item->birth_date ? \Carbon\Carbon::parse($item->birth_date)->translatedFormat('d M Y') : '-',
            'phone' => $item->phone ?? '-',
            'status' => $item->status == 1 ? 'Aktif' : 'Non-Aktif',
        ];
    });

    return response()->json(['data' => $data]);
}

    public function create()
    {
        $majors = Major::orderBy('name')->get();
        return view('teachers.add', compact('majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', 'unique:tbl_teachers,nip'],
            'name' => ['required', 'string', 'max:255'],
            'id_major' => ['nullable', 'exists:tbl_majors,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = null;
        if ($request->hasFile('img_url')) {
            $imgName = now()->format('YmdHis').'-'.$request->file('img_url')->getClientOriginalName();
            $request->file('img_url')->move(public_path('uploads/teachers'), $imgName);
        }

        Teacher::create([
            'creation_time' => now(),
            'create_id' => auth()->id(),
            'archived' => 0,
            'id_user' => auth()->id(),
            'nip' => $validated['nip'],
            'name' => $validated['name'],
            'img_url' => $imgName,
            'id_major' => $validated['id_major'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => 1,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        $majors = Major::orderBy('name')->get();
        return view('teachers.edit', compact('teacher', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', 'unique:tbl_teachers,nip,'.$id],
            'name' => ['required', 'string', 'max:255'],
            'id_major' => ['nullable', 'exists:tbl_majors,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = $teacher->img_url;
        if ($request->hasFile('img_url')) {
            $imgName = now()->format('YmdHis').'-'.$request->file('img_url')->getClientOriginalName();
            $request->file('img_url')->move(public_path('uploads/teachers'), $imgName);
        }

        $teacher->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'nip' => $validated['nip'],
            'name' => $validated['name'],
            'img_url' => $imgName,
            'id_major' => $validated['id_major'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $teacher = Teacher::findOrFail($id);
        $teacher->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}