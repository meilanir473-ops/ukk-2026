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
</head>
<body>
<h1>Dashboard</h1>

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

<hr>

<h3>Menu Navigasi</h3>
<?php if ($role == "admin") { ?>

    <p>
        <a href="menu1.php">
            Kelola Guru
        </a>
    </p>

    <p>
        <a href="menu2.php">
          Kelola Siswa
        </a>
    </p>

    <p>
        <a href="menu3.php">
            Kelola Kelas
        </a>
    </p>

    <p>
        <a href="menu4.php">
           Kelola Tahun Ajaran
        </a>
    </p>
    <p>
        <a href="menu5.php">
            Penempatan Siswa
        </a>
    </p>
     <p>
        <a href="menu5.php">
            Kelola Wali Kelas
        </a>
    </p>
     <p>
        <a href="menu5.php">
           Kelola Kategori Pelanggan
        </a>
    </p>
     <p>
        <a href="menu5.php">
            Kelola Jenis Pelanggaran
        </a>
    </p>
     <p>
        <a href="menu5.php">
           Cetak/Export
        </a>
    </p>
     <p>
        <a href="menu5.php">
           Cetak  Pelanggaran
        </a>
    </p>
     <p>
        <a href="menu5.php">
            Tindakan
        </a>
    </p>
     <p>
        <a href="menu5.php">
            Laporan
        </a>
    </p>
     <p>
        <a href="menu5.php">
          Riwayat
        </a>
    </p>
     <p>
        <a href="menu5.php">
            Rekap Poin
        </a>

<?php } elseif ($role == "guru") { ?>
   
    <p>
        <a href="menu3.php">
            Catatan Pelanggaran
        </a>
    </p>

    <p>
        <a href="menu4.php">
            Tindakan
        </a>
    </p>
     <p>
        <a href="menu4.php">
            Laporan
        </a>
    </p>
     <p>
        <a href="menu4.php">
            Riwayat
        </a>
    </p>
     <p>
        <a href="menu4.php">
            Rekapan Poin
        </a>
    </p>

<?php } ?>


<hr>

<p>
    <a href="logout.php">
        Logout
    </a>
</p>

</body>

</html>