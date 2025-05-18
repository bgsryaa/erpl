<div id="reader" style="width:300px"></div>
<button id="start-scan">Mulai Scan Barcode</button>
 <script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scannedBarcode = null;

document.getElementById('start-scan').onclick = function() {
    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        (decodedText, decodedResult) => {
            scannedBarcode = decodedText;
            html5QrCode.stop();
            ambilLokasiDanKirim();
        },
        (errorMessage) => {
            // ignore scan errors
        }
    );
};

function ambilLokasiDanKirim() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Kirim barcode + lokasi ke backend
            fetch('/api/attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer {{ auth()->user()->api_token ?? "" }}' // sesuaikan jika pakai sanctum/breeze
                },
                body: JSON.stringify({
                    barcode: scannedBarcode,
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                })
            })
            .then(response => response.json())
            .then(data => alert('Absensi berhasil!'))
            .catch(err => alert('Gagal absen'));
        }, function() {
            alert('Gagal mendapatkan lokasi!');
        });
    } else {
        alert("Geolocation tidak didukung browser Anda.");
    }
}
</script>