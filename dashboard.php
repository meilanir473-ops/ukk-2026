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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<head>
    
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">
           Sistem Pelanggaran
            </h4>
            <?php if ($role === 'admin'): ?>
            <li class="nav-item mb-2"><a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
            <li class="nav-item mb-2"><a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a></li>
            <li class="nav-item mb-2"><a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
            <li class="nav-item mb-2"><a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a></li>
            <li class="nav-item mb-2"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
            <li class="nav-item mb-2"><a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a></li>
            <li class="nav-item mb-2"><a href="kelola_kategori.php" class="nav-link text-white">Kelola Kategori</a></li>
            <li class="nav-item mb-2"><a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">Kelola Jenis Pelanggaran</a></li>
            <li class="nav-item mb-2"><a href="cetak_export.php" class="nav-link text-white">Cetak / Export</a></li>
        <?php endif; ?>

        <!-- Menu untuk Guru & Admin -->
        <?php if ($role === 'guru' || $role === 'admin'): ?>
            <li class="nav-item mb-2"><a href="catat_pelanggaran.php" class="nav-link text-white">Catat Pelanggaran</a></li>
            <li class="nav-item mb-2"><a href="tindakan.php" class="nav-link text-white">Tindakan</a></li>
            <li class="nav-item mb-2"><a href="laporan.php" class="nav-link text-white">Laporan</a></li>
            <li class="nav-item mb-2"><a href="riwayat.php" class="nav-link text-white">Riwayat</a></li>
            <li class="nav-item mb-2"><a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a></li>
            <li class="nav-item mb-2"><a href="aboutme" class="nav-link text-white">Aboutme</a></li>
        <?php endif; ?>
            
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                    <p>
        Logout
    </a>
</p>

            </ul>

        </div>


        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2>Dashboard</h2>

            <p>
               <p>
    Selamat datang,
    <strong>
        <?php echo htmlspecialchars($nama); ?>
    </strong>
</p>

<p>
    Anda login sebagai:
    <strong>
        <?php echo ucfirst($role); ?>
    </strong>
</p>
            </p>

            <div class="row">

                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title">
                                Data Siswa
                            </h5>

                            <h2>
                                120
                            </h2>

                        </div>

                    </div>
                </div>


                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title">
                                Data Guru
                            </h5>

                            <h2>
                                25
                            </h2>

                        </div>

                    </div>
                </div>


                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title">
                                Kelas
                            </h5>

                            <h2>
                                12
                            </h2>

                        </div>

                    </div>
                </div>

            </div>

        </main>

    </div>
</div>
</body>

</html>