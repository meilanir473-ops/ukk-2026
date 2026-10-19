```php
<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$nama_user = $_SESSION['nama_user'];

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jk = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tgl = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    if ($id) {
        mysqli_query($koneksi, "UPDATE t_siswa SET
            nis='$nis', nisn='$nisn', nama='$nama',
            jenis_kelamin='$jk', tanggal_lahir='$tgl',
            alamat='$alamat' WHERE id=" . (int)$id);
    } else {
        mysqli_query($koneksi, "INSERT INTO t_siswa
            (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
            VALUES ('$nis','$nisn','$nama','$jk','$tgl','$alamat',1)");
    }

    header("Location: kelola_siswa.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_siswa WHERE id=$id");
    header("Location: kelola_siswa.php");
    exit();
}

$kosong = [
    'id'=>'', 'nis'=>'', 'nisn'=>'', 'nama'=>'',
    'jenis_kelamin'=>'', 'tanggal_lahir'=>'', 'alamat'=>''
];

$edit = $kosong;

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $hasil = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id=$id");
    if ($hasil && mysqli_num_rows($hasil) > 0) {
        $edit = mysqli_fetch_assoc($hasil);
    }
}

$data = mysqli_query($koneksi, "SELECT * FROM t_siswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 250px; min-height: 100vh;
            background: #212529; position: fixed;
            top: 0; left: 0; padding: 20px;
        }
        .sidebar a {
            display: block; padding: 10px;
            color: #adb5bd; text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white; background: #0d6efd;
        }
        .main-content { margin-left: 250px; padding: 25px; }
        @media(max-width:768px) {
            .sidebar { width: 100%; min-height: auto; position: relative; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="kelola_guru.php">Kelola Guru</a>
    <a href="kelola_siswa.php" class="active">Kelola Siswa</a>
    <a href="kelola_kelas.php">Kelola Kelas</a>
    <a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a>
    <a href="penempatan_siswa.php">Penempatan Siswa</a>
    <a href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
    <a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a>
    <a href="kelola_jenis_pelanggaran.php">Jenis Pelanggaran</a>
    <a href="laporan.php">Laporan</a>
    <a href="about_me.php">About Me</a>
    <hr class="text-white">
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<div class="main-content">
    <h2>Kelola Siswa</h2>
    <p class="text-secondary">Dashboard / Data Siswa</p>

    <!-- FORM TAMBAH DAN EDIT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <?= $edit['id'] ? 'Edit Data Siswa' : 'Tambah Data Siswa' ?>
        </div>

        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($edit['id']) ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control"
                               value="<?= htmlspecialchars($edit['nis']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control"
                               value="<?= htmlspecialchars($edit['nisn']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" name="nama" class="form-control"
                               value="<?= htmlspecialchars($edit['nama']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" <?= $edit['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
                            <option value="P" <?= $edit['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                               value="<?= htmlspecialchars($edit['tanggal_lahir']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control"
                                  rows="2" required><?= htmlspecialchars($edit['alamat']) ?></textarea>
                    </div>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">
                    Simpan
                </button>

                <?php if ($edit['id']): ?>
                    <a href="kelola_siswa.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA SISWA -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Data Siswa
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelSiswa"
                       class="table table-bordered table-striped table-hover w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>L/P</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php $no=1; while ($r=mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nis']) ?></td>
                            <td><?= htmlspecialchars($r['nisn']) ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= htmlspecialchars($r['jenis_kelamin']) ?></td>
                            <td><?= htmlspecialchars($r['tanggal_lahir']) ?></td>
                            <td><?= htmlspecialchars($r['alamat']) ?></td>
                            <td class="text-nowrap">
                                <a href="kelola_siswa.php?edit=<?= (int)$r['id'] ?>"
                                   class="btn btn-warning btn-sm">Edit</a>

                                <a href="kelola_siswa.php?hapus=<?= (int)$r['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelSiswa').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        columnDefs: [
            { targets: [0, 7], orderable: false, searchable: false }
        ],
        language: {
            search: "Cari siswa:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ siswa",
            infoEmpty: "Tidak ada data siswa",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data siswa",
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
