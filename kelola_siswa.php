    ```php
    <?php
    require_once 'koneksi.php';
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        header("Location: login.php"); exit();
    }

    $pesan = '';

    // SIMPAN / EDIT
    if (isset($_POST['simpan'])) {
        $id = (int)($_POST['id'] ?? 0);
        $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
        $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $jk = $_POST['jenis_kelamin'];
        $tgl = $_POST['tanggal_lahir'] ?: null;
        $tglSQL = $tgl ? "'" . mysqli_real_escape_string($koneksi, $tgl) . "'" : "NULL";
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $status = ($_POST['status_aktif'] ?? '') == 'Aktif' ? 1 : 0;

        if ($id) {
            $sql = "UPDATE t_siswa SET nis='$nis', nisn='$nisn', nama='$nama',
            jenis_kelamin='$jk', tanggal_lahir=$tglSQL, alamat='$alamat',
            status_aktif='$status', updated_at=NOW() WHERE id=$id";
        } else {
            $sql = "INSERT INTO t_siswa
            (nis,nisn,nama,jenis_kelamin,tanggal_lahir,alamat,status_aktif,created_at,updated_at)
            VALUES ('$nis','$nisn','$nama','$jk',$tglSQL,'$alamat','$status',NOW(),NOW())";
        }

        if (mysqli_query($koneksi, $sql)) {
            header("Location: kelola_siswa.php"); exit();
        }
        $pesan = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }

    // HAPUS DATA
    if (isset($_GET['hapus'])) {
        $id = (int)$_GET['hapus'];

        mysqli_begin_transaction($koneksi);
        $ok = mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE siswa_id=$id")
        && mysqli_query($koneksi, "DELETE FROM t_kelas_siswa WHERE siswa_id=$id")
        && mysqli_query($koneksi, "DELETE FROM t_siswa WHERE id=$id");

        if ($ok) {
            mysqli_commit($koneksi);
            header("Location: kelola_siswa.php"); exit();
        } else {
            $pesan = "Gagal menghapus data: " . mysqli_error($koneksi);
            mysqli_rollback($koneksi);
        }
    }

    $aksi = $_GET['aksi'] ?? '';
    $edit_id = (int)($_GET['edit'] ?? 0);
    $edit = $edit_id ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id=$edit_id")) : null;

    $limit = 10;
    $h = max(1, (int)($_GET['halaman'] ?? 1));
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari'] ?? '');
    $where = $cari ? "WHERE nis LIKE '%$cari%' OR nisn LIKE '%$cari%' OR nama LIKE '%$cari%'" : "";

    $total = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM t_siswa $where"));
    $pages = max(1, ceil($total / $limit));
    $h = min($h, $pages);
    $offset = ($h - 1) * $limit;
    $data = mysqli_query($koneksi, "SELECT * FROM t_siswa $where ORDER BY nama LIMIT $offset,$limit");
    $no = $offset + 1;
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body{background:#f8f9fa}
    .sidebar{min-height:100vh;background:#212529;color:#fff;position:fixed;width:235px;top:0;left:0;padding:15px}
    .sidebar a{color:#adb5bd;text-decoration:none;display:block;padding:10px 18px;border-radius:5px}
    .sidebar a:hover,.sidebar a.active{color:#fff;background:#0d6efd}
    .main-content{margin-left:235px;padding:20px}
    .card{border:1px solid #ddd;margin-bottom:20px}
    .card-header{padding:10px 15px}
    .table thead th{background:#212529;color:white;white-space:nowrap}
    @media(max-width:768px){.sidebar{width:180px}.main-content{margin-left:180px;padding:12px}}
    </style>
    </head>
    <body>

    <div class="sidebar d-flex flex-column">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="kelola_guru.php">Kelola Guru</a></li>
    <li><a href="kelola_siswa.php" class="active">Kelola Siswa</a></li>
    <li><a href="kelola_kelas.php">Kelola Kelas</a></li>
    <li><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
    <li><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
    <li><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
    <li><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
    <li><a href="laporan.php">Laporan</a></li>
    <li><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
    <li><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger">Logout</a>
    </div>

    <div class="main-content">
    <h2 class="mb-3"><?= $edit ? 'Edit Data Siswa' : 'Kelola Siswa' ?></h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    <hr>

    <?php if ($pesan): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <?php if ($aksi === 'tambah' || $edit): ?>
    <div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><?= $edit ? 'Edit Data Siswa' : 'Tambah Data Siswa' ?></div>
    <div class="card-body">
    <form method="POST">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

    <div class="mb-3">
    <label class="form-label">NIS (Maks. 8 Angka)</label>
    <input type="text" name="nis" class="form-control" maxlength="8" pattern="[0-9]{1,8}" value="<?= htmlspecialchars($edit['nis'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
    <label class="form-label">NISN (Maks. 10 Angka)</label>
    <input type="text" name="nisn" class="form-control" maxlength="10" pattern="[0-9]{0,10}" value="<?= htmlspecialchars($edit['nisn'] ?? '') ?>">
    </div>
    <div class="mb-3">
    <label class="form-label">Nama Siswa</label>
    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($edit['nama'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
    <label class="form-label">Jenis Kelamin</label>
    <select name="jenis_kelamin" class="form-select" required>
    <option value="L" <?= ($edit['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
    <option value="P" <?= ($edit['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>
    </div>
    <div class="mb-3">
    <label class="form-label">Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($edit['tanggal_lahir'] ?? '') ?>">
    </div>
    <div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($edit['alamat'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
    <label class="form-label">Status Aktif</label>
    <select name="status_aktif" class="form-select" required>
    <option value="Aktif" <?= ($edit['status_aktif'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
    <option value="Tidak Aktif" <?= ($edit['status_aktif'] ?? 1) == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
    </select>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    <a href="kelola_siswa.php" class="btn btn-secondary">Batal</a>
    </form>
    </div>
    </div>
    <?php else: ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Data Siswa</h5>
    <a href="?aksi=tambah" class="btn btn-primary">+ Tambah Data Siswa</a>
    </div>

    <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Data Siswa</div>
    <div class="card-body">
    <div class="row mb-3 justify-content-between">
    <div class="col-auto">Tampilkan 10 data</div>
    <div class="col-auto">
    <form method="GET" class="d-flex gap-2">
    <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari data..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">
    <button class="btn btn-primary btn-sm">Cari</button>
    </form>
    </div>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
    <thead><tr><th>No</th><th>NIS</th><th>NISN</th><th>Nama Siswa</th><th>L/P</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php while($r = mysqli_fetch_assoc($data)): ?>
    <tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($r['nis']) ?></td>
    <td><?= htmlspecialchars($r['nisn'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['nama']) ?></td>
    <td><?= htmlspecialchars($r['jenis_kelamin']) ?></td>
    <td><?= htmlspecialchars($r['alamat'] ?? '') ?></td>
    <td><span class="badge bg-<?= $r['status_aktif'] == 1 ? 'success' : 'secondary' ?>"><?= $r['status_aktif'] == 1 ? 'Aktif' : 'Tidak Aktif' ?></span></td>
    <td class="text-nowrap">
    <a href="?edit=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
    <a href="?hapus=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data siswa ini?')">Hapus</a>
    </td>
    </tr>
    <?php endwhile; if (!$total): ?>
    <tr><td colspan="8" class="text-center">Data siswa tidak ditemukan.</td></tr>
    <?php endif; ?>
    </tbody>
    </table>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <small>Menampilkan <?= $total ? $offset + 1 : 0 ?>–<?= min($offset + $limit, $total) ?> dari <?= $total ?> data</small>
    <ul class="pagination pagination-sm mb-0">
    <?php for($i=1; $i<=$pages; $i++): ?>
    <li class="page-item <?= $h == $i ? 'active' : '' ?>">
    <a class="page-link" href="?halaman=<?= $i ?>&cari=<?= urlencode($_GET['cari'] ?? '') ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
    </ul>
    </div>
    </div>
    </div>
    <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
