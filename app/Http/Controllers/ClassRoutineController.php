<?php

namespace App\Http\Controllers;

use App\Models\ClassRoutine;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    public function index()
    {
        return view('class-routines.index');
    }

    public function data()
    {
        $routines = ClassRoutine::with(['schoolClass', 'subject', 'teacher'])
            ->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get();

        $data = $routines->map(function ($item) {
            return [
                'id' => $item->id,
                'class_name' => $item->schoolClass->name ?? '-',
                'subject_name' => $item->subject->name ?? '-',
                'teacher_name' => $item->teacher->name ?? '-',
                'day' => $item->day,
                'time' => substr($item->start_time, 0, 5).' - '.substr($item->end_time, 0, 5),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('class-routines.add', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'id_subject' => ['required', 'exists:tbl_subjects,id'],
            'id_teacher' => ['nullable', 'exists:tbl_teachers,id'],
            'day' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        ClassRoutine::create([
            'creation_time' => now(),
            'create_id' => auth()->id(),
            'archived' => 0,
            'id_user' => auth()->id(),
            'id_class' => $validated['id_class'],
            'id_subject' => $validated['id_subject'],
            'id_teacher' => $validated['id_teacher'] ?? null,
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $routine = ClassRoutine::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('class-routines.edit', compact('routine', 'classes', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $routine = ClassRoutine::findOrFail($id);

        $validated = $request->validate([
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'id_subject' => ['required', 'exists:tbl_subjects,id'],
            'id_teacher' => ['nullable', 'exists:tbl_teachers,id'],
            'day' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $routine->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'id_class' => $validated['id_class'],
            'id_subject' => $validated['id_subject'],
            'id_teacher' => $validated['id_teacher'] ?? null,
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $routine = ClassRoutine::findOrFail($id);
        $routine->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}