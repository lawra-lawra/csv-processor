<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

</head>
<body class="antialiased">
    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Upload a CSV</label>
        <input type="file" name="file" id="file"  />

        <input type="submit" />
    </form>

    @if (! empty($people))
        <pre>{{ print_r($people) }}</pre>
    @endif
</body>
