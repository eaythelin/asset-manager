const btn = document.getElementById('download-qr-btn');

if (btn) {
    btn.addEventListener('click', function() {
        const assetName = btn.dataset.assetName;
        const svg = document.querySelector('#qr-container svg');
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        canvas.width = 300;
        canvas.height = 300;

        const svgData = new XMLSerializer().serializeToString(svg);
        const svgBlob = new Blob([svgData], { type: 'image/svg+xml' });
        const url = URL.createObjectURL(svgBlob);

        img.onload = function () {
            ctx.drawImage(img, 0, 0);
            const a = document.createElement('a');
            a.download = assetName + '-qr.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
            URL.revokeObjectURL(url);
        };

        img.src = url;
    });
}