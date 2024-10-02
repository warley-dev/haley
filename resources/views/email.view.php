<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <div class="main">
        <div class="header">
            <div class="header-content">
                <h1 class="header-title">Helo word</h1>
            </div>
        </div>

        <div class="content">
            {{ $text }}
        </div>

        <div class="footer"> © {{ date('Y') }} - Haley</div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
            scroll-behavior: smooth;
            font-family: system-ui;
        }

        .main {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
        }

        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
            overflow: hidden;
            border-bottom: 1px solid rgba(187, 187, 187, 0.767);
            padding-bottom: 10px;
        }

        .header-content {
            padding: 6px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .header-title {
            display: flex;
            width: calc(100% - 50px);
            font-size: 22px;
            color: #4f4f4f;
        }

        .header-subtitle {
            font-size: 12px;
            color: #777777d6;
        }

        .logo {
            width: 65px;
            object-fit: contain;
            padding: 6px;
        }

        .content {
            padding: 30px 8px;
        }

        .footer {
            padding: 8px;
            color: #4b4b4b;
            font-size: 12px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #bbbbbb63;
            border-radius: 6px;
        }
    </style>
</body>

</html>