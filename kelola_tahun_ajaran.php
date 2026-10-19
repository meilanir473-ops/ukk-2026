```php
<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$nama_user = $_SESSION['nama_user'] ?? 'Administrator Utama';

if (isset($_POST['simpan'])) {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $mulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
    $status = (int)$_POST['status_aktif'];

    if ($id) {
        mysqli_query($koneksi, "UPDATE t_tahun_ajaran SET
            nama='$nama', tanggal_mulai='$mulai',
            tanggal_selesai='$selesai', status_aktif=$status
            WHERE id=$id");
    } else {
        mysqli_query($koneksi, "INSERT INTO t_tahun_ajaran
            (nama, tanggal_mulai, tanggal_selesai, status_aktif)
            VALUES ('$nama','$mulai','$selesai',$status)");
    }

    header("Location: kelola_tahun_ajaran.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_tahun_ajaran WHERE id=$id");
    header("Location: kelola_tahun_ajaran.php");
    exit();
}

$edit = [
    'id'=>'', 'nama'=>'', 'tanggal_mulai'=>'',
    'tanggal_selesai'=>'', 'status_aktif'=>'1'
];

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $hasil = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran WHERE id=$id");

    if ($hasil && mysqli_num_rows($hasil) > 0) {
        $edit = mysqli_fetch_assoc($hasil);
    }
}

$data = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Tahun Ajaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh; background: #212529;
            color: white; position: fixed;
            width: 250px; top: 0; left: 0;
        }
        .sidebar a {
            color: #adb5bd; text-decoration: none;
            display: block; padding: 10px 20px;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white; background: #0d6efd;
        }
        .main-content { margin-left: 250px; padding: 20px; }

        @media(max-width:768px) {
            .sidebar { position: relative; width: 100%; min-height: auto; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="kelola_guru.php">Kelola Guru</a>
    <a href="kelola_siswa.php">Kelola Siswa</a>
    <a href="kelola_kelas.php">Kelola Kelas</a>
    <a href="kelola_tahun_ajaran.php" class="active">Kelola Tahun Ajaran</a>
    <a href="penempatan_siswa.php">Penempatan Siswa</a>
    <a href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
    <a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a>
    <a href="laporan.php">Laporan</a>
    <a href="kelola_jenis_pelanggaran.php">Jenis Pelanggaran</a>
    <a href="about_me.php">About Me</a>
    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2>Kelola Tahun Ajaran</h2>
    <p><a href="dashboard.php">Dashboard</a> / Tahun Ajaran</p>

    <!-- FORM TAMBAH / EDIT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <?= $edit['id'] ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' ?>
        </div>

        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id"
                       value="<?= htmlspecialchars((string)$edit['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="nama" class="form-control"
                           placeholder="Contoh: 2026/2027"
                           value="<?= htmlspecialchars($edit['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="<?= htmlspecialchars($edit['tanggal_mulai']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                           value="<?= htmlspecialchars($edit['tanggal_selesai']) ?>"
                           min="<?= htmlspecialchars($edit['tanggal_mulai']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Aktif</label>
                    <select name="status_aktif" class="form-select" required>
                        <option value="1" <?= $edit['status_aktif']=='1'?'selected':'' ?>>
                            Aktif
                        </option>
                        <option value="0" <?= $edit['status_aktif']=='0'?'selected':'' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">
                    Simpan
                </button>

                <?php if ($edit['id']): ?>
                    <a href="kelola_tahun_ajaran.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Data Tahun Ajaran</div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelTahunAjaran"
                       class="table table-bordered table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php $no=1; while ($r=mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= htmlspecialchars($r['tanggal_mulai']) ?></td>
                            <td><?= htmlspecialchars($r['tanggal_selesai']) ?></td>
                            <td>
                                <?php if ($r['status_aktif'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="kelola_tahun_ajaran.php?edit=<?= (int)$r['id'] ?>"
                                   class="btn btn-warning btn-sm">Edit</a>

                                <a href="kelola_tahun_ajaran.php?hapus=<?= (int)$r['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                   Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT DATATABLES -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelTahunAjaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        columnDefs: [
            { targets: [0, 5], orderable: false, searchable: false }
        ],
        language: {
            search: "Cari tahun ajaran:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data tahun ajaran",
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
```