<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="@route('web.method')" method="post" enctype="multipart/form-data" aria-multiline="true">
        <input type="text" hidden name="_token" value="@csrf">
        <input type="text" name="nome" value="">
        <input type="text" name="email" value="">
        <input type="file" multiple id="file" name="file[]">
        <input type="text" name="m[0]" value="">
        <input type="text" name="m[1]" value="">
        <input type="text" name="m[2]" value="">

        <input type="submit" value="enviar">
    </form>
</body>

</html>