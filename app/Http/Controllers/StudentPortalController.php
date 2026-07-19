<?php

namespace App\Http\Controllers;

use App\Models\ClassRoutine;
use App\Models\Attendance;
use App\Models\Exam;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    private function currentStudent()
    {
        return auth()->user()->student;
    }

    public function schedule()
    {
        $student = $this->currentStudent();

        if (!$student) {
            abort(403, 'Akun Anda belum terhubung ke data siswa. Hubungi Admin.');
        }

        $routines = ClassRoutine::with(['subject', 'teacher'])
            ->where('id_class', $student->id_class)
            ->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get();

        return view('student-portal.schedule', compact('routines', 'student'));
    }

    public function attendance()
    {
        $student = $this->currentStudent();

        if (!$student) {
            abort(403, 'Akun Anda belum terhubung ke data siswa. Hubungi Admin.');
        }

        $attendances = Attendance::where('id_student', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        $summary = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        return view('student-portal.attendance', compact('attendances', 'student', 'summary'));
    }

    public function scores()
    {
        $student = $this->currentStudent();

        if (!$student) {
            abort(403, 'Akun Anda belum terhubung ke data siswa. Hubungi Admin.');
        }

        $exams = Exam::with('subject')
            ->where('id_student', $student->id)
            ->orderBy('id', 'desc')
            ->get();

        $grouped = $exams->groupBy(function ($item) {
            return $item->subject->name ?? 'Lainnya';
        });

        return view('student-portal.scores', compact('grouped', 'student'));
    }
}