<?php

namespace App\Http\Controllers;

use App\Models\ParentStudent;
use App\Models\ClassRoutine;
use App\Models\Attendance;
use App\Models\Exam;
use Illuminate\Http\Request;

class ParentPortalController extends Controller
{
    private function children()
    {
        return ParentStudent::with('student.schoolClass', 'student.major')
            ->where('id_user', auth()->id())
            ->get()
            ->pluck('student');
    }

    public function index()
    {
        $children = $this->children();
        return view('parent-portal.index', compact('children'));
    }

    private function authorizeChild($studentId)
    {
        $isMyChild = ParentStudent::where('id_user', auth()->id())
            ->where('id_student', $studentId)
            ->exists();

        if (!$isMyChild) {
            abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
        }
    }

    public function schedule($studentId)
    {
        $this->authorizeChild($studentId);
        $children = $this->children();
        $student = $children->firstWhere('id', $studentId);

        $routines = ClassRoutine::with(['subject', 'teacher'])
            ->where('id_class', $student->id_class)
            ->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get();

        return view('parent-portal.schedule', compact('routines', 'student', 'children'));
    }

    public function attendance($studentId)
    {
        $this->authorizeChild($studentId);
        $children = $this->children();
        $student = $children->firstWhere('id', $studentId);

        $attendances = Attendance::where('id_student', $studentId)
            ->orderBy('date', 'desc')
            ->get();

        $summary = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        return view('parent-portal.attendance', compact('attendances', 'student', 'summary', 'children'));
    }

    public function scores($studentId)
    {
        $this->authorizeChild($studentId);
        $children = $this->children();
        $student = $children->firstWhere('id', $studentId);

        $exams = Exam::with('subject')
            ->where('id_student', $studentId)
            ->orderBy('id', 'desc')
            ->get();

        $grouped = $exams->groupBy(function ($item) {
            return $item->subject->name ?? 'Lainnya';
        });

        return view('parent-portal.scores', compact('grouped', 'student', 'children'));
    }
}