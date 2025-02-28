<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="
    min-h-screen
    bg-gradient-to-br from-blue-50 to-indigo-100
    flex items-center justify-center
    p-4
">
    <div class="
        w-full max-w-md
        bg-white
        rounded-2xl
        shadow-lg
    ">
        {{-- Card Header --}}
        <div class="p-8">
            <h1 class="
                text-2xl font-semibold
                text-center
                text-gray-800
                mb-2
            ">
                Selamat Datang Kembali
            </h1>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="
                    mb-6
                    bg-red-50
                    text-red-600
                    p-4
                    rounded-lg
                    text-sm
                ">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login.authenticate') }}" method="POST">
                @csrf
                
                {{-- Username Field --}}
                <div class="mb-6">
                    <label 
                        for="username" 
                        class="
                            block
                            text-sm font-medium
                            text-gray-700
                            mb-2
                        "
                    >
                        Username
                    </label>
                    <input 
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="
                            w-full
                            px-4 py-3
                            border border-gray-200
                            rounded-lg
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition-colors
                            placeholder-gray-400
                            outline-none
                        "
                        placeholder="Masukkan Username"
                        required
                        autofocus
                    >
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="mb-6">
                    <label 
                        for="password" 
                        class="
                            block
                            text-sm font-medium
                            text-gray-700
                            mb-2
                        "
                    >
                        Password
                    </label>
                    <input 
                        type="password"
                        id="password"
                        name="password"
                        class="
                            w-full
                            px-4 py-3
                            border border-gray-200
                            rounded-lg
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition-colors
                            placeholder-gray-400
                            outline-none
                        "
                        placeholder="Masukkan kata sandi"
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me Checkbox --}}
                <div class="flex items-center mb-6">
                    <input 
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="
                            w-4 h-4
                            text-blue-600
                            border-gray-300
                            rounded
                            focus:ring-blue-500
                        "
                    >
                    <label 
                        for="remember"
                        class="ml-2 text-sm text-gray-600"
                    >
                        Ingat saya
                    </label>
                </div>

                {{-- Submit Button --}}
                <button 
                    type="submit"
                    class="
                        w-full
                        px-4 py-3
                        bg-blue-600
                        hover:bg-blue-700
                        text-white
                        font-medium
                        rounded-lg
                        transition-colors
                        focus:outline-none
                        focus:ring-2
                        focus:ring-blue-500
                        focus:ring-offset-2
                    "
                >
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>