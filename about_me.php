
<?php
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role  = strtolower($_SESSION['role'] ?? '');
$is_wk = $_SESSION['is_wali_kelas'] ?? false;

if (!in_array($role, ['admin', 'guru'])) {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama_user'] ?? 'Pengguna';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Me</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: white;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background: #0d6efd;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .profile-card {
            max-width: 800px;
            margin: 10px 0;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">

    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item mb-1">
            <a href="dashboard.php">Dashboard</a>
        </li>

        <?php if ($role === 'admin'): ?>

            <li class="nav-item mb-1">
                <a href="kelola_guru.php">Kelola Guru</a>
            </li>

            <li class="nav-item mb-1">
                <a href="kelola_siswa.php">Kelola Siswa</a>
            </li>

            <li class="nav-item mb-1">
                <a href="kelola_kelas.php">Kelola Kelas</a>
            </li>

            <li class="nav-item mb-1">
                <a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a>
            </li>

            <li class="nav-item mb-1">
                <a href="penempatan_siswa.php">Penempatan Siswa</a>
            </li>

            <li class="nav-item mb-1">
                <a href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
            </li>

            <li class="nav-item mb-1">
                <a href="kelola_kategori_pelanggaran.php">
                    Kelola Kategori Pelanggaran
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="kelola_jenis_pelanggaran.php">
                    Kelola Jenis Pelanggaran
                </a>
            </li>

        <?php elseif ($role === 'guru'): ?>

            <li class="nav-item mb-1">
                <a href="catat_pelanggaran.php">Catat Pelanggaran</a>
            </li>

            <li class="nav-item mb-1">
                <a href="tindakan.php">Tindakan</a>
            </li>

            <li class="nav-item mb-1">
                <a href="riwayat.php">Riwayat</a>
            </li>

            <li class="nav-item mb-1">
                <a href="rekap_poin.php">Rekap Poin</a>
            </li>

        <?php endif; ?>

        <!-- MENU LAPORAN -->
        <?php if (
            $role === 'admin' ||
            $role === 'guru' ||
            $is_wk == true ||
            $is_wk == 1 ||
            $is_wk == '1'
        ): ?>

            <li class="nav-item mb-1">
                <a href="laporan.php">
                    Laporan
                </a>
            </li>

        <?php endif; ?>

        <!-- ABOUT ME -->
        <li class="nav-item mb-1">
            <a href="about_me.php" class="active">About Me</a>
        </li>

    </ul>

    <hr>

    <a href="logout.php" class="text-danger"
       onclick="return confirm('Yakin ingin keluar?')">
        Logout
    </a>

</div>

<!-- KONTEN UTAMA -->
<div class="main-content">

    <h2 class="mb-3">About Me</h2>

    <hr>

    <div class="card shadow-sm profile-card">

        <div class="card-body text-center p-4">

            <img src="Foto.jpeg"
                 alt="Foto Profil"
                 class="rounded-circle mb-3"
                 width="150"
                 height="150"
                 style="object-fit: cover;">

            <h3>Rani Meilani</h3>

            <p class="text-muted">
                Informasi Pembuat Aplikasi Pelanggaran Siswa
            </p>

            <hr>

            <h3 class="text-start">Tentang Aplikasi</h3>

            <hr>

            <p class="text-start">
                Aplikasi <strong>Pelanggaran Siswa</strong> merupakan sistem
                informasi yang dibuat untuk membantu sekolah mengelola data
                siswa, guru, kelas, tahun ajaran, serta data pelanggaran siswa.
            </p>

            <hr>

            <h4 class="text-start">Identitas</h4>

            <hr>

            <div class="text-start">
                <p><strong>Nama:</strong> Rani Meilani</p>
                <p><strong>Sekolah:</strong> SMK Muhammadiyah</p>
                <p><strong>Jurusan:</strong> Rekayasa Perangkat Lunak</p>
                <p><strong>Tujuan:</strong> Memenuhi tugas UKK 2026</p>
            </div>

        </div>

    </div>

</div>

</body>
</html>
