<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$pesan = "";

// PROSES TAMBAH GURU
if (isset($_POST['tambah'])) {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    if (mysqli_query($koneksi, "INSERT INTO t_guru (nip, nama, email) VALUES ('$nip', '$nama', '$email')")) {
        $pesan = "Guru berhasil ditambahkan!";
    } else {
        $pesan = "Gagal: " . mysqli_error($koneksi);
    }
}

// PROSES EDIT / UPDATE GURU
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    $query = "UPDATE t_guru SET nip = '$nip', nama = '$nama', email = '$email' WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Data guru berhasil diubah!";
    } else {
        $pesan = "Gagal update: " . mysqli_error($koneksi);
    }
}

// PROSES HAPUS GURU
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM t_guru WHERE id = $id");
    header("Location: kelola_guru.php");
    exit;
}

// AMBIL DATA UNTUK FORM EDIT JIKA ADA PARAMETER ?ubah=ID
$edit_data = null;
if (isset($_GET['ubah'])) {
    $id_ubah = intval($_GET['ubah']);
    $result_ubah = mysqli_query($koneksi, "SELECT * FROM t_guru WHERE id = $id_ubah");
    $edit_data = mysqli_fetch_assoc($result_ubah);
}

// AMBIL SEMUA DATA GURU (Diurutkan dari ID terkecil agar No 1 ada di atas)
$data = mysqli_query($koneksi, "SELECT * FROM t_guru ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Guru</title>
</head>
<body>
    <h2>Kelola Guru</h2>
    <p><a href="dashboard.php">&laquo; Dashboard</a></p>
    <?php if ($pesan) echo "<p><b>$pesan</b></p>"; ?>

    <!-- FORM TAMBAH / EDIT GURU -->
    <?php if ($edit_data): ?>
        <h3>Edit Guru</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
            NIP: <br><input type="text" name="nip" value="<?= htmlspecialchars($edit_data['nip']); ?>" required><br>
            Nama Guru: <br><input type="text" name="nama" value="<?= htmlspecialchars($edit_data['nama']); ?>" required><br>
            Email: <br><input type="email" name="email" value="<?= htmlspecialchars($edit_data['email']); ?>" required><br><br>
            <button type="submit" name="edit">Update</button>
            <a href="kelola_guru.php">Batal</a>
        </form>
    <?php else: ?>
        <h3>Tambah Guru Baru</h3>
        <form method="POST">
            NIP: <br><input type="text" name="nip" required><br>
            Nama Guru: <br><input type="text" name="nama" required><br>
            Email: <br><input type="email" name="email" required><br><br>
            <button type="submit" name="tambah">Simpan</button>
        </form>
    <?php endif; ?>

    <hr>
    <h3>Daftar Guru</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while($r = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($r['nip'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($r['nama'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($r['email'] ?? '-'); ?></td>
            <td>
                <a href="?ubah=<?= $r['id']; ?>">Edit</a> | 
                <a href="?hapus=<?= $r['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>