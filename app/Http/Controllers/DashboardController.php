<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoutine;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role == 4) {
            return $this->studentDashboard($user);
        }

        if (in_array($user->role, [3, 5])) {
            return $this->teacherDashboard($user);
        }

        return $this->adminDashboard($user);
    }

    private function studentDashboard($user)
    {
        $student = $user->student;
        $todayRoutines = collect();
        $recentAttendance = collect();

        if ($student) {
            $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
            $todayName = $dayMap[Carbon::now()->format('l')] ?? '';

            $todayRoutines = ClassRoutine::with('subject')
                ->where('id_class', $student->id_class)
                ->where('day', $todayName)
                ->orderBy('start_time')
                ->get();

            $recentAttendance = Attendance::where('id_student', $student->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard-student', compact('student', 'todayRoutines', 'recentAttendance'));
    }

    private function teacherDashboard($user)
    {
        $teacher = $user->teacher;
        $todayRoutines = collect();
        $classCount = 0;

        if ($teacher) {
            $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
            $todayName = $dayMap[Carbon::now()->format('l')] ?? '';

            $todayRoutines = ClassRoutine::with(['schoolClass', 'subject'])
                ->where('id_teacher', $teacher->id)
                ->where('day', $todayName)
                ->orderBy('start_time')
                ->get();

            $classCount = SchoolClass::where('id_wali_kelas', $teacher->id)->count();
        }

        return view('dashboard-teacher', compact('teacher', 'todayRoutines', 'classCount'));
    }

    private function adminDashboard($user)
    {
        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();
        $totalMajors = Major::count();
        $totalUsers = $user->role == 1 ? User::where('archived', 0)->count() : null;

        // Data untuk grafik: siswa per jurusan
        $studentsPerMajor = Major::withCount('students')->orderBy('name')->get();

        // Data untuk grafik: distribusi gender siswa
        $genderCounts = [
            'L' => Student::where('gender', 'L')->count(),
            'P' => Student::where('gender', 'P')->count(),
        ];

        // Data untuk grafik: tren kehadiran 7 hari terakhir
        $attendanceTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Attendance::where('date', $date->format('Y-m-d'))
                ->where('status', 'Hadir')
                ->count();
            $attendanceTrend[] = [
                'date' => $date->translatedFormat('d M'),
                'count' => $count,
            ];
        }

        return view('dashboard', compact(
            'totalStudents', 'totalClasses', 'totalMajors', 'totalUsers',
            'studentsPerMajor', 'genderCounts', 'attendanceTrend'
        ));
    }
}