<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();
        $totalMajors = Major::count();
        $totalUsers = auth()->user()->role == 1 ? User::where('archived', 0)->count() : null;

        return view('dashboard', compact('totalStudents', 'totalClasses', 'totalMajors', 'totalUsers'));
    }
}