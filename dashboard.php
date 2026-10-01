<?php

session_start();

if (!isset($_SESSION['id'])) {

    header("Location: login.php");
    exit;

}

$nama = $_SESSION['name'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 d-flex flex-column justify-content-between">
            <div>
                <h4 class="text-white mb-4">
                    Sistem Pelanggaran
                </h4>
                
                <ul class="nav flex-column">
                    <!-- Menu Khusus Admin -->
                    <?php if ($role === 'admin'): ?>
                    <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link active bg-primary text-white rounded">Dashboard</a></li>
                    <li class="nav-item mb-2"><a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
                    <li class="nav-item mb-2"><a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a></li>
                    <li class="nav-item mb-2"><a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
                    <li class="nav-item mb-2"><a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a></li>
                    <li class="nav-item mb-2"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
                    <li class="nav-item mb-2"><a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a></li>
                    <li class="nav-item mb-2"><a href="kelola_kategori.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a></li>
                    <li class="nav-item mb-2"><a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">Kelola Jenis Pelanggaran</a></li>
                    <li class="nav-item mb-2"><a href="cetak_export.php" class="nav-link text-white">Cetak / Export</a></li>

                    <?php endif; ?>

                    <!-- Menu untuk Guru & Admin -->
                    <?php if ($role === 'guru' ): ?>
                        <?php if ($role === 'guru'): ?>
                            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link active bg-primary text-white rounded">Dashboard</a></li>
                        <?php endif; ?>
                        <li class="nav-item mb-2"><a href="catat_pelanggaran.php" class="nav-link text-white">Catat Pelanggaran</a></li>
                        <li class="nav-item mb-2"><a href="tindakan.php" class="nav-link text-white">Tindakan</a></li>
                        <li class="nav-item mb-2"><a href="laporan.php" class="nav-link text-white">Laporan</a></li>
                        <li class="nav-item mb-2"><a href="riwayat.php" class="nav-link text-white">Riwayat</a></li>
                        <li class="nav-item mb-2"><a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a></li>
                        
                    <?php endif; ?>

                    <!-- Link About Me (Bisa diakses Admin dan Guru) -->
                   <li class="nav-item mb-2"> <a href="aboutme.php" class="nav-link text-white"><i class="bi bi-person-circle"></i> About Me
    </a>
</li>
                </ul>
            </div>

            <!-- Tombol Logout -->
            <div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2>Dashboard</h2>

            <p class="mt-3">
                Selamat datang, <strong><?php echo htmlspecialchars($nama); ?></strong>
            </p>
            <p>
                Anda login sebagai: <strong><?php echo ucfirst($role); ?></strong>
            </p>

            <div class="row mt-4">

                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Data Siswa</h5>
                            <h2 class="fw-bold">120</h2>
                        </div>
                    </div>
                </div>

                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Data Guru</h5>
                            <h2 class="fw-bold">25</h2>
                        </div>
                    </div>
                </div>

                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Kelas</h5>
                            <h2 class="fw-bold">12</h2>
                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>