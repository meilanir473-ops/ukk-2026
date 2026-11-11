<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';
$nama = $_SESSION['name'] ?? ($_SESSION['nama_user'] ?? ($_SESSION['nama'] ?? 'Pengguna'));

// Proses Update Tindakan & Status
if (isset($_POST['update_tindakan'])) {
    $id = (int) $_POST['id'];
    $tindakan_manual = mysqli_real_escape_string($koneksi, $_POST['tindakan']);
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    $stmt = mysqli_prepare($koneksi, "UPDATE t_pelanggaran_siswa SET tindakan = ?, status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $tindakan_manual, $status_baru, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: tindakan.php?msg=update");
    exit();
}

// Ambil data HANYA yang statusnya BUKAN 'Selesai' dari SEMUA KELAS
$sql = "SELECT ps.*, s.nama AS nama_siswa, k.nama AS nama_kelas, p.nama AS nama_pelanggaran, g.nama AS nama_guru 
        FROM t_pelanggaran_siswa ps 
        LEFT JOIN t_siswa s ON ps.siswa_id = s.id 
        LEFT JOIN t_kelas k ON ps.kelas_id = k.id 
        LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id 
        LEFT JOIN t_guru g ON ps.guru_id = g.id 
        WHERE ps.status != 'Selesai'
        ORDER BY ps.tanggal DESC, ps.id DESC";

$data = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Tindakan Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
    body { background: #f8f9fa; }
    
    /* SIDEBAR SAMA PERSIS DENGAN HALAMAN RIWAYAT */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: #212529;
        color: #fff;
        padding-top: 15px;
        overflow-y: auto;
    }
    .sidebar a {
        display: block;
        padding: 10px 20px;
        color: #adb5bd;
        text-decoration: none;
    }
    .sidebar a:hover,
    .sidebar a.active {
        background: #0d6efd;
        color: #fff;
    }

    /* KONTEN UTAMA */
    .main { 
        margin-left: 250px; 
        padding: 30px; 
    }
    .card { 
        border-radius: 6px; 
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); 
    }
</style>
</head>
<body>

<!-- Sidebar Konsisten -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="catat_pelanggaran.php">Catat Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="tindakan.php" class="active">Tindakan</a></li>
        <li class="nav-item mb-1"><a href="riwayat.php">Riwayat</a></li>
        <li class="nav-item mb-1"><a href="rekap_poin.php">Rekap Poin</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>

    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- Main Content -->
<div class="main">
    <h3 class="fw-bold mb-3">Tindakan Pelanggaran</h3>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'update'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Tindakan dan status berhasil diperbarui! 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white py-3 fw-semibold">
            Data Tindakan Pelanggaran Siswa (Semua Kelas)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelTindakan" class="table table-bordered table-striped align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Pelanggaran</th>
                            <th>Poin</th>
                            <th>Guru</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['tanggal'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggaran'] ?? '-'); ?></td>
                            <td><span class="badge bg-danger"><?= (int)($row['poin'] ?? 0); ?></span></td>
                            <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
                            <td>
                                <span class="badge bg-<?= ($row['status'] === 'Selesai') ? 'success' : 'warning text-dark'; ?>">
                                    <?= htmlspecialchars($row['status'] ?? 'Proses'); ?>
                                </span>
                            </td>
                            <td>
                                <!-- Tombol Aksi Tindak yang memicu Modal Popup -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTindak<?= $row['id']; ?>">
                                    Tindak
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Popup untuk masing-masing baris data -->
                        <div class="modal fade" id="modalTindak<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">Form Tindakan Pelanggaran</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nama Siswa</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nama_siswa']); ?> (<?= htmlspecialchars($row['nama_kelas']); ?>)" disabled>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Pelanggaran</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nama_pelanggaran']); ?>" disabled>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jenis Tindakan (Ketik Manual)</label>
                                                <textarea name="tindakan" class="form-control" rows="3" placeholder="Contoh: Diberi pembinaan, dipanggil orang tua, dll." required><?= htmlspecialchars($row['tindakan'] ?? ''); ?></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Status Tindakan</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Proses" <?= ($row['status'] === 'Proses' || $row['status'] === 'Pending') ? 'selected' : ''; ?>>Proses</option>
                                                    <option value="Selesai" <?= ($row['status'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update_tindakan" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Akhir Modal -->

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#tabelTindakan').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            zeroRecords: "Belum ada data tindakan",
            paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });
});
</script>
</body>
</html>