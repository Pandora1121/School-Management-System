<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('attendances.index', compact('classes'));
    }

    public function data(Request $request)
    {
        $query = Attendance::with(['student', 'schoolClass']);

        if ($request->filled('id_class')) {
            $query->where('id_class', $request->id_class);
        }
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $data = $attendances->map(function ($item) {
            return [
                'id' => $item->id,
                'student_name' => $item->student->name ?? '-',
                'class_name' => $item->schoolClass->name ?? '-',
                'date' => \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y'),
                'status' => $item->status,
                'note' => $item->note ?? '-',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $students = collect();
        $selectedClass = null;
        $selectedDate = $request->date ?? now()->format('Y-m-d');

        if ($request->filled('id_class')) {
            $selectedClass = $request->id_class;
            $students = Student::where('id_class', $request->id_class)->orderBy('name')->get();
        }

        return view('attendances.add', compact('classes', 'students', 'selectedClass', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:Hadir,Sakit,Izin,Alpa'],
        ]);

        foreach ($validated['status'] as $studentId => $status) {
            $existing = Attendance::where('id_student', $studentId)
                ->where('date', $validated['date'])
                ->first();

            if ($existing) {
                $existing->update([
                    'update_time' => now(),
                    'update_id' => auth()->id(),
                    'status' => $status,
                ]);
            } else {
                Attendance::create([
                    'creation_time' => now(),
                    'create_id' => auth()->id(),
                    'archived' => 0,
                    'id_user' => auth()->id(),
                    'id_student' => $studentId,
                    'id_class' => $validated['id_class'],
                    'date' => $validated['date'],
                    'status' => $status,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan.']);
    }

    public function destroy($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}