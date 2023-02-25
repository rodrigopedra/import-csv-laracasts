<!DOCTYPE html>

<h1>Contacts @if($count)(total: {{ \number_format($count) }})@endif</h1>

<p>
    <a href="{{ \route('import.create') }}">Import</a>
</p>

@if($time)
    <p>Import took {{ \number_format($time, 2) }} seconds</p>
@endif

<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td>{{ $contact->last_name }}, {{ $contact->first_name }}</td>
                <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                <td><a href="tel:{{ $contact->phone_number }}">{{ $contact->phone_number }}</a></td>
            </tr>
        @empty
            <tr>
                <td align="center" colspan="3"><em>no contacts</em></td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $contacts->links('vendor.pagination.simple-default') }}
