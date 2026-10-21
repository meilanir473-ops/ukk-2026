<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['simpan'])) {
    $id = (int)($_POST['id'] ?? 0);
    $kategori_id = (int)($_POST['pelanggaran_kategori_id'] ?? 0);
    $kode = mysqli_real_escape_string($koneksi, trim($_POST['kode'] ?? ''));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama'] ?? ''));
    $poin = (int)($_POST['poin'] ?? 0);
    $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi'] ?? ''));

    if ($id > 0) {
        mysqli_query($koneksi, "UPDATE t_pelanggaran SET
            pelanggaran_kategori_id=$kategori_id, kode='$kode',
            nama='$nama', poin=$poin, deskripsi='$deskripsi'
            WHERE id=$id");
    } else {
        mysqli_query($koneksi, "INSERT INTO t_pelanggaran
            (pelanggaran_kategori_id, kode, nama, poin, deskripsi)
            VALUES ($kategori_id, '$kode', '$nama', $poin, '$deskripsi')");
    }

    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran WHERE id=$id");
    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

$edit = [
    'id'=>'', 'pelanggaran_kategori_id'=>'',
    'kode'=>'', 'nama'=>'', 'poin'=>'', 'deskripsi'=>''
];

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $hasil = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran WHERE id=$id");

    if ($hasil && mysqli_num_rows($hasil) > 0) {
        $edit = mysqli_fetch_assoc($hasil);
    }
}

$listKategori = mysqli_query($koneksi,
    "SELECT id, nama FROM t_pelanggaran_kategori
     WHERE status_aktif=1 ORDER BY nama ASC");

$data = mysqli_query($koneksi,
    "SELECT p.*, k.nama AS nama_kategori
     FROM t_pelanggaran p
     LEFT JOIN t_pelanggaran_kategori k
     ON p.pelanggaran_kategori_id=k.id
     ORDER BY p.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #fff;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-width: 0;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
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
        <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php" class="active">Kelola Jenis Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>

    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2 class="mb-3">Kelola Jenis Pelanggaran</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    <hr>
    <!-- FORM TAMBAH / EDIT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <?= !empty($edit['id']) ? 'Edit Jenis Pelanggaran' : 'Tambah Jenis Pelanggaran'; ?>
        </div>
        <div class="card-body">
            <form method="POST" action="kelola_jenis_pelanggaran.php">
                <input type="hidden" name="id" value="<?= e($edit['id']); ?>">

                <div class="mb-3">
                    <label class="form-label">Kategori Pelanggaran:</label>
                    <select name="pelanggaran_kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php if ($listKategori): while ($k = mysqli_fetch_assoc($listKategori)): ?>
                            <option value="<?= (int)$k['id']; ?>"
                                <?= $edit['pelanggaran_kategori_id'] == $k['id'] ? 'selected' : ''; ?>>
                                <?= e($k['nama']); ?>
                            </option>
                        <?php endwhile; endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode:</label>
                    <input type="text" name="kode" class="form-control"
                           placeholder="Contoh: PLG-001" value="<?= e($edit['kode']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Jenis Pelanggaran:</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= e($edit['nama']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Poin:</label>
                    <input type="number" name="poin" class="form-control"
                           min="0" value="<?= e($edit['poin']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi / Keterangan:</label>
                    <textarea name="deskripsi" class="form-control" rows="2"><?= e($edit['deskripsi']); ?></textarea>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">
                    <?= !empty($edit['id']) ? 'Update Data' : 'Simpan'; ?>
                </button>

                <?php if (!empty($edit['id'])): ?>
                    <a href="kelola_jenis_pelanggaran.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Data Jenis Pelanggaran</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelPelanggaran"
                       class="table table-bordered table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Nama Pelanggaran</th>
                            <th>Poin</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($data && mysqli_num_rows($data) > 0): ?>
                        <?php $no=1; while ($r = mysqli_fetch_assoc($data)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= e($r['kode']); ?></td>
                                <td><?= e($r['nama_kategori'] ?? '-'); ?></td>
                                <td><?= e($r['nama']); ?></td>
                                <td><span class="badge bg-danger"><?= (int)$r['poin']; ?></span></td>
                                <td><?= $r['deskripsi'] ? e($r['deskripsi']) : '-'; ?></td>
                                <td class="text-nowrap">
                                    <a href="kelola_jenis_pelanggaran.php?edit=<?= (int)$r['id']; ?>"
                                       class="btn btn-sm btn-warning">Edit</a>
                                    <a href="kelola_jenis_pelanggaran.php?hapus=<?= (int)$r['id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT DATATABLES -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelPelanggaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        columnDefs: [
            { targets: [0, 6], orderable: false, searchable: false }
        ],
        language: {
            search: "Cari data:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data jenis pelanggaran",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

</body>
</html>
