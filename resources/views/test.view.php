<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Test</title>
</head>

<body>
    {{-- @include('include.teste')

    @section('h1') Ola @endsection --}}

    <h1>@url('/fghfgh')</h1>
    <h1>@urlFull('/fghfgh')</h1>
    <h1>@urlFullQuery(['teste' => 'ola'])</h1>

    <h1>@csrf</h1>
    <h1>@route('web.files',['um','dois'])</h1>
</body>

</html>