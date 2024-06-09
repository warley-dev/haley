<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Server 2</title>
    </head>

    <body>
        <h1>Server 2</h1>

        <div id="chat">

        </div>

        <form id="form">
            <textarea style="width: 100%" rows="30" required placeholder="mensage...." id="mesage"></textarea>
            <button type="submit">Enviar</button>
        </form>

        <script>
            var wsServer = 'ws://framework:6000';
            var websocket = new WebSocket(wsServer);

            var form = document.getElementById('form');
            var mesage = document.getElementById('mesage');
            var chat = document.getElementById('chat');

            websocket.onopen = function(evt) {
                console.log("Connected to WebSocket server.");
            };

            websocket.onclose = function(evt) {
                console.log("Disconnected");
            };

            websocket.onmessage = function(evt) {
                console.log('Retrieved data from server: ' + evt.data);

                var element = document.createElement('p');
                element.innerHTML = evt.data;

                chat.appendChild(element);
            };

            websocket.onerror = function(evt, e) {
                console.log('Error occurred: ' + evt.data);
            };

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                websocket.send(mesage.value);
            });
        </script>
    </body>

</html>
