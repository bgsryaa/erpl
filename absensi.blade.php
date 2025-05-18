<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Absensi Mahasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tambahkan ini di bagian <head> atau sebelum </body> -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<!-- Tempatkan di atas/di bawah form -->
<div id="barcode-reader" style="width: 300px"></div>
<button type="button" onclick="startScanner()">Scan Barcode</button>

<form>
    <input type="text" id="barcode" name="barcode" placeholder="Scan barcode" required>
    <!-- Latitude, Longitude field seperti sebelumnya -->
</form>

<script>
function startScanner() {
  const html5QrCode = new Html5Qrcode("barcode-reader");
  html5QrCode.start(
    { facingMode: "environment" },
    {
      fps: 10,
      qrbox: 250
    },
    (decodedText, decodedResult) => {
      document.getElementById('barcode').value = decodedText;
      html5QrCode.stop();
      document.getElementById('barcode-reader').innerHTML = '';
    },
    (errorMessage) => {
      // Ignore errors
    }
  );
}
</script>
    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #48C9B0;
            --background: #f5f8fa;
            --white: #fff;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, var(--primary) 50%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }
        .container {
            background: var(--white);
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 1.3rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.11);
            width: 370px;
            max-width: 95vw;
            text-align: center;
        }
        h2 {
            color: var(--primary);
            margin-bottom: 1.2rem;
            font-weight: 600;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: 500;
            color: #444;
            margin: 0.6rem 0 0.15rem 0;
            text-align: left;
        }
        input[type="text"], input[type="number"] {
            padding: 0.7rem;
            border: 1.5px solid #dcdcdc;
            border-radius: 0.6rem;
            margin-bottom: 0.9rem;
            background: #f7f7ff;
            font-size: 1.05rem;
            outline: none;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border: 1.5px solid var(--primary);
        }
        button[type="submit"] {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border: none;
            color: var(--white);
            padding: 0.9rem;
            font-size: 1.1rem;
            border-radius: 0.6rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.6rem;
            transition: background 0.2s, transform 0.14s;
        }
        button[type="submit"]:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 2px 12px 0 rgba(76,99,255,0.13);
        }
        .result {
            margin-top: 1.2rem;
            min-height: 2.1em;
            color: #fff;
            background: linear-gradient(90deg, #48C9B0 40%, #6C63FF 100%);
            padding: 0.6em 1em;
            border-radius: 0.5em;
            box-shadow: 0 2px 6px 0 rgba(76,99,255,0.09);
            font-size: 1em;
            display: none;
        }
        .logout-btn {
            margin-top: 2.2rem;
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 0.7rem 1.1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 1px 4px 0 rgba(231,76,60,0.15);
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        @media (max-width: 480px) {
            .container {
                padding: 1.2rem;
                border-radius: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Absensi Mahasiswa</h2>
        <form id="absensiForm" autocomplete="off">
            <label for="barcode">Barcode</label>
            <input type="text" id="barcode" name="barcode" placeholder="Scan barcode" required>

            <label for="latitude">Latitude</label>
            <input type="number" step="any" id="latitude" name="latitude" placeholder="Contoh: -6.200000" required>

            <label for="longitude">Longitude</label>
            <input type="number" step="any" id="longitude" name="longitude" placeholder="Contoh: 106.816666" required>

            <button type="submit">Submit Absensi</button>
        </form>
        <div class="result" id="result"></div>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>
    <script>
    // Autofill lokasi jika user mengizinkan
    window.onload = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                document.getElementById('latitude').value = pos.coords.latitude;
                document.getElementById('longitude').value = pos.coords.longitude;
            });
        }
    };

    document.getElementById('absensiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        let token = localStorage.getItem('api_token');
        let data = {
            barcode: this.barcode.value,
            latitude: this.latitude.value,
            longitude: this.longitude.value
        };
        let resultDiv = document.getElementById('result');
        resultDiv.style.display = 'block';
        resultDiv.textContent = 'Loading...';
        resultDiv.className = 'result';

        try {
            let response = await fetch('/api/attendance', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(data)
            });
            let res = await response.json();
            if (response.ok && res.data) {
                resultDiv.textContent = 'Absensi berhasil!';
                resultDiv.className = 'result';
                document.getElementById('absensiForm').reset();
            } else {
                resultDiv.textContent = res.message || 'Gagal absensi';
            }
        } catch (err) {
            resultDiv.textContent = 'Terjadi error: ' + err;
        }
    });

    function logout() {
        localStorage.removeItem('api_token');
        localStorage.removeItem('user_role');
        localStorage.removeItem('user_name');
        window.location.href = '/';
    }
    </script>
    @extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Kehadiran (Absensi)</h2>
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Data User yang Sudah Absen</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Tanggal Absen</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($absensis as $absensi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $absensi->user->name ?? '-' }}</td>
                        <td>{{ $absensi->user->email ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($absensi->created_at)->format('d M Y H:i') }}</td>
                        <td>{{ $absensi->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data absensi.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
</body>
</html>