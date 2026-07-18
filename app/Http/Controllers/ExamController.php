<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\ClassRoutine;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private function currentTeacher()
    {
        return auth()->user()->teacher;
    }

    // Kelas+mapel yang boleh diisi nilai oleh guru ini (dari jadwal mengajarnya)
    private function teachingAssignments($teacher)
    {
        return ClassRoutine::with(['schoolClass', 'subject'])
            ->where('id_teacher', $teacher->id)
            ->get()
            ->unique(function ($item) {
                return $item->id_class.'-'.$item->id_subject;
            });
    }

    public function index()
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            abort(403, 'Akun Anda belum terhubung ke data guru. Hubungi Admin.');
        }

        $exams = Exam::with(['student', 'subject', 'schoolClass'])
            ->where('id_teacher', $teacher->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            abort(403, 'Akun Anda belum terhubung ke data guru. Hubungi Admin.');
        }

        $assignments = $this->teachingAssignments($teacher);

        return view('exams.create', compact('assignments'));
    }

    public function studentsForm(Request $request)
    {
        $teacher = $this->currentTeacher();

        $validated = $request->validate([
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'id_subject' => ['required', 'exists:tbl_subjects,id'],
            'exam_type' => ['required', 'in:Tugas,UTS,UAS,Kuis'],
        ]);

        // Pastikan guru ini memang mengajar kombinasi kelas+mapel ini
        $isValidAssignment = ClassRoutine::where('id_teacher', $teacher->id)
            ->where('id_class', $validated['id_class'])
            ->where('id_subject', $validated['id_subject'])
            ->exists();

        if (!$isValidAssignment) {
            abort(403, 'Anda tidak mengajar mata pelajaran ini di kelas tersebut.');
        }

        $class = SchoolClass::findOrFail($validated['id_class']);
        $subject = Subject::findOrFail($validated['id_subject']);
        $students = Student::where('id_class', $validated['id_class'])->orderBy('name')->get();

        return view('exams.students-form', [
            'class' => $class,
            'subject' => $subject,
            'examType' => $validated['exam_type'],
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $teacher = $this->currentTeacher();

        $validated = $request->validate([
            'id_class' => ['required', 'exists:tbl_classes,id'],
            'id_subject' => ['required', 'exists:tbl_subjects,id'],
            'exam_type' => ['required', 'in:Tugas,UTS,UAS,Kuis'],
            'score' => ['required', 'array'],
            'score.*' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $isValidAssignment = ClassRoutine::where('id_teacher', $teacher->id)
            ->where('id_class', $validated['id_class'])
            ->where('id_subject', $validated['id_subject'])
            ->exists();

        if (!$isValidAssignment) {
            abort(403, 'Anda tidak mengajar mata pelajaran ini di kelas tersebut.');
        }

        foreach ($validated['score'] as $studentId => $score) {
            Exam::create([
                'creation_time' => now(),
                'create_id' => auth()->id(),
                'archived' => 0,
                'id_user' => auth()->id(),
                'id_student' => $studentId,
                'id_subject' => $validated['id_subject'],
                'id_class' => $validated['id_class'],
                'id_teacher' => $teacher->id,
                'exam_type' => $validated['exam_type'],
                'score' => $score,
            ]);
        }

        return redirect()->route('exams.index')->with('success', 'Nilai berhasil disimpan.');
    }

    public function destroy($id)
    {
        $teacher = $this->currentTeacher();
        $exam = Exam::findOrFail($id);

        if (!$teacher || $exam->id_teacher != $teacher->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus nilai ini.');
        }

        $exam->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}