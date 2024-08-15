<body>
    <video id="local" autoplay muted></video>
    <button onclick="start(this)">start video</button>
    <button id="stream" onclick="stream(this)" disabled>stream video</button>

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
    </script>
    <script>
        const local = document.querySelector("video#local");
        let peerConnection;

        const channel = new SignalServer("stream-video"); // <- new change
        channel.onmessage = e => {
            if (e.data.type === "icecandidate") {
                peerConnection?.addIceCandidate(e.data.candidate);
            } else if (e.data.type === "answer") {
                console.log("Received answer");
                peerConnection?.setRemoteDescription(e.data);
            }
        }

        // function to ask for camera and microphone permission
        // and stream to #local video element
        function start(e) {
            e.disabled = true;
            document.getElementById("stream").disabled = false; // enable the stream button
            //   navigator.mediaDevices.getUserMedia({ audio: true, video: true })
            //     .then((stream) => local.srcObject = stream);


            navigator.mediaDevices.getDisplayMedia({
                audio: true,
                // video: {
                //     width: ,
                //     height: 1080,
                //     // frameRate: 30
                // }
            }).then((stream) => {
                local.srcObject = stream;
            });
        }

        function stream(e) {
            e.disabled = true;

            const config = {};
            peerConnection = new RTCPeerConnection(config); // local peer connection

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

            local.srcObject.getTracks().forEach(track => peerConnection.addTrack(track, local.srcObject));

            peerConnection.createOffer({
                    offerToReceiveAudio: true,
                    offerToReceiveVideo: true
                })
                .then(async offer => {
                    await peerConnection.setLocalDescription(offer);
                    console.log("Created offer, sending...");
                    channel.postMessage({
                        type: "offer",
                        sdp: offer.sdp
                    });
                });
        }
    </script>
</body>
