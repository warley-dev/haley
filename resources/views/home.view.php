<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haley</title>
</head>

<body>
    {{-- @include('include.teste'); --}}

    @foreach ($filmes as $filme)
    <div>
        <p>{{ $filme->titulo }}</p>
    </div>
    @endforeach

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
            scroll-behavior: smooth;
            color: white;
        }

        p {
            width: 100%;
            text-align: center;
            padding: 2px;
        }

        main {
            min-height: 100vh;
            width: 100%;
            display: flex;
            position: relative;
            align-content: center
        }

        .box-img {
            display: flex;
            width: 100%;
            justify-content: center;
        }

        .box-img .logo {
            width: 260px;
            height: 260px;
            object-fit: contain;
        }

        body {
            background-color: #282828;
        }
    </style>
</body>

</html>