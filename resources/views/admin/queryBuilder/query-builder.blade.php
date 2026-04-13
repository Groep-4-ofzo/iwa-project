@extends('admin.layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-blue-50 border-2 border-blue-400 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-3">Opgeslagen Query Uitvoeren</h3>
            <div class="flex gap-3">
                <select id="query_select" class="flex-1 border rounded px-3 py-2">
                    <option value="">-- Kies een query --</option>
                    @foreach($queries as $q)
                        <option value="{{ $q->id }}">{{ $q->omschrijving }}</option>
                    @endforeach
                </select>
                <button onclick="runExisting()" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded">
                    Uitvoeren
                </button>
            </div>
        </div>

        <form action="{{ route('admin.query.store') }}" method="POST">
            @csrf
            <h1 class="text-2xl font-bold mb-4">Query Builder</h1>

            <input type="text" name="omschrijving" placeholder="Naam van de Query" required
                   class="w-full border rounded px-3 py-2 mb-4">
            <input type="number" name="contract_id" placeholder="Contract ID"
                   class="border rounded px-3 py-2 mb-6">

            @for ($i = 0; $i < $groupCount; $i++)
                <div class="border rounded p-4 mb-3 bg-gray-50">
                    <strong class="block mb-2">Groep {{ $i + 1 }}</strong>
                    <select name="groups_data[{{ $i }}][operator]" class="border rounded px-2 py-1">
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}">{{ $op->omschrijving }}</option>
                        @endforeach
                    </select>

                    <div class="mt-3 pl-5">
                        @php $cCount = $criteriaCounts[$i] ?? 1; @endphp
                        @for ($j = 0; $j < $cCount; $j++)
                            <div class="flex gap-2 mb-2">
                                <select name="groups_data[{{ $i }}][criteria][{{ $j }}][type]" class="flex-2 border rounded px-2 py-1">
                                    @foreach($criteriumTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->omschrijving }}</option>
                                    @endforeach
                                </select>
                                <select name="groups_data[{{ $i }}][criteria][{{ $j }}][comparison]" class="border rounded px-2 py-1">
                                    @foreach($comparisons as $comp)
                                        <option value="{{ $comp->id }}">{{ $comp->omschrijving }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="groups_data[{{ $i }}][criteria][{{ $j }}][value]"
                                       placeholder="Waarde..."
                                       class="flex-1 border rounded px-2 py-1">
                            </div>
                        @endfor
                        <a href="{{ route('admin.query.index', ['groups' => $groupCount, 'criteria' => array_replace($criteriaCounts, [$i => $cCount + 1])]) }}"
                           class="text-blue-600 hover:underline text-sm">+ Regel</a>
                    </div>
                </div>
            @endfor

            <div class="flex justify-between items-center mt-5">
                <a href="{{ route('admin.query.index', ['groups' => $groupCount + 1, 'criteria' => $criteriaCounts]) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    + Groep
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                    Opslaan & Uitvoeren
                </button>
            </div>
        </form>
    </div>

    <script>
        function runExisting() {
            const id = document.getElementById('query_select').value;
            if (id) window.location.href = '/admin/query/execute/' + id;
        }
    </script>
@endsection
