<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class OrderController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('kasir.order', compact('menus'));
    }
}
