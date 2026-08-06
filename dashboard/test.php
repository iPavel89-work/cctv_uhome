<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FLV Player</title>

    <script src="https://cdn.jsdelivr.net/npm/flv.js/dist/flv.min.js"></script>
</head>
<body>

<video
        id="video"
        controls
        autoplay
        muted
        playsinline
        style="width:100%">
</video>

<script>
    const video = document.getElementById('video');

    if (flvjs.isSupported()) {

        const player = flvjs.createPlayer({
            type: 'flv',
            url: 'https://cam1.uhome.kz:18080/rtsp/93170/7089cf6c70bd61e37695',
            isLive: true
        });

        player.attachMediaElement(video);
        player.load();

        player.play().catch(console.error);
    }
</script>

</body>
</html>