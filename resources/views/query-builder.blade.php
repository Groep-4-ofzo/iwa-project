<div style="font-family: sans-serif; padding: 20px; max-width: 900px; margin: auto;">
    
    <div style="border: 2px solid #3490dc; padding: 20px; margin-bottom: 40px; background: #f1f8ff; border-radius: 8px;">
        <h3>Opgeslagen Query Uitvoeren</h3>
        <div style="display: flex; gap: 10px;">
            <select id="query_select" style="flex-grow: 1; padding: 10px;">
                <option value="">-- Kies een query --</option>
                @foreach($queries as $q)
                    <option value="{{ $q->id }}">{{ $q->omschrijving }}</option>
                @endforeach
            </select>
            <button onclick="runExisting()" style="background: #38c172; color: white; padding: 10px 20px; border: none; cursor: pointer;">▶ Uitvoeren</button>
        </div>
    </div>

    <form action="{{ route('query.store') }}" method="POST">
        @csrf
        <h1>Query Builder</h1>
        <input type="text" name="omschrijving" placeholder="Naam van de Query" required style="width:100%; padding:10px; margin-bottom:20px;">
        <input type="number" name="contract_id" placeholder="1" />
        @for ($i = 0; $i < $groupCount; $i++)
            <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; background: #f9f9f9;">
                <strong>Groep {{ $i + 1 }}</strong>
                <select name="groups_data[{{ $i }}][operator]">
                    @foreach($operators as $op) <option value="{{ $op->id }}">{{ $op->omschrijving }}</option> @endforeach
                </select>

                <div style="margin-top: 10px; padding-left: 20px;">
                    @php $cCount = $criteriaCounts[$i] ?? 1; @endphp
                    @for ($j = 0; $j < $cCount; $j++)
                        <div style="margin-bottom: 10px; display: flex; gap: 5px;">
                            <select name="groups_data[{{ $i }}][criteria][{{ $j }}][type]" style="flex: 2; padding: 8px;">
                                @foreach($criteriumTypes as $type) <option value="{{ $type->id }}">{{ $type->omschrijving }}</option> @endforeach
                            </select>
                            <select name="groups_data[{{ $i }}][criteria][{{ $j }}][comparison]" style="flex: 1; padding: 8px;">
                                @foreach($comparisons as $comp) <option value="{{ $comp->id }}">{{ $comp->omschrijving }}</option> @endforeach
                            </select>
                            <input type="text" name="groups_data[{{ $i }}][criteria][{{ $j }}][value]" placeholder="Waarde..." style="flex: 2; padding: 8px;">
                        </div>
                    @endfor
                    <a href="{{ route('query.index', ['groups' => $groupCount, 'criteria' => array_replace($criteriaCounts, [$i => $cCount + 1])]) }}">+ Regel</a>
                </div>
            </div>
        @endfor

        <div style="margin-top: 20px;">
            <a href="{{ route('query.index', ['groups' => $groupCount + 1, 'criteria' => $criteriaCounts]) }}" style="background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">+ Groep</a>
            <button type="submit" style="background: #3490dc; color: white; padding: 10px 25px; border: none; float: right; cursor: pointer;">Opslaan & Uitvoeren</button>
        </div>
    </form>
</div>

<script>
    function runExisting() {
        const id = document.getElementById('query_select').value;
        if(id) window.location.href = '/query/execute/' + id;
    }
</script>