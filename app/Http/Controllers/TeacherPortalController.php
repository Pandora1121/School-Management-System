<?php

namespace App\Http\Controllers;

use App\Models\ClassRoutine;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;

class TeacherPortalController extends Controller
{
    private function currentTeacher()
    {
        return auth()->user()->teacher;
    }

    // Cek apakah guru ini boleh akses kelas tertentu:
    // - Wali Kelas: hanya kelas yang dia ampu
    // - Guru biasa: kelas manapun yang pernah dia ajar (ada di jadwal)
    private function canAccessClass($teacher, $classId): bool
    {
        $class = SchoolClass::find($classId);
        if (!$class) return false;

        if ($class->id_wali_kelas == $teacher->id) {
            return true;
        }

        return ClassRoutine::where('id_teacher', $teacher->id)
            ->where('id_class', $classId)
            ->exists();
    }

    public function schedule()
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            abort(403, 'Akun Anda belum terhubung ke data guru. Hubungi Admin.');
        }

        $routines = ClassRoutine::with(['schoolClass', 'subject'])
            ->where('id_teacher', $teacher->id)
            ->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get();

        return view('teacher-portal.schedule', compact('routines', 'teacher'));
    }

    // Dipakai Guru & Wali Kelas: daftar kelas yang bisa mereka akses
    public function classes()
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            abort(403, 'Akun Anda belum terhubung ke data guru. Hubungi Admin.');
        }

        $waliKelasClassIds = SchoolClass::where('id_wali_kelas', $teacher->id)->pluck('id');
        $teachingClassIds = ClassRoutine::where('id_teacher', $teacher->id)->pluck('id_class');

        $allClassIds = $waliKelasClassIds->merge($teachingClassIds)->unique();

        $classes = SchoolClass::whereIn('id', $allClassIds)->orderBy('name')->get();

        return view('teacher-portal.classes', compact('classes', 'teacher'));
    }

    public function classStudents($id)
    {
        $teacher = $this->currentTeacher();
        $class = SchoolClass::findOrFail($id);

        if (!$teacher || !$this->canAccessClass($teacher, $id)) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $students = Student::where('id_class', $id)->orderBy('name')->get();
        $isWaliKelas = $class->id_wali_kelas == $teacher->id;

        return view('teacher-portal.class-students', compact('students', 'class', 'isWaliKelas'));
    }

    public function attendanceForm(Request $request, $id)
    {
        $teacher = $this->currentTeacher();
        $class = SchoolClass::findOrFail($id);

        if (!$teacher || !$this->canAccessClass($teacher, $id)) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $selectedDate = $request->date ?? now()->format('Y-m-d');
        $students = Student::where('id_class', $id)->orderBy('name')->get();

        return view('teacher-portal.attendance-form', compact('class', 'students', 'selectedDate'));
    }

    public function attendanceStore(Request $request, $id)
    {
        $teacher = $this->currentTeacher();
        $class = SchoolClass::findOrFail($id);

        if (!$teacher || !$this->canAccessClass($teacher, $id)) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $validated = $request->validate([
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
                    'id_class' => $id,
                    'date' => $validated['date'],
                    'status' => $status,
                ]);
            }
        }

        return redirect()->route('teacher.classes.students', $id)->with('success', 'Absensi berhasil disimpan.');
    }
}