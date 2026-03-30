@extends('admin.layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Logs of user {{ $user->id }} {{$user->name}}</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">endpoint used</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">files downloaded</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">activity date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">activity time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">authorised</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">data transferred</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($userActivity as $activity)
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->id }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->userid }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->endpoint_used }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->files_downloaded }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->activity_date }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->activity_time }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->authorized }}</th>
                        <th class="px-6 py-4 whitespace-nowrap">{{ $activity->data_transferred }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
