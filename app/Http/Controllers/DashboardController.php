<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title_page' => 'Dashborad',
            'title' => 'Dashborad',
            'data_guru' => Teacher::count('id'),
            'data_siswa' => Student::count('id'),
        ];
        return view('dashboard.index', $data);
    }
}
