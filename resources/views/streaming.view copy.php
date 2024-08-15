<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screen Capture Example</title>
</head>

<body>
    <div>
        <button id="startButton">Start Recording</button>
        <button id="stopButton" disabled>Stop Recording</button>
    </div>

    <video id="recordedVideo" width="50%" height="50%" controls autoplay></video>
    <video id="streaming" width="600" height="400" controls autoplay></video>

    <script>
        const startButton = document.getElementById('startButton');
        const stopButton = document.getElementById('stopButton');
        const recordedVideo = document.getElementById('recordedVideo');
        const streaming = document.getElementById('streaming');

        let stream_blobs = [];
        let stream_blob_count = 0;

        streaming.autoplay = true;
        streaming.controls = true;
        streaming.dataset.current = 0;


        var mediaSource = new MediaSource();
        streaming.src = URL.createObjectURL(mediaSource);

        let sourceBuffer;

        // websocket
        var websocket = new WebSocket("ws://framework:2020");

        websocket.onopen = function(event) {
            console.log('open');
        }

        websocket.onmessage = (event) => {
            var data = JSON.parse(JSON.parse(event.data));
            // console.log(data);


            var blob = arrayBufferToBlob(data.blob.data, data.blob.type);


            // const blob = new Blob(data.blob.data, {
            //     type: data.blob.type
            // });

            // console.log(blob);
            streaming.src = window.URL.createObjectURL(blob);
            // setTimeout(() => {
            //     streaming.play();
            // },10)
        }

        websocket.onerror = function(event) {
            console.log('error:', event);
        };

        websocket.onclose = function(event) {
            console.log('close:', event);
        };
        // end websocket

        streaming.addEventListener('ended', function() {
            streamPlay();
        });

        async function streamInit() {
            mediaStream = await navigator.mediaDevices.getDisplayMedia({
                video: true,
                audio: true // Capturar áudio também
            });

            recordedVideo.srcObject = mediaStream;
            const mediaRecorder = new MediaRecorder(mediaStream);

            mediaRecorder.start();

            mediaRecorder.onstart = function() {
                if (mediaRecorder.state === "recording") {

                    setTimeout(() => {
                        mediaRecorder.stop();
                    }, 200);
                }
            }

            mediaRecorder.onstop = function(event) {
                mediaRecorder.start();
            };

            mediaRecorder.ondataavailable = function(event) {
                console.log('aadfsdfsdfsa')

                mediaSource.addEventListener('sourceopen', function() {
                    // Criar um buffer de origem
                    if (!sourceBuffer) sourceBuffer = mediaSource.addSourceBuffer(event.type);
                    console.log('a')
                    sourceBuffer.appendBuffer(event.data);



                });

                if (sourceBuffer) sourceBuffer.appendBuffer(event.data);
                // resizeVideoBlob(event.data, 640, 480, function(resizedBlob) {
                //     //   streaming.srcObject = resizedBlob;
                //     //   streaming.play();

                //     blobToArrayBuffer(resizedBlob).then(arrayBuffer => {


                //         var send = JSON.stringify({
                //             blob: {
                //                 data: Array.from(new Uint8Array(arrayBuffer)), // Convertendo o array buffer para um array comum
                //                 type: event.data.type
                //             }
                //         }, null, 0);

                //         websocket.send(send);
                //     });
                // });
            };
        }

        function streamPlay() {
            console.log('next');

            var current_key = parseInt(streaming.dataset.current);
            var next_source = streaming.querySelector(`[data-key="${(current_key + 1)}"]`);

            if (next_source) {
                streaming.dataset.current = next_source.dataset.key;
                streaming.src = next_source.src;
                streaming.play();
            }
        }

        function blobToArrayBuffer(blob) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();

                reader.onload = () => {
                    resolve(reader.result);
                };

                reader.onerror = reject;
                reader.readAsArrayBuffer(blob);
            });
        }

        function arrayBufferToBlob(arrayBuffer, type) {
            return new Blob([new Uint8Array(arrayBuffer)], {
                type: type
            });
        }

        function resizeVideoBlob(blob, maxWidth, maxHeight, callback) {
            var video = document.createElement('video');
            var url = URL.createObjectURL(blob);
            video.src = url;

            video.addEventListener('loadedmetadata', function() {
                var width = video.videoWidth;
                var height = video.videoHeight;
                var ratio = Math.min(maxWidth / width, maxHeight / height);

                var canvas = document.createElement('canvas');
                canvas.width = width * ratio;
                canvas.height = height * ratio;

                var ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(resizedBlob) {
                    // Liberar o URL do objeto
                    URL.revokeObjectURL(url);
                    // Chamar a função de retorno com o Blob redimensionado
                    callback(resizedBlob);
                }, blob.type);
            });

        }

        startButton.addEventListener('click', () => {
            streamInit()
        })
    </script>

    {{-- <script>
            const startButton = document.getElementById('startButton');
            const stopButton = document.getElementById('stopButton');
            const recordedVideo = document.getElementById('recordedVideo');

            let mediaRecorder;
            let mediaStream;
            // let recordedChunks = [];
            let streamingBlob = [];

            startButton.addEventListener('click', startRecording);
            stopButton.addEventListener('click', stopRecording);

            // socket
            var websocket = new WebSocket("ws://framework:2019");

            // websocket.binaryType = "blob";

            websocket.onopen = function(event) {
                console.log('open');
            }

            var playerStream = document.getElementById('streaming');

            // playerStream.addEventListener('loadeddata', function() {
            //     playerStream.play();
            // });

            // playerBlob = null;

            var chunks = [];

            websocket.onmessage = (event) => {
                // var data = JSON.parse(JSON.parse(event.data));
                // // console.log(data);



                // const blob = new Blob(data.stream.blob, {
                //     type: data.stream.blobType
                // });

                // console.log(blob);


                // playerStream.src = window.URL.createObjectURL(blob);
                // playerStream.play();
            }

            websocket.onerror = function(event) {
                console.log('error:', event);
            };

            websocket.onclose = function(event) {
                console.log('close:', event);
            };


            // ...

            async function startRecording() {
                try {
                    mediaStream = await navigator.mediaDevices.getDisplayMedia({
                        video: true,
                        audio: true // Capturar áudio também
                    });

                    recordedVideo.srcObject = mediaStream;

                    const mediaRecorder = new MediaRecorder(mediaStream);



                    mediaRecorder.onstart = function() {
                        if (mediaRecorder.state === "recording") {

                            // setTimeout(() => {
                            // setTimeout(() => {


                            setTimeout(() => {
                                mediaRecorder.stop();
                            }, 100);


                        }
                    }

                    mediaRecorder.onstop = function(event) {
                        setTimeout(() => {
                            mediaRecorder.start();
                        }, 90);
                    };

                    mediaRecorder.ondataavailable = function(event) {

                        // chunks.append(event.data);

                        // if (first == true) {
                        // setTimeout(() => {
                        // adicionarDadosAoBlob(event.data);
                        // }, 300);
                        // } else {
                        // first = false;
                        // setTimeout(() => {
                        adicionarDadosAoBlob(event.data);
                        // },50)

                        // }

                        // resizeVideoBlob(event.data, 640, 480, function(resizedBlob) {
                        //     // Aqui você pode enviar o Blob redimensionado para o backend
                        //     // console.log('Blob redimensionado:', resizedBlob);
                        //     // playerStream.addEventListener('loadeddata', function() {
                        //     //     playerStream.play();
                        //     // });



                        //     // playerStream.src = window.URL.createObjectURL(resizedBlob);

                        //     // blobToArrayBuffer(resizedBlob).then(arrayBuffer => {
                        //     //     var send = JSON.stringify({
                        //     //         // stream: {
                        //     //         //     blobData: Array.from(new Uint8Array(arrayBuffer)), // Convertendo o array buffer para um array comum
                        //     //         //     blobType: event.data.type
                        //     //         // }

                        //     //         stream: resizedBlob
                        //     //     }, null, 0);

                        //     //     // var send = JSON.stringify(Array.from(new Uint8Array(arrayBuffer)) , null, 0);

                        //     //     websocket.send(send);
                        //     //     // console.log(send)

                        //     // })
                        // });



                    };

                    //   setTimeout(() => {
                    mediaRecorder.start();
                    //   }, 400);

                    startButton.disabled = true;
                    stopButton.disabled = false;
                } catch (err) {
                    console.log('error' + err);
                }
            }

            function stopRecording() {
                if (mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                    mediaStream.getTracks().forEach(track => track.stop());
                    startButton.disabled = false;
                    stopButton.disabled = true;
                }
            }

            function blobToArrayBuffer(blob) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();

                    reader.onload = () => {
                        resolve(reader.result);
                    };

                    reader.onerror = reject;
                    reader.readAsArrayBuffer(blob);
                });
            }

            function arrayBufferToBlob(arrayBuffer, type) {
                return new Blob([new Uint8Array(arrayBuffer)], {
                    type: type
                });
            }

            function resizeVideoBlob(blob, maxWidth, maxHeight, callback) {
                // var video = document.createElement('video');
                // var url = URL.createObjectURL(blob);
                // video.src = url;

                // video.addEventListener('loadedmetadata', function() {
                //     var width = video.videoWidth;
                //     var height = video.videoHeight;
                //     var ratio = Math.min(maxWidth / width, maxHeight / height);

                //     var canvas = document.createElement('canvas');
                //     canvas.width = width * ratio;
                //     canvas.height = height * ratio;

                //     var ctx = canvas.getContext('2d');
                //     ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                //     canvas.toBlob(function(resizedBlob) {
                //         // Liberar o URL do objeto
                //         URL.revokeObjectURL(url);
                //         // Chamar a função de retorno com o Blob redimensionado
                //         callback(resizedBlob);
                //     }, blob.type);
                // });


                var video = document.createElement('video');
                var url = URL.createObjectURL(blob);
                video.src = url;

                video.addEventListener('loadedmetadata', function() {
                    var width = video.videoWidth;
                    var height = video.videoHeight;
                    var ratio = Math.min(maxWidth / width, maxHeight / height);

                    var canvas = document.createElement('canvas');
                    canvas.width = width * ratio;
                    canvas.height = height * ratio;

                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    canvas.toBlob(function(resizedBlob) {
                        // Liberar o URL do objeto
                        URL.revokeObjectURL(url);
                        // Chamar a função de retorno com o Blob redimensionado
                        callback(resizedBlob);
                    }, blob.type);
                });

            }

            // Variável global para armazenar os blobs anteriores
            let blobsAnteriores = [];
            // Variável global para armazenar o tamanho total dos blobs anteriores
            let tamanhoTotalAnterior = 0;

            // Função para adicionar dados ao blob atual
            function adicionarDadosAoBlob(dadosNovos) {
                // Criar um novo blob com os dados novos
                const blobNovo = new Blob([dadosNovos], {
                    type: 'video/mp4'
                }); // Substitua 'video/mp4' pelo tipo MIME correto do seu blob

                if (blobsAnteriores.length === 0) {
                    // Se não houver blobs anteriores, o blob atual será apenas o novo blob
                    blobsAnteriores.push(blobNovo);
                    tamanhoTotalAnterior += blobNovo.size;
                } else {
                    // Se houver blobs anteriores, criar um novo blob que contém todos os blobs anteriores e o novo blob
                    const blobs = [blobsAnteriores[blobsAnteriores.length - 1], blobNovo];
                    blobsAnteriores.push(new Blob(blobs, {
                        type: blobsAnteriores[0].type
                    }));
                    tamanhoTotalAnterior += blobNovo.size;
                }

                // Atualizar o player de vídeo com o novo blob
                const videoElement = document.getElementById('streaming'); // Substitua 'meuPlayerDeVideo' pelo id do seu elemento de vídeo
                if (videoElement.paused) {
                    // Se o vídeo estiver pausado, atribuir o blob atual diretamente ao src
                    videoElement.src = URL.createObjectURL(blobsAnteriores[blobsAnteriores.length - 1]);
                } else {
                    // Se o vídeo estiver reproduzindo, adicionar o novo blob à fonte de mídia do vídeo
                    const mediaSource = new MediaSource();
                    mediaSource.addEventListener('sourceopen', () => {
                        const sourceBuffer = mediaSource.addSourceBuffer('video/mp4; codecs="avc1.42E01E, mp4a.40.2"'); // Substitua pelo codec do seu vídeo

                        try {
                            sourceBuffer.appendBuffer(blobNovo);
                        } catch (error) {
                            // blobsAnteriores = [];
                            // tamanhoTotalAnterior = 0;

                            adicionarDadosAoBlob(dadosNovos)
                        }
                    });
                    // videoElement.src = URL.createObjectURL(mediaSource);
                }
            }
        </script> --}}
</body>

</html>