```php
<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    if ($id) {
        $id = (int)$id;
        mysqli_query($koneksi, "UPDATE t_guru SET nip='$nip',
            nama='$nama', email='$email' WHERE id='$id'");
    } else {
        mysqli_query($koneksi, "INSERT INTO t_guru (nip, nama, email)
            VALUES ('$nip', '$nama', '$email')");
    }

    header("Location: kelola_guru.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_guru WHERE id='$id'");
    header("Location: kelola_guru.php");
    exit();
}

$edit = ['id'=>'', 'nip'=>'', 'nama'=>'', 'email'=>''];

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $hasil = mysqli_query($koneksi, "SELECT * FROM t_guru WHERE id='$id'");
    $edit = mysqli_fetch_assoc($hasil) ?: $edit;
}

$data = mysqli_query($koneksi, "SELECT * FROM t_guru");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh; background: #212529; color: white;
            position: fixed; width: 250px; top: 0; left: 0;
        }
        .sidebar a {
            color: #adb5bd; text-decoration: none;
            display: block; padding: 10px 20px;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white; background: #0d6efd;
        }
        .main-content { margin-left: 250px; padding: 20px; }
        @media (max-width: 768px) {
            .sidebar { position: relative; width: 100%; min-height: auto; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="kelola_guru.php" class="active">Kelola Guru</a></li>
        <li><a href="kelola_siswa.php">Kelola Siswa</a></li>
        <li><a href="kelola_kelas.php">Kelola Kelas</a></li>
        <li><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
        <li><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
        <li><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
        <li><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
        <li><a href="laporan.php">Laporan</a></li>
        <li><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
        <li><a href="about_me.php">About Me</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <h2>Kelola Guru</h2>
    <a href="dashboard.php">Dashboard</a> |
    <a href="logout.php">Logout</a>
    <hr>

    <!-- FORM GURU -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($edit['id'] ?? '') ?>">

                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control"
                           value="<?= htmlspecialchars($edit['nip'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Guru</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= htmlspecialchars($edit['nama'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($edit['email'] ?? '') ?>" required>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">
                    Simpan
                </button>

                <?php if (!empty($edit['id'])): ?>
                    <a href="kelola_guru.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL GURU -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Daftar Data Guru</h5>

            <div class="table-responsive">
                <table id="tabelGuru"
                       class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($r = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nip']) ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= htmlspecialchars($r['email']) ?></td>
                            <td>
                                <a href="kelola_guru.php?edit=<?= (int)$r['id'] ?>"
                                   class="btn btn-sm btn-warning">Edit</a>
                                <a href="kelola_guru.php?hapus=<?= (int)$r['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus data guru ini?')">
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
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

<script>
$(document).ready(function () {
    new DataTable('#tabelGuru', {
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, 4] }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data guru",
            zeroRecords: "Data guru tidak ditemukan",
            emptyTable: "Belum ada data guru",
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