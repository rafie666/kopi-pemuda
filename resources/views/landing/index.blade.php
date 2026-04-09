@extends('layouts.app')

@section('title', 'Selamat Datang di Kopi Pemuda')

@section('content')
    <h1>Selamat Datang di Kopi Pemuda ☕</h1>
    <p>Silakan login untuk mengakses dashboard Admin atau Kasir.</p>

    @guest
        <a href="{{ route('login') }}" style="padding:10px 20px; background:#333; color:#fff; text-decoration:none; border-radius:5px;">Login</a>
    @endguest
@endsection
