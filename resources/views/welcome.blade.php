<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/btvtc-logo.ico') }}">
    <title>btvtc-mis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-slate-100 relative overflow-hidden min-h-screen">
    <!-- Floating background shapes -->
    <div aria-hidden="true" class="absolute inset-0 -z-10">
        <div class="absolute -left-32 -top-24 w-96 h-96 bg-gradient-to-tr from-sky-200 via-cyan-200 to-blue-200 rounded-full blur-3xl opacity-60 transform rotate-45"></div>
        <div class="absolute right-[-8rem] top-1/4 w-72 h-72 bg-gradient-to-br from-emerald-200 via-teal-200 to-cyan-200 rounded-full blur-2xl opacity-50"></div>
        <div class="absolute left-1/4 bottom-[-6rem] w-80 h-80 bg-gradient-to-bl from-blue-100 via-sky-200 to-emerald-200 rounded-full blur-2xl opacity-40"></div>
    </div>

    <div class="flex items-center justify-center min-h-screen px-6">
        <div class="relative w-full max-w-3xl">
                    <img src="{{ asset('images/btvtc-logo.png') }}" alt="BTVTC logo" class="w-48 h-48 lg:w-80 lg:h-80 flex-shrink-0 mx-auto rounded-full shadow-lg shadow-green-200">
                    <div class="text-center">
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-green-900">BTVTC Management Information System</h1>
                        <p class="mt-3 text-slate-600 text-lg">Streamlining operations and enhancing efficiency with secure, modern tools.</p>
                        <div class="mt-6 flex justify-center gap-4">
                            @auth
                                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-green-700 hover:bg-green-800 text-white font-semibold shadow-lg shadow-green-200">Go to Dashboard</a>
                            @else
                                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-green-700 hover:bg-green-800 text-white font-semibold shadow-lg shadow-green-200">Login</a>
                            @endauth
                        </div>
                    </div>
              
            <!-- subtle floating card to give depth -->
            <div class="pointer-events-none absolute -right-8 -bottom-8 w-40 h-24 bg-white/50 rounded-xl blur-sm opacity-60 transform rotate-6"></div>
        </div>
    </div>
</body>
</html>