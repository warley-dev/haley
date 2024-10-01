<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">

    <title>DEBUG</title>
</head>

<body>
    <div class="header-menu">
        <div class="error_message">
            <p>
                <?php echo $error_message ?>
            </p>

            <!-- <a href="vscode://file{{$error_file}}:{{$error_line}}">{{$error_file}}:{{$error_line}}</a> -->
            <!-- href="vscode://file/C:/caminho/do/seu/arquivo.txt" -->
            <a class="error_message_link" href="vscode://file{{$error_file}}:{{$error_line}}">
                <?php echo $error_file ?>
            </a>
        </div>

        <div class=" menu">
            <p class="menu-btn menu-btn-active" data-btn="code">code</p>
            <p class="menu-btn" data-btn="error">error</p>
            <p class="menu-btn" data-btn="request">request</p>
            <p class="menu-btn" data-btn="header">header</p>
        </div>
    </div>

    <div class="sections">
        <section class="debug">
            <div class="box">
                <div class="box-debug" id="code">
                    <section class="code">
                        <?php echo $code ?>
                    </section>
                </div>

                <div class="box-debug display-none" id="error">
                    @if($error_all)
                    <p class="none-border">
                    <pre>{{ var_dump($error_all) }}</pre>
                    </p>
                    @endif
                </div>

                <div class="box-debug display-none" id="request">
                    <p>METHOD =>
                        <?php echo $method ?>
                    </p>
                    <?php if ($request_all != false) : ?>
                        <?php foreach ($request_all as $key => $value) : ?>
                            <p>
                                <?php echo $key ?> =>
                                <?php htmlspecialchars(print_r($value)) ?>
                            </p>
                        <?php endforeach ?>
                    <?php else : ?>
                        <p>[]</p>
                    <?php endif ?>
                </div>

                <div class="box-debug display-none" id="header">
                    <?php if ($headers) : ?>
                        <?php foreach ($headers as $key => $value) : ?>
                            <p>
                                <?php echo $key ?> =>
                                <?php echo $value ?>
                            </p>

                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
        </section>
    </div>

    <style>
        @media (max-width:700px) {
            .error_message p {
                font-size: 12px !important;
            }
        }

        @media (max-width:1100px) {
            .error_message p {
                text-align: unset !important;
            }
        }

        body {
            height: 100vh;
            background: #fbfbfb;
            display: flex;
            flex-direction: column;
            background-color: #141414;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
            scroll-behavior: smooth;
            font-family: 'Roboto Mono', monospace;
            color: rgb(112 112 112);
            word-break: break-all;
        }

        pre {
            border: none;
            font-size: 14px;
        }

        ::-webkit-scrollbar {
            width: 6px;
            background: none;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #dbdbdb2b;
            border-radius: 8px;
        }

        .sections {
            max-height: 100%;
            overflow: overlay;
            display: flex;
        }

        .display-none {
            display: none !important;
        }

        .none-border {
            border: unset !important;
        }

        .code {
            overflow-y: auto;
            overflow-x: hidden !important;
            padding: 6px 0px;
            height: 100%;
            width: 100%;
        }

        .code p {
            padding: 2px;
            display: flex;
            align-items: center;
            font-size: 12px !important;
            color: #939393;
            border-bottom: none !important;
            line-height: 1;
        }

        /* start menu */
        .menu {
            display: flex;
            padding: 8px 10px;
            background: #dbdbdb2b;
        }

        .menu-btn-active {
            color: #d2721e !important;
            /* font-weight: bolder; */
        }

        .menu-btn {
            cursor: pointer !important;
            font-size: 14px;
            cursor: pointer;
            color: #c9c9c9;
            padding: 0px 8px;
        }

        .menu-btn:hover {
            color: #d2721e !important;
        }

        /* end menu */

        /* debug */
        .debug {
            justify-content: center;
            display: flex;
            width: 100%;
            position: relative;
        }

        .header-menu {
            width: 100%;
            z-index: 900;
        }

        .box {
            max-height: 100%;
            width: 100%;
            overflow-x: auto;
        }

        .box-debug {
            padding: 5px;
            overflow: auto;
            overflow-x: auto;
        }

        .box-debug p {
            font-size: 13px;
            color: #949494;
            padding: 3px 8px;
            border-bottom: 1px solid #d1d1d1;
        }

        .box-debug p:last-child {
            border-bottom: unset !important;
        }

        .error_message {
            background-color: #68686817;
            padding: 8px;
        }

        .error_message p {
            font-size: 14px;
            text-align: center;
            color: #d2721e;
        }

        .line-number {
            color: #7e7e7e;
            font-size: 12px;
            min-width: 30px;
            margin-left: 8px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .error_message_link {
            color: #13b313 !important;
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
        }

        .error-line {
            background: #08080859;
            color: #13b313 !important;
        }

        .box-debug p:hover {
            background-color: #08080859 !important;
            cursor: pointer;
        }
    </style>

    <script>
        document.getElementById('error_line').scrollIntoView();

        let buttons = document.querySelectorAll('.menu-btn');
        let boxs = document.querySelectorAll('.box-debug');

        buttons.forEach((button) => {
            button.addEventListener('click', (e) => {
                boxs.forEach((box) => {
                    box.classList.add('display-none');
                });

                buttons.forEach((button) => {
                    button.classList.remove('menu-btn-active');
                });

                let button = e.target;
                let tab = button.dataset.btn;

                button.classList.add('menu-btn-active');
                document.getElementById(tab).classList.remove('display-none');
            });
        });
    </script>
</body>

</html>