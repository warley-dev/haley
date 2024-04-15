<html>

    <head>
        <style>
            body {
                width: 600px;
                font-family: calibri;
            }

            .error {
                color: #FF0000;
            }

            .chat-connection-ack {
                color: #26af26;
            }

            .chat-message {
                border-bottom-left-radius: 4px;
                border-bottom-right-radius: 4px;
            }

            #btnSend {
                background: #26af26;
                border: #26af26 1px solid;
                border-radius: 4px;
                color: #FFF;
                display: block;
                margin: 15px 0px;
                padding: 10px 50px;
                cursor: pointer;
            }

            #chat-box {
                background: #fff8f8;
                border: 1px solid #ffdddd;
                border-radius: 4px;
                border-bottom-left-radius: 0px;
                border-bottom-right-radius: 0px;
                min-height: 300px;
                padding: 10px;
                overflow: auto;
            }

            .chat-box-html {
                color: #09F;
                margin: 10px 0px;
                font-size: 0.8em;
            }

            .chat-box-message {
                color: #09F;
                padding: 5px 10px;
                background-color: #fff;
                border: 1px solid #ffdddd;
                border-radius: 4px;
                display: inline-block;
            }

            .chat-input {
                border: 1px solid #ffdddd;
                border-top: 0px;
                width: 100%;
                box-sizing: border-box;
                padding: 10px 8px;
                color: #191919;
            }

            #online {
                width: 100%;
                display: flex;
                justify-content: center;
                text-align: center;
                color: #26af26;
                font-size: 16px;
            }

            #logout {
                background: #af2626;
                border: #af2626 1px solid;
                border-radius: 4px;
                color: #FFF;
                display: block;
                margin: 15px 0px;
                padding: 10px 50px;
                cursor: pointer;
            }
        </style>

        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>

        <script>
            function showMessage(messageHTML) {
                $('#chat-box').append(messageHTML);
            }

            var login = true;

            setTimeout(() => {
                var websocket = new WebSocket("ws://framework:9073");

                websocket.onopen = function(event) {
                    // console.log(event);
                    showMessage("<div class='chat-connection-ack'>Conectado!</div>");

                    $('#logout').prop('hidden', false);

                }

                websocket.onmessage = function(event) {

                    console.log(event)
                    var msg = JSON.parse(event.data);



                    if (!msg) return;

                    if (msg.open) document.getElementById('chat-box').innerHTML += `${msg.open}`;
                    if (msg.message) document.getElementById('chat-box').innerHTML += `<div class='chat-connection-ack'>${msg.user}: ${msg.message}</div>`;
                    if (msg.disconnect) document.getElementById('chat-box').innerHTML += `<div class='error'>${msg.disconnect}</div>`;
                    if (msg.online) document.getElementById('online').innerHTML = `<div class='error'>${msg.online} usuários online</div>`;
                };

                websocket.onerror = function(event) {
                    showMessage("<div class='error'>Problem due to some Error</div>");
                };

                websocket.onclose = function(event) {
                    showMessage("<div class='chat-connection-ack'>Connection Closed</div>");
                    document.getElementById('online').innerHTML = `<div class='error'>Desconectado</div>`;
                };

                $('#logout').click(function(e) {
                    e.preventDefault();

                    var logout = JSON.stringify({
                        message: 'close'
                    }, null, 0);

                    websocket.send(logout);
                });

                $('#frmChat').on("submit", function(event) {
                    event.preventDefault();

                    $('#chat-user').attr("type", "hidden");

                    var messageJSON = JSON.stringify({
                        user: $('#chat-user').val(),
                        message: $('#chat-message').val(),
                        login: login
                    }, null, 0);

                      var blob = new Blob([messageJSON], { type: 'text/plain' });
                      console.log(blob.size)

                    websocket.send(messageJSON);

                    login = false;
                });
            }, 100);
        </script>
    </head>

    <body>
        <form name="frmChat" id="frmChat">
            <div id="online"></div>
            <div id="chat-box"></div>

            <input type="text" name="chat-user" id="chat-user" placeholder="Name" class="chat-input" required />
            <input type="text" name="chat-message" id="chat-message" placeholder="Message" class="chat-input chat-message" required />
            <input type="submit" id="btnSend" name="send-chat-message" value="Send">

            <button hidden id="logout" value="1">Deslogar</button>
        </form>
    </body>

</html>
