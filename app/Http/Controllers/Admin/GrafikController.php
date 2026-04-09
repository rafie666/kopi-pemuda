<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class GrafikController extends Controller
{
    public function index()
    {
        return view('admin.grafik');
    }
}
