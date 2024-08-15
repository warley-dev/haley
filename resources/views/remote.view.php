<body>
    <video id="remote" controls></video>

    <script>
        class SignalServer {
            constructor(channel) {
                this.socket = new WebSocket("ws://localhost:2020");

                this.socket.addEventListener("open", () => {
                    this.postMessage({
                        type: "join-channel",
                        channel
                    });
                });

                this.socket.addEventListener("message", (e) => {
                    console.log(e)

                    const object = JSON.parse(e.data);
                    if (object.type === "connection-established") console.log("connection established");
                    else if (object.type === "joined-channel") console.log("Joined channel: " + object.channel);
                    else this.onmessage({
                        data: object
                    });
                });
            }

            onmessage(e) {}

            postMessage(data) {
                this.socket.send(JSON.stringify(data));
            }
        }
    </script> <!-- new change -->
    <script>
        const remote = document.querySelector("video#remote");
        let peerConnection;

        const channel = new SignalServer("stream-video"); // <- new change
        channel.onmessage = e => {
            if (e.data.type === "icecandidate") {
                peerConnection?.addIceCandidate(e.data.candidate);
            } else if (e.data.type === "offer") {
                console.log("Received offer");
                handleOffer(e.data);
            }
        }

        function handleOffer(offer) {
            const config = {};

            peerConnection = new RTCPeerConnection(config);

            peerConnection.addEventListener("track", e => remote.srcObject = e.streams[0]);
            peerConnection.addEventListener("icecandidate", e => {
                let candidate = null;
                if (e.candidate !== null) {
                    candidate = {
                        candidate: e.candidate.candidate,
                        sdpMid: e.candidate.sdpMid,
                        sdpMLineIndex: e.candidate.sdpMLineIndex,
                    };
                }
                channel.postMessage({
                    type: "icecandidate",
                    candidate
                });
            });

            peerConnection.setRemoteDescription(offer).then(() => peerConnection.createAnswer()).then(async answer => {
                await peerConnection.setLocalDescription(answer);
                console.log("Created answer, sending...");
                channel.postMessage({
                    type: "answer",
                    sdp: answer.sdp,
                });
            });
        }
    </script>
</body>
