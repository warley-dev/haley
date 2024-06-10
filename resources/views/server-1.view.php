<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Server 1</title>
    </head>

    <body>
        <style>
            .hidden {
                display: none !important;
            }

            #player {
                width: 100%;
                height: 400px;
            }
        </style>

        <h1>Server 1 <span id="usuarios"></span></h1>

        <div id="chat">

        </div>

        <form id="form">
            <textarea style="width: 100%" rows="30" required id="mensagem"></textarea>
            <input type="text" id="nome">
            <button type="submit">Enviar</button>
            <button id="startCapture" type="button">Capturar Tela</button>
        </form>

        <video autoplay id="player" controls></video>

        <script>
            var ws_chat = new WebSocket('ws://{{ env('SERVER_ALIAS') }}:5006/helo/word/teste');
            var form = document.getElementById('form');
            var mensagem = document.getElementById('mensagem');
            var chat = document.getElementById('chat');
            var nome = document.getElementById('nome');
            var usuarios = document.getElementById('usuarios');
            var first = true;

            ws_chat.onopen = function() {
                console.log('Connected to WebSocket server');
            };

            ws_chat.onclose = function(event) {
                alert('Conexão encerrada');
            };

            ws_chat.onmessage = function(event) {
                console.log(event);

                try {
                    var data = JSON.parse(event.data);

                    if (data.usuarios) usuarios.innerHTML = '- Usuários conectados: ' + data.usuarios;

                    if (data.mensagem) {
                        var element = document.createElement('p');
                        element.innerHTML = data.nome + ': ' + data.mensagem;

                        chat.appendChild(element);
                    }
                } catch (error) {
                    // console.log(error);
                }
            };

            ws_chat.onerror = function(event) {
                alert('Falha na conexão');
            };

            mensagem.addEventListener('keydown', function(e) {
                if (e.keyCode !== 13) return;

                e.preventDefault();

                sendMessage();
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                sendMessage();
            });

            function sendMessage() {
                if (first) {

                    if (!nome.value) {
                        alert('Preencha o campo nome');
                        return;
                    }

                    nome.classList.add('hidden');
                    first = false;
                }

                ws_chat.send(JSON.stringify({
                    mensagem: mensagem.value,
                    nome: nome.value
                }));

                mensagem.value = '';
            }

            // stream
            var ws_stream = new WebSocket('ws://{{ env('SERVER_ALIAS') }}:5007');

            const startCaptureButton = document.getElementById('startCapture');
            const videoPlayer = document.getElementById('player');

            console.log(startCaptureButton, videoPlayer);

            ws_stream.onmessage = function(event) {
                // videoPlayer.srcObject = stream;
            };


            startCaptureButton.addEventListener('click', async () => {
                try {
                    const stream = await navigator.mediaDevices.getDisplayMedia({
                        video: true,
                        audio: true
                    });

                    videoPlayer.srcObject = stream;

                    console.log(stream);

                    // ws_chat.send(stream);
                } catch (error) {
                    console.error('Error accessing screen capture:', error);
                }
            });

            // if (socket.readyState === WebSocket.OPEN) {
            //     console.log('WebSocket está conectado.');
            // } else if (socket.readyState === WebSocket.CONNECTING) {
            //     console.log('WebSocket está se conectando.');
            // } else if (socket.readyState === WebSocket.CLOSING) {
            //     console.log('WebSocket está se fechando.');
            // } else if (socket.readyState === WebSocket.CLOSED) {
            //     console.log('WebSocket está fechado.');
            // }
        </script>
    </body>

</html>
