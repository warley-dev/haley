<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>

        <script>
            if ('MediaSource' in window && MediaSource.isTypeSupported('video/mp4; codecs="avc1.42E01E, mp4a.40.2"')) {
                console.log('soupported');

                let source = new MediaSource();

                source.onsourceopen = (event) => {
                    console.log('source open', event);
                }

                source.onsourceclose = (event) => {
                    console.log('source close', event);
                }

                source.addEventListener('sourceopen', (event) => {
                    console.log('source open', event);

                    let player = document.getElementById('player');
                    let sourceBuffer = source.addSourceBuffer('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');

                    player.srcObject = source;

                    // this.ws_stream.onmessage = (event) => {
                    //     const data = new Uint8Array(event.data);
                    //     sourceBuffer.appendBuffer(data);
                    // }


                });

            } else {
                console.log('not supported');
            }
        </script>

    </body>

</html>
