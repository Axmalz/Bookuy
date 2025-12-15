<!DOCTYPE html>
<html lang="id">
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookuy</title>

    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memuat Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Konfigurasi Tailwind untuk Font Kustom -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        // Daftarkan 'font-sugo' untuk memanggil font kustom kita
                        'sugo': ['Sugo Pro Display', 'sans-serif'],

                        // Daftarkan 'font-poppins' sebagai default
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <!-- CSS Kustom (Termasuk Font Kustom & Bingkai) -->
    <style>
        /* Memuat file font kustom .ttf Anda dari public/fonts/ */
        @font-face {
            font-family: 'Sugo Pro Display'; /* Kita beri nama ini */

            /* Pastikan nama file di bawah ini SAMA PERSIS
              dengan nama file font yang Anda letakkan di public/fonts/
            */
            src: url('{{ asset('fonts/Sugo-Pro-Classic-Regular-trial.ttf') }}') format('truetype');

            /* 'Paksa' font ini untuk merespons class 'font-bold' */
            font-weight: 700; /* 700 (bold) */
            font-style: normal;
        }

        /* CSS untuk Bingkai Handphone (iPhone 14) */
        body {
            background-color: #1a1a1a; /* Latar belakang gelap agar handphone terlihat */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* DIEDIT: Font default adalah Poppins, BUKAN Inter */
            font-family: 'Poppins', sans-serif;
            padding: 2rem 0;
        }

        .iphone-frame {
            width: 400px; /* Lebar iPhone 14 sekitar 390px */
            height: 850px; /* Tinggi iPhone 14 sekitar 844px */
            background: #111;
            border-radius: 60px; /* Sudut melengkung */
            border: 10px solid #333;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7), inset 0 0 0 2px #000, inset 0 0 0 8px #222;
            padding: 10px;
            position: relative;
            box-sizing: border-box;
        }

        .iphone-screen {
            background: #ffffff;
            width: 100%;
            height: 100%;
            border-radius: 40px; /* Sudut melengkung dalam */
            overflow: hidden; /* Memastikan konten tidak keluar dari layar */
            position: relative; /* DIEDIT: Menjadi 'anchor' untuk Nav Bar */
            display: flex;
            flex-direction: column;
        }

        /* Notch (poni) di atas layar */
        .iphone-notch {
            width: 180px;
            height: 30px;
            background: #111;
            position: absolute;
            top: 0px; /* Posisi pas dari atas */
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0 0 20px 20px;
            z-index: 50;
        }

        /* Konten aplikasi */
        .app-content {
            flex-grow: 1;
            overflow-y: auto; /* Membuat konten bisa di-scroll vertikal */
            overflow-x: hidden; /* FIX: Mencegah scroll horizontal pada halaman utama */
            position: relative;
            width: 100%; /* Pastikan lebar tidak melebihi parent */
        }

        /* Spinner styles (untuk splash screen) */
        .spinner {
            width: 50px;
            height: 50px;
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-top-color: #FFFFFF;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

    </style>

    <!-- Slot untuk CSS tambahan per halaman -->
    @stack('styles')
</head>

<body>

    <!-- Bingkai Handphone -->
    <div class="iphone-frame">
        <!-- Layar Handphone -->
        <div class="iphone-screen">
            <!-- Poni (Notch) -->
            <div class="iphone-notch"></div>

            <!-- DIEDIT: Area Konten yang bisa di-scroll -->
            <div class="app-content">
                @yield('content')
            </div>

            <!-- DIEDIT: Slot BARU untuk Nav Bar (Statis, di luar area scroll) -->
            @stack('navbar')
        </div>
    </div>

    <!-- Slot untuk JS tambahan per halaman -->
    @stack('scripts')
</body>
</html>
