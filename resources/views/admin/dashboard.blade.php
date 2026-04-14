@extends('admin.layouts.admin')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center bg-gray-50 px-4">

    <div class="max-w-2xl w-full bg-white shadow-xl rounded-2xl p-8 relative">

        <!-- Quote icon -->
        <div class="absolute -top-6 left-6 text-5xl text-indigo-500 opacity-20">
            ❝
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">
            Quote van de dag
        </h2>

        <!-- Quote text -->
        <div class="text-lg text-gray-600 italic leading-relaxed text-center">
            {!! $quote !!}
        </div>

        <!-- Divider -->
        <div class="my-6 border-t"></div>

        <!-- Footer -->
        <div class="text-sm text-gray-400 text-center">
            Elke dag een beetje inspiratie ✨
        </div>

    </div>

</div>
@endsection
