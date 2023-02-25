<!DOCTYPE html>

<h1>Match columns</h1>

@if($headers)
    <form action="{{ \route('import.update', ['import' => $import]) }}" method="POST">
        @csrf
        @method('PATCH')

        <table border="1">
            <thead>
                <tr>
                    <th>CSV Column</th>
                    <th>DB Column</th>
                </tr>
            </thead>
            <tbody>
                @foreach($headers as $header)
                    <tr>
                        <th scope="row">
                            <label for="{{ $header }}">{{ $header }}</label>
                        </th>
                        <td>
                            <select name="map[{{ $header }}]" id="{{ $header }}" autocomplete="off">
                                <option value="">[SKIP]</option>
                                @foreach($columns as $column)
                                    <option>{{ $column }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit">import</button>
    </form>
@else
    <p><strong>Empty file!</strong></p>
@endif

@if($records)
    <h2>Data sample</h2>

    <table border="1">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    @foreach($headers as $index => $ignored)
                        <td>{{ $record[$index] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<h2>Or delete and cancel</h2>

<form action="{{ \route('import.destroy', ['import' => $import]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">delete</button>
</form>
