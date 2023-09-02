@extends('components.layouts.app')

@section('content')
    <!doctype html>
<html lang={{ str_replace('_', '-', app()->getLocale()) }}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="flex flex-col justify-center items-center h-screen bg-slate-800">
    <div class="gap-2 bg-slate-700 rounded-xl border border-slate-900 p-4">
        <h1 class="text-3xl font-bold text-white">
            Register
        </h1>
        <p class="text-white">
            Please register to continue.
        </p>

        <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-2">
            @csrf
            <label for="email">Email</label>
            <input
                type="text"
                name="email"
                id="email"
                placeholder="Email"
                class="rounded-lg border border-slate-900 p-2 w-full4"
            />


            <label for="password">
                Password
            </label>
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Password"
                class="rounded-lg border border-slate-900 p-2 w-full"
            />

            <label for="password_confirmation">
                Confirm Password
            </label>
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Confirm Password"
                class="rounded-lg border border-slate-900 p-2 w-full"
            />
            <button
                class="bg-slate-900 text-white rounded-lg p-2 w-full mt-4"
            >Register
            </button>
        </form>
        @if($errors->any())
            <div class="bg-red-500 rounded-lg p-4 mt-4">
                <ul class="list-disc list-inside text-white">
                    @foreach($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
</body>
</html>

@endsection
