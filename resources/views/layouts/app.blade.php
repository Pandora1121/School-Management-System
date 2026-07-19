<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Sekolah')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar {
            min-height: 100vh;
            width: 240px;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,.1);
        }
        .main-content {
            flex: 1;
        }
    </style>
</head>
<body class="bg-light">
    @auth
    <div class="d-flex">
        <nav class="sidebar bg-dark p-3 flex-shrink-0">
            <h5 class="text-white mb-4">Manajemen Sekolah</h5>
            <ul class="nav flex-column gap-1">

                {{-- Semua role --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">Profil Saya</a>
                </li>

                {{-- Super Admin & Admin --}}
                @if (in_array(auth()->user()->role, [1, 2]))
                    <li class="nav-item">
                        <a href="{{ route('majors.index') }}" class="nav-link {{ request()->routeIs('majors.*') ? 'active' : '' }}">Jurusan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">Data Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">Data Guru</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">Mata Pelajaran</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('class-routines.index') }}" class="nav-link {{ request()->routeIs('class-routines.*') ? 'active' : '' }}">Jadwal Pelajaran</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">Absensi Siswa</a>
                    </li>
                @endif

                {{-- Guru & Wali Kelas --}}
                @if (in_array(auth()->user()->role, [3, 5]))
                    <li class="nav-item">
                        <a href="{{ route('teacher.schedule') }}" class="nav-link {{ request()->routeIs('teacher.schedule') ? 'active' : '' }}">Jadwal Mengajar</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.classes') }}" class="nav-link {{ request()->routeIs('teacher.classes*') ? 'active' : '' }}">Kelas Saya</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('exams.index') }}" class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}">Nilai Siswa</a>
                    </li>
                @endif

                {{-- Siswa --}}
                @if (auth()->user()->role == 4)
                    <li class="nav-item">
                        <a href="{{ route('student.schedule') }}" class="nav-link {{ request()->routeIs('student.schedule') ? 'active' : '' }}">Jadwal Pelajaran</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.attendance') }}" class="nav-link {{ request()->routeIs('student.attendance') ? 'active' : '' }}">Riwayat Presensi</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.scores') }}" class="nav-link {{ request()->routeIs('student.scores') ? 'active' : '' }}">Nilai Saya</a>
                    </li>
                @endif

                {{-- Super Admin saja --}}
                @if (auth()->user()->role == 1)
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">User List</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('password-resets.index') }}" class="nav-link {{ request()->routeIs('password-resets.*') ? 'active' : '' }}">Reset Password</a>
                    </li>
                @endif

            </ul>
        </nav>
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    @else
        @yield('content')
    @endauth
    @stack('scripts')
</body>
</html>