<?php
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { header("Location: login.php"); exit; }

$pesan = "";
if (isset($_POST['simpan'])) {
    $id = $_POST['id'] ?? '';
    $nama = $_POST['nama']; 
    $tgl_mulai = $_POST['tanggal_mulai']; 
    $tgl_selesai = $_POST['tanggal_selesai']; 
    $status = $_POST['status_aktif'];

    if ($id) {
        $q = "UPDATE t_tahun_ajaran SET nama='$nama', tanggal_mulai='$tgl_mulai', tanggal_selesai='$tgl_selesai', status_aktif=$status WHERE id=$id";
        $pesan = "Tahun ajaran diubah!";
    } else {
        $q = "INSERT INTO t_tahun_ajaran (nama, tanggal_mulai, tanggal_selesai, status_aktif) VALUES ('$nama', '$tgl_mulai', '$tgl_selesai', $status)";
        $pesan = "Tahun ajaran ditambahkan!";
    }
    mysqli_query($koneksi, $q) ? header("Location: kelola_tahun_ajaran.php") : $pesan = "Gagal: " . mysqli_error($koneksi);
}

if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM t_tahun_ajaran WHERE id=" . intval($_GET['hapus']));
    header("Location: kelola_tahun_ajaran.php"); exit;
}

$edit = isset($_GET['ubah']) ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran WHERE id=" . intval($_GET['ubah']))) : null;
$data = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Kelola Tahun Ajaran</title></head>
<body>
    <h2>Kelola Tahun Ajaran</h2>
    <p><a href="dashboard.php">&laquo; Dashboard</a></p>
    <?php if ($pesan) echo "<p><b>$pesan</b></p>"; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? ''; ?>">
        Nama Tahun Ajaran (Contoh: 2025/2026): <br>
        <input type="text" name="nama" value="<?= $edit['nama'] ?? ''; ?>" placeholder="2025/2026" required><br>
        
        Tanggal Mulai: <br>
        <input type="date" name="tanggal_mulai" value="<?= $edit['tanggal_mulai'] ?? ''; ?>" required><br>
        
        Tanggal Selesai: <br>
        <input type="date" name="tanggal_selesai" value="<?= $edit['tanggal_selesai'] ?? ''; ?>" required><br>
        
        Status Aktif: <br>
        <select name="status_aktif">
            <option value="1" <?= (($edit['status_aktif'] ?? 0) == 1) ? 'selected':''; ?>>Aktif (1)</option>
            <option value="0" <?= (($edit['status_aktif'] ?? 0) == 0) ? 'selected':''; ?>>Tidak Aktif (0)</option>
        </select><br><br>
        
        <button type="submit" name="simpan">Simpan</button>
        <?php if($edit): ?><a href="kelola_tahun_ajaran.php">Batal</a><?php endif; ?>
    </form>

    <hr>
    <h3>Daftar Tahun Ajaran</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Tahun Ajaran</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while($r = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $r['nama']; ?></td>
            <td><?= $r['tanggal_mulai']; ?></td>
            <td><?= $r['tanggal_selesai']; ?></td>
            <td><?= $r['status_aktif'] ? 'Aktif' : 'Tidak Aktif'; ?></td>
            <td>
                <a href="?ubah=<?= $r['id']; ?>">Edit</a> | 
                <a href="?hapus=<?= $r['id']; ?>" onclick="return confirm('Hapus tahun ajaran ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>