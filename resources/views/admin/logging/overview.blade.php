@extends('admin.layouts.admin')
<!--vars = userActivity and endpointActivity-->
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Latest logs</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded shadow">
            <input type="radio" name="type" id="subscribers" checked hidden>
            <input type="radio" name="type" id="users" hidden>
            <label for="subscribers" class="border-purple-200 text-purple-600 hover:border-transparent hover:bg-purple-600 hover:text-white active:bg-purple-700">
                Subscriber logs
            </label>
            <label for="users" id="user_button">
                User logs
            </label>

            <div id="subscriber_logs">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identifier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">endpoint used</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">files downloaded</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">activity date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">activity time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">authorised</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">data transferred</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($endpointActivity as $activity)
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">{{ $activity->id }}</th>
                            <th class="px-6 py-4 whitespace-nowrap">{{ $activity->identifier }}</th>
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
            
            <div id="user_logs">
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
            <style>
                #user_logs, #subscriber_logs { display: none; }
                #subscribers:checked ~ #subscriber_logs { display: block; }
                #users:checked ~ #user_logs { display: block; }
            </style>
        </div>
    </div>
@endsection
