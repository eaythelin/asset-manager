const btn = document.getElementById('download-qr-btn');

if (btn) {
    btn.addEventListener('click', function() {
        const assetName = btn.dataset.assetName;
        const assetCode = btn.dataset.assetCode;
        const svg = document.querySelector('#qr-container svg');
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        const qrSize = 300;
        const labelHeight = 50;
        canvas.width = qrSize
        canvas.height = qrSize + labelHeight;

        const svgData = new XMLSerializer().serializeToString(svg);
        const svgBlob = new Blob([svgData], { type: 'image/svg+xml' });
        const url = URL.createObjectURL(svgBlob);

        img.onload = function () {
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.drawImage(img, 0, 0, qrSize, qrSize);

            ctx.fillStyle = '#000000';
            ctx.textAlign = 'center';
            ctx.font = 'bold 16px sans-serif';
            ctx.fillText(assetName, canvas.width / 2, qrSize + 22);

            ctx.font = '14px sans-serif';
            ctx.fillText(assetCode, canvas.width / 2, qrSize + 42);

            const a = document.createElement('a');
            a.download = assetName + '-qr.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
            URL.revokeObjectURL(url);
        };

        img.src = url;
    });
}
