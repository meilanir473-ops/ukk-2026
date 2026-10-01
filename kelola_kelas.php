<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$pesan = "";

// PROSES TAMBAH KELAS
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tingkat = mysqli_real_escape_string($koneksi, $_POST['tingkat']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $status_aktif = intval($_POST['status_aktif']);
    
    $query = "INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif) VALUES ('$nama', '$tingkat', '$jurusan', $status_aktif)";
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Kelas berhasil ditambahkan!";
    } else {
        $pesan = "Gagal: " . mysqli_error($koneksi);
    }
}

// PROSES EDIT / UPDATE KELAS
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tingkat = mysqli_real_escape_string($koneksi, $_POST['tingkat']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $status_aktif = intval($_POST['status_aktif']);
    
    $query = "UPDATE t_kelas SET nama = '$nama', tingkat = '$tingkat', jurusan = '$jurusan', status_aktif = $status_aktif WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Data kelas berhasil diubah!";
    } else {
        $pesan = "Gagal update: " . mysqli_error($koneksi);
    }
}

// PROSES HAPUS KELAS
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM t_kelas WHERE id = $id");
    header("Location: kelola_kelas.php");
    exit;
}

// AMBIL DATA UNTUK FORM EDIT JIKA ADA PARAMETER ?ubah=ID
$edit_data = null;
if (isset($_GET['ubah'])) {
    $id_ubah = intval($_GET['ubah']);
    $result_ubah = mysqli_query($koneksi, "SELECT * FROM t_kelas WHERE id = $id_ubah");
    $edit_data = mysqli_fetch_assoc($result_ubah);
}

// AMBIL SEMUA DATA KELAS (Diurutkan dari ID terkecil agar No 1 di atas)
$data = mysqli_query($koneksi, "SELECT * FROM t_kelas ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kelas</title>
</head>
<body>
    <h2>Kelola Data Kelas</h2>
    <p><a href="dashboard.php">&laquo; Dashboard</a></p>
    <?php if ($pesan) echo "<p><b>$pesan</b></p>"; ?>

    <!-- FORM TAMBAH / EDIT KELAS -->
    <?php if ($edit_data): ?>
        <h3>Edit Kelas</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
            
            Nama Kelas (Contoh: XII RPL 1): <br>
            <input type="text" name="nama" value="<?= htmlspecialchars($edit_data['nama']); ?>" required><br>
            
            Tingkat: <br>
            <select name="tingkat" required>
                <option value="">-- Pilih Tingkat --</option>
                <option value="X" <?= ($edit_data['tingkat'] == 'X') ? 'selected' : ''; ?>>X</option>
                <option value="XI" <?= ($edit_data['tingkat'] == 'XI') ? 'selected' : ''; ?>>XI</option>
                <option value="XII" <?= ($edit_data['tingkat'] == 'XII') ? 'selected' : ''; ?>>XII</option>
            </select><br>
            
            Jurusan: <br>
            <input type="text" name="jurusan" value="<?= htmlspecialchars($edit_data['jurusan']); ?>" placeholder="Contoh: RPL" required><br>
            
            Status Aktif: <br>
            <select name="status_aktif" required>
                <option value="1" <?= ($edit_data['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif (1)</option>
                <option value="0" <?= ($edit_data['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif (0)</option>
            </select><br><br>
            
            <button type="submit" name="edit">Update Kelas</button>
            <a href="kelola_kelas.php">Batal</a>
        </form>
    <?php else: ?>
        <h3>Tambah Kelas Baru</h3>
        <form method="POST">
            Nama Kelas (Contoh: XII RPL 1): <br>
            <input type="text" name="nama" required><br>
            
            Tingkat: <br>
            <select name="tingkat" required>
                <option value="">-- Pilih Tingkat --</option>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select><br>
            
            Jurusan: <br>
            <input type="text" name="jurusan" placeholder="Contoh: RPL" required><br>
            
            Status Aktif: <br>
            <select name="status_aktif" required>
                <option value="1">Aktif (1)</option>
                <option value="0">Tidak Aktif (0)</option>
            </select><br><br>
            
            <button type="submit" name="tambah">Simpan Kelas</button>
        </form>
    <?php endif; ?>

    <hr>
    <h3>Daftar Kelas</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Kelas</th>
            <th>Tingkat</th>
            <th>Jurusan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while($r = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($r['nama']); ?></td>
            <td><?= htmlspecialchars($r['tingkat']); ?></td>
            <td><?= htmlspecialchars($r['jurusan']); ?></td>
            <td><?= ($r['status_aktif'] == 1) ? 'Aktif' : 'Tidak Aktif'; ?></td>
            <td>
                <a href="?ubah=<?= $r['id']; ?>">Edit</a> | 
                <a href="?hapus=<?= $r['id']; ?>" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>