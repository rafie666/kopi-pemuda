<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kopi Pemuda')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
    <nav style="padding:10px; background:#333; color:#fff; display:flex; justify-content:space-between;">
        <div>
            <a href="/" style="color:#fff; text-decoration:none; font-weight:bold;">Kopi Pemuda</a>
        </div>
        <div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" style="color:#fff; margin-right:10px;">Dashboard</a>
                @elseif(auth()->user()->role === 'kasir')
                    <a href="{{ route('kasir.dashboard') }}" style="color:#fff; margin-right:10px;">Dashboard</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:#fff; cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color:#fff; margin-right:10px;">Login</a>
                <a href="{{ route('register') }}" style="color:#fff;">Register</a>
            @endauth
        </div>
    </nav>

    <div style="padding:20px;">
        @yield('content')
    </div>
</body>
</html>
