@extends('admin.layouts.admin')
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

        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Latest logs</h1>
            </div>

            <input type="radio" id="subscribers" name="log_tabs" class="peer/subscribers hidden" checked />
            <input type="radio" id="users" name="log_tabs" class="peer/users hidden" />

            <div class="flex space-x-1 mb-0 px-2">
                <label for="subscribers"
                    class="cursor-pointer px-6 py-2 text-sm font-bold rounded-t-lg transition-all duration-200
                            /* Standaard staat (Inactief/Donkerder) */
                            bg-gray-200 text-gray-500 hover:bg-gray-300
                            /* Actieve staat (Geselecteerd/Wit) */
                            peer-checked/subscribers:bg-white peer-checked/subscribers:text-purple-600 peer-checked/subscribers:shadow-[-1px_-1px_5px_rgba(0,0,0,0.05)]">
                    Subscriber logs
                </label>

                <label for="users"
                    class="cursor-pointer px-6 py-2 text-sm font-bold rounded-t-lg transition-all duration-200
                            /* Standaard staat (Inactief/Donkerder) */
                            bg-gray-200 text-gray-500 hover:bg-gray-300
                            /* Actieve staat (Geselecteerd/Wit) */
                            peer-checked/users:bg-white peer-checked/users:text-purple-600 peer-checked/users:shadow-[-1px_-1px_5px_rgba(0,0,0,0.05)]">
                    User logs
                </label>
            </div>

            <div id="subscriber_logs"
                class="hidden peer-checked/subscribers:block bg-white shadow-lg rounded-b-lg rounded-tr-lg overflow-x-auto border-t border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Identifier</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Endpoint</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Files</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Auth</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="endpoint_table">
                        @foreach($endpointActivity as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $activity->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $activity->identifier }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->endpoint_used }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->files_downloaded }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->activity_date }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->activity_time }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->authorized }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $activity->data_transferred }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="user_logs"
                class="hidden peer-checked/users:block bg-white shadow-lg rounded-b-lg rounded-tr-lg overflow-x-auto border-t border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Endpoint</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Files</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Auth</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="user_table">
                        @foreach($userActivity as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $activity->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $activity->userid }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->endpoint_used }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->files_downloaded }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->activity_date }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->activity_time }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->authorized }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $activity->data_transferred }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const refreshTime = 10; // Refresh time in seconds
        const itemsPerPage = 10;

        const tableConfigs = {
            endpoint: {
                tableName: 'endpoint_table',
                paginationName: 'endpoint_pagination',
                columns: (row) => `
                    <td class="px-6 py-4 text-sm text-gray-900">${ row.id }</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">${ row.identifier }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.endpoint_used }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.files_downloaded }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.activity_date }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.activity_time }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.authorized }</td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">${ row.data_transferred }</td>
                `,
                colspan: 8,
            },
            user: {
                tableName: 'user_table',
                paginationName: 'user_pagination',
                columns: (row) => `
                    <td class="px-6 py-4 text-sm text-gray-900">${ row.id }</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">${ row.userid }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.endpoint_used }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.files_downloaded }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.activity_date }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.activity_time }</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${ row.authorized }</td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">${ row.data_transferred }</td>
                `,
                colspan: 8,
            },
        };

        let data = {};

        async function fetchData() {
            try {
                const response = await fetch('/api/logs');
                data = await response.json();

                renderTable('endpoint');
                renderTable('user')
            } catch (e) {
                console.error(e);
            }
        }

        function renderTable(table) {
            const config = tableConfigs[table];
            const tableBody = document.getElementById(config.tableName);
            const rows = data[table] ?? [];

            if (rows.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="${config.colspan}" class="text-center text-gray-500 py-6">Geen gegevens gevonden</td></tr>`;
            } else {
                tableBody.innerHTML = rows.map(row => `<tr>${config.columns(row)}</tr>`).join('');
            }
        }

        fetchData()
        setInterval(fetchData, refreshTime * 1000)
    </script>
@endsection
