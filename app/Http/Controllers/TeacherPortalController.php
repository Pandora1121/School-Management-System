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

    public function classes()
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            abort(403, 'Akun Anda belum terhubung ke data guru. Hubungi Admin.');
        }

        $classes = SchoolClass::where('id_wali_kelas', $teacher->id)->orderBy('name')->get();

        return view('teacher-portal.classes', compact('classes', 'teacher'));
    }

    public function classStudents($id)
    {
        $teacher = $this->currentTeacher();
        $class = SchoolClass::findOrFail($id);

        if (!$teacher || $class->id_wali_kelas != $teacher->id) {
            abort(403, 'Anda bukan wali kelas dari kelas ini.');
        }

        $students = Student::where('id_class', $id)->orderBy('name')->get();

        return view('teacher-portal.class-students', compact('students', 'class'));
    }

    public function attendanceForm(Request $request, $id)
    {
        $teacher = $this->currentTeacher();
        $class = SchoolClass::findOrFail($id);

        if (!$teacher || $class->id_wali_kelas != $teacher->id) {
            abort(403, 'Anda bukan wali kelas dari kelas ini.');
        }

        $selectedDate = $request->date ?? now()->format('Y-m-d');
        $students = Student::where('id_class', $id)->orderBy('name')->get();

        return view('teacher-portal.attendance-form', compact('class', 'students', 'selectedDate'));
    }

    public function attendanceStore(Request $request, $id)
    {
        $teacher = $this->currentTeacher();
        $class = SchoolClass::findOrFail($id);

        if (!$teacher || $class->id_wali_kelas != $teacher->id) {
            abort(403, 'Anda bukan wali kelas dari kelas ini.');
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