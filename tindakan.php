<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_ukk_2026");
if (!$koneksi) die("Koneksi gagal: " . mysqli_connect_error());

function e($val) { return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8'); }

// Handle POST (Simpan) & GET (Reset)
if (isset($_POST['simpan_tindakan'])) {
    $stmt = mysqli_prepare($koneksi, "UPDATE t_pelanggaran_siswa SET tindakan = ?, status = ?, updated_at = NOW() WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $_POST['tindakan'], $_POST['status'], $_POST['id']);
    mysqli_stmt_execute($stmt);
    header("Location: tindakan.php?msg=sukses"); exit();
}

if (isset($_GET['reset'])) {
    $stmt = mysqli_prepare($koneksi, "UPDATE t_pelanggaran_siswa SET tindakan = NULL, status = 'Pending', updated_at = NOW() WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_GET['reset']);
    mysqli_stmt_execute($stmt);
    header("Location: tindakan.php?msg=reset"); exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Tindakan - Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; position: fixed; width: 250px; top: 0; left: 0; overflow-y: auto; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; border-radius: 5px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #0d6efd; }
        .main { margin-left: 250px; padding: 30px; }
        @media (max-width: 767.98px) { .sidebar { position: relative; width: 100%; min-height: auto; } .main { margin-left: 0; padding: 15px; } }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="catat_pelanggaran.php">Catat Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="tindakan.php" class="active">Tindakan</a></li>
        <li class="nav-item mb-1"><a href="riwayat.php">Riwayat</a></li>
        <li class="nav-item mb-1"><a href="rekap_poin.php">Rekap Poin</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main">
    <h3 class="fw-bold mb-3">Kelola Tindakan & Sanksi Siswa</h3>
    <a href="dashboard.php" class="btn btn-secondary mb-4">Kembali ke Dashboard</a>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'sukses' ? 'success' : 'warning'; ?> py-2">
            <?= $_GET['msg'] === 'sukses' ? 'Tindakan/sanksi berhasil diperbarui!' : 'Tindakan berhasil di-reset!'; ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">Daftar Tindakan & Penanganan Pelanggaran</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelTindakan" class="table table-bordered table-striped align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th>
                            <th>Pelanggaran</th><th>Poin</th><th>Tindakan / Sanksi</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): 
                        $id = (int)$row['id'];
                        $status = $row['status'] ?? 'Pending';
                        $bg = match($status) { 'Selesai' => 'success', 'Proses' => 'info text-dark', default => 'warning text-dark' };
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= e($row['tanggal']); ?></td>
                            <td><?= e($row['nama_siswa']); ?></td>
                            <td><?= e($row['nama_kelas']); ?></td>
                            <td><?= e($row['nama_pelanggaran']); ?></td>
                            <td><span class="badge bg-danger"><?= e($row['poin']); ?></span></td>
                            <td><?= !empty($row['tindakan']) ? e($row['tindakan']) : '<span class="text-muted fst-italic">Belum ada tindakan</span>'; ?></td>
                            <td><span class="badge bg-<?= $bg; ?>"><?= e($status); ?></span></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTindakan<?= $id; ?>">Beri Tindakan</button>
                                <?php if (!empty($row['tindakan'])): ?>
                                    <a href="tindakan.php?reset=<?= $id; ?>" class="btn btn-warning btn-sm mt-1" onclick="return confirm('Reset tindakan ini?')">Reset</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TINDAKAN -->
<?php 
mysqli_data_seek($query, 0);
while ($row = mysqli_fetch_assoc($query)): 
    $id = (int)$row['id'];
    $status = $row['status'] ?? 'Pending';
?>
<div class="modal fade" id="modalTindakan<?= $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Form Tindakan & Sanksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Siswa:</label>
                        <p class="form-control-plaintext"><?= e($row['nama_siswa']); ?> (<?= e($row['nama_kelas']); ?>)</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pelanggaran:</label>
                        <p class="form-control-plaintext"><?= e($row['nama_pelanggaran']); ?> (Poin: <?= e($row['poin']); ?>)</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan / Sanksi</label>
                        <textarea name="tindakan" class="form-control" rows="3" required placeholder="Contoh: Panggilan orang tua"><?= e($row['tindakan']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Penanganan</label>
                        <select name="status" class="form-select" required>
                            <option value="Pending" <?= $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Proses" <?= $status === 'Proses' ? 'selected' : ''; ?>>Proses</option>
                            <option value="Selesai" <?= $status === 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="simpan_tindakan" class="btn btn-primary">Simpan Tindakan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endwhile; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#tabelTindakan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        columnDefs: [{ targets: [0, 8], orderable: false, searchable: false }],
        language: {
            search: "Cari data:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data", infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan", emptyTable: "Belum ada data tindakan",
            paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });
});
</script>
</body>
</html>