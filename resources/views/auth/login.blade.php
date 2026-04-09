<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kopi Pemuda</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        .input-gradient {
            background: linear-gradient(180deg, #d1d5db 0%, #9ca3af 100%);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }
        .input-gradient:focus {
            background: #e5e7eb;
        }
        .glass-panel {
            background: rgba(20, 20, 20, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-input {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-1px);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%);
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #ffffff 0%, #e5e7eb 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="h-screen flex justify-center items-center font-sans bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('images/kopii.jpeg') }}'); background-color: #1a1a1a;">

    <!-- Dark Overlay to ensure text readability -->
    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <div class="glass-panel rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 w-[90%] max-w-[24rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] flex flex-col items-center z-10 relative transition-transform duration-500 hover:scale-[1.02]">
        <!-- Logo Icon -->
        <div class="bg-white/90 backdrop-blur-sm rounded-full p-4 mb-4 w-20 h-20 flex items-center justify-center shadow-lg transform transition duration-500 hover:rotate-[360deg]">
            <img src="{{ asset('images/logo.png') }}" class="w-12 h-12 object-contain">
        </div>

        <!-- Title -->
        <h1 class="text-white font-bold text-xl tracking-[0.3em] uppercase mb-10 text-center drop-shadow-md">KOPI PEMUDA</h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="w-full bg-red-500/80 backdrop-blur-sm text-white text-xs px-4 py-2 rounded-lg mb-4 text-center border border-red-400/50">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="w-full space-y-5">
            @csrf
            
            <!-- Username Input -->
            <div class="relative group">
                <input type="text" name="username" placeholder="username" 
                    class="w-full glass-input py-3 px-6 rounded-full text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/50 text-center transition-all duration-300 shadow-inner"
                    required autofocus autocomplete="off">
            </div>

            <!-- Password Input -->
            <div class="relative group">
                <input type="password" name="password" id="password" placeholder="Password" 
                    class="w-full glass-input py-3 px-6 rounded-full text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/50 text-center transition-all duration-300 shadow-inner"
                    required>
                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-900 focus:outline-none transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full btn-gradient py-3 rounded-full text-black font-bold lowercase tracking-widest transition-all duration-300 mt-4 shadow-lg active:scale-95">
                masuk
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

</body>
</html>
