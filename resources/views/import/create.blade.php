<!DOCTYPE html>

<h1>Import contacts</h1>

<form action="{{ \route('import.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="file" name="file">

    <button type="submit">send</button>
</form>
