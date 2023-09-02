@extends('components.layouts.app')

@section('content')
    <div class="flex justify-center items-center flex-col h-min bg-slate-600">
        <div class="p-3 flex justify-between w-full bg-slate-700">
            <h3 class="text-2xl font-bold text-white">Actions</h3>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    class="bg-slate-900 text-white rounded-lg p-2 w-32"
                >Leave
                </button>
            </form>
        </div>
        <div class="w-1/2 p-3">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                <p class="text-gray-700 text-base">You are logged in!</p>
            </div>
        </div>
    </div>
@endsection
