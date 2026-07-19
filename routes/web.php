<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassRoutineController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherPortalController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\ParentPortalController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/majors', [MajorController::class, 'index'])->name('majors.index');
    Route::get('/majors/data', [MajorController::class, 'data'])->name('majors.data');
    Route::get('/majors/create', [MajorController::class, 'create'])->name('majors.create');
    Route::post('/majors', [MajorController::class, 'store'])->name('majors.store');
    Route::get('/majors/{id}/edit', [MajorController::class, 'edit'])->name('majors.edit');
    Route::put('/majors/{id}', [MajorController::class, 'update'])->name('majors.update');
    Route::delete('/majors/{id}', [MajorController::class, 'destroy'])->name('majors.destroy');
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    // ...routes majors yang sudah ada...

    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/data', [ClassController::class, 'data'])->name('classes.data');
    Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{id}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');
});


Route::middleware(['auth', 'role:1,2'])->group(function () {
    // ...routes majors & classes yang sudah ada...

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/data', [StudentController::class, 'data'])->name('students.data');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});


Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/data', [TeacherController::class, 'data'])->name('teachers.data');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
});


Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/data', [SubjectController::class, 'data'])->name('subjects.data');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
});



Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/class-routines', [ClassRoutineController::class, 'index'])->name('class-routines.index');
    Route::get('/class-routines/data', [ClassRoutineController::class, 'data'])->name('class-routines.data');
    Route::get('/class-routines/create', [ClassRoutineController::class, 'create'])->name('class-routines.create');
    Route::post('/class-routines', [ClassRoutineController::class, 'store'])->name('class-routines.store');
    Route::get('/class-routines/{id}/edit', [ClassRoutineController::class, 'edit'])->name('class-routines.edit');
    Route::put('/class-routines/{id}', [ClassRoutineController::class, 'update'])->name('class-routines.update');
    Route::delete('/class-routines/{id}', [ClassRoutineController::class, 'destroy'])->name('class-routines.destroy');
});


Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/data', [AttendanceController::class, 'data'])->name('attendances.data');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
});

Route::middleware(['auth', 'role:3,5'])->group(function () {
    Route::get('/my-schedule', [TeacherPortalController::class, 'schedule'])->name('teacher.schedule');
});

Route::middleware(['auth', 'role:3,5'])->group(function () {
    Route::get('/my-classes', [TeacherPortalController::class, 'classes'])->name('teacher.classes');
    Route::get('/my-classes/{id}/students', [TeacherPortalController::class, 'classStudents'])->name('teacher.classes.students');
    Route::get('/my-classes/{id}/attendance', [TeacherPortalController::class, 'attendanceForm'])->name('teacher.attendance.create');
    Route::post('/my-classes/{id}/attendance', [TeacherPortalController::class, 'attendanceStore'])->name('teacher.attendance.store');
});



Route::middleware(['auth', 'role:4'])->group(function () {
    Route::get('/my-class-schedule', [StudentPortalController::class, 'schedule'])->name('student.schedule');
    Route::get('/my-attendance', [StudentPortalController::class, 'attendance'])->name('student.attendance');
    Route::get('/my-scores', [StudentPortalController::class, 'scores'])->name('student.scores');
});


Route::middleware(['auth', 'role:3,5'])->group(function () {
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams/students-form', [ExamController::class, 'studentsForm'])->name('exams.students-form');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/password-resets', [PasswordResetRequestController::class, 'index'])->name('password-resets.index');
    Route::get('/password-resets/data', [PasswordResetRequestController::class, 'data'])->name('password-resets.data');
    Route::post('/password-resets/{id}/process', [PasswordResetRequestController::class, 'process'])->name('password-resets.process');
});

Route::middleware(['auth', 'role:6'])->group(function () {
    Route::get('/my-children', [ParentPortalController::class, 'index'])->name('parent.index');
    Route::get('/my-children/{studentId}/schedule', [ParentPortalController::class, 'schedule'])->name('parent.schedule');
    Route::get('/my-children/{studentId}/attendance', [ParentPortalController::class, 'attendance'])->name('parent.attendance');
    Route::get('/my-children/{studentId}/scores', [ParentPortalController::class, 'scores'])->name('parent.scores');
});