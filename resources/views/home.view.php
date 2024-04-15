<!DOCTYPE html>
<html lang="pt-BR">

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haley</title>
</head>

<body>
    @section('h1') Helo Word @endsection


    {{-- @include('include.teste')     --}}

    

    <main>
        <div class="box-img">
            <img class="logo" src="assets/img/haley.png" alt="Logo">
        </div>

    </main>

    <script>
        var socket = new WebSocket('ws://http://framework/socket');

        socket.onmessage = function(e) {
           console.log(e)
        };

        console.log(socket)
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
            scroll-behavior: smooth;
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