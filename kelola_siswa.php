<?php
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { header("Location: login.php"); exit; }

$pesan = "";
if (isset($_POST['simpan'])) {
    $id = $_POST['id'] ?? '';
    $nis = $_POST['nis']; $nisn = $_POST['nisn']; $nama = $_POST['nama'];
    $jk = $_POST['jenis_kelamin']; $tgl = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat']; $status = $_POST['status_aktif'];

    if ($id) {
        $q = "UPDATE t_siswa SET nis='$nis', nisn='$nisn', nama='$nama', jenis_kelamin='$jk', tanggal_lahir='$tgl', alamat='$alamat', status_aktif=$status WHERE id=$id";
        $pesan = "Data diubah!";
    } else {
        $q = "INSERT INTO t_siswa (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif) VALUES ('$nis', '$nisn', '$nama', '$jk', '$tgl', '$alamat', $status)";
        $pesan = "Data ditambahkan!";
    }
    mysqli_query($koneksi, $q) ? header("Location: kelola_siswa.php") : $pesan = "Gagal: " . mysqli_error($koneksi);
}

if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM t_siswa WHERE id=" . intval($_GET['hapus']));
    header("Location: kelola_siswa.php"); exit;
}

$edit = isset($_GET['ubah']) ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id=" . intval($_GET['ubah']))) : null;
$data = mysqli_query($koneksi, "SELECT * FROM t_siswa ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Kelola Siswa</title></head>
<body>
    <h2>Kelola Siswa</h2>
    <p><a href="dashboard.php">&laquo; Dashboard</a></p>
    <?php if ($pesan) echo "<p><b>$pesan</b></p>"; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? ''; ?>">
        NIS: <br><input type="text" name="nis" value="<?= $edit['nis'] ?? ''; ?>" required><br>
        NISN: <br><input type="text" name="nisn" value="<?= $edit['nisn'] ?? ''; ?>" required><br>
        Nama: <br><input type="text" name="nama" value="<?= $edit['nama'] ?? ''; ?>" required><br>
        L/P: <br>
        <select name="jenis_kelamin" required>
            <option value="L" <?= (($edit['jenis_kelamin'] ?? '') == 'L') ? 'selected':''; ?>>L</option>
            <option value="P" <?= (($edit['jenis_kelamin'] ?? '') == 'P') ? 'selected':''; ?>>P</option>
        </select><br>
        Tanggal Lahir: <br><input type="date" name="tanggal_lahir" value="<?= $edit['tanggal_lahir'] ?? ''; ?>" required><br>
        Alamat: <br><textarea name="alamat" required><?= $edit['alamat'] ?? ''; ?></textarea><br>
        Status: <br>
        <select name="status_aktif">
            <option value="1" <?= (($edit['status_aktif'] ?? 1) == 1) ? 'selected':''; ?>>Aktif</option>
            <option value="0" <?= (($edit['status_aktif'] ?? 1) == 0) ? 'selected':''; ?>>Tidak</option>
        </select><br><br>
        <button type="submit" name="simpan">Simpan</button>
        <?php if($edit): ?><a href="kelola_siswa.php">Batal</a><?php endif; ?>
    </form>

    <hr>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>No</th><th>NIS</th><th>Nama</th><th>L/P</th><th>Tgl Lahir</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr>
        <?php $no = 1; while($r = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $r['nis']; ?></td>
            <td><?= $r['nama']; ?></td>
            <td><?= $r['jenis_kelamin']; ?></td>
            <td><?= $r['tanggal_lahir']; ?></td>
            <td><?= $r['alamat']; ?></td>
            <td><?= $r['status_aktif'] ? 'Aktif' : 'Tidak'; ?></td>
            <td>
                <a href="?ubah=<?= $r['id']; ?>">Edit</a> | 
                <a href="?hapus=<?= $r['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>