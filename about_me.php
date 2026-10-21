<?php
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama_user'] ?? 'Administrator Utama';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh; background: #212529; color: white;
            position: fixed; width: 250px; top: 0; left: 0;
            overflow-y: auto; z-index: 1000;
        }
        .sidebar a {
            color: #adb5bd; text-decoration: none; display: block;
            padding: 10px 20px; border-radius: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white; background: #0d6efd;
        }
        .main-content { margin-left: 250px; padding: 20px; }
        
        /* DIUBAH MENJADI RATA KIRI: Menggunakan margin-left: 0 (atau margin: 10px 0) */
        .profile-card { max-width: 800px; margin: 10px 0; }

        .profile-icon {
            width: 100px; height: 100px; border-radius: 50%;
            background: #0d6efd; color: white; display: flex;
            align-items: center; justify-content: center;
            font-size: 45px; margin: auto;
        }
        @media(max-width:768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 12px; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="kelola_guru.php">Kelola Guru</a></li>
        <li class="nav-item mb-1"><a href="kelola_siswa.php">Kelola Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_kelas.php">Kelola Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
        <li class="nav-item mb-1"><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="about_me.php" class="active">About Me</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2 class="mb-3">About Me</h2>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>

    <div class="card shadow-sm profile-card">
        <div class="card-body text-center p-4">
            <img src="Foto.jpeg" alt="Foto Profil" class="rounded-circle mb-3" width="160" height="200" style="object-fit: cover;">
            <h3><?= htmlspecialchars($nama_user, ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="text-muted">Informasi Pembuat Aplikasi Pelanggaran Siswa</p>
            <hr>

            <h3 class="text-start">Tentang Aplikasi</h3>
            <hr>
            <p class="text-start">
                Aplikasi <strong>Pelanggaran Siswa</strong> merupakan sistem
                informasi yang di buat untuk membantu sekolah mengelola data siswa,
                guru, kelas, tahun ajaran, serta data pelanggaran siswa.
            </p>
            <hr>

            <h4 class="text-start">Identitas</h4>
            <hr>
            <div class="text-start">
                <p><strong>Nama:</strong> Raniameilani</p>
                <p><strong>Sekolah:</strong> SMK Muhammadiyah </p>
                <p><strong>Jurusan:</strong> Rekayasa Perangkat Lunak </p>
                <p><strong>Tujuan:</strong> Memenuhi tugas UKK 2026</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>