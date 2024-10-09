<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>@csrf</h1>
    
    <form method="POST" action="{{ route('web.post') }}">



        <input name="_token" value="@csrf" type="hidden">

        <input type="text" name="um">
        <input type="text" name="dois">


        <button type="submit">Enviar</button>
    </form>

</body>
</html>