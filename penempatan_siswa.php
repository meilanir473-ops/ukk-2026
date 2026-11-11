
<?php
require_once 'koneksi.php';

$pdo = new PDO(
    "mysql:host=localhost;dbname=db_ukk_2026;charset=utf8mb4",
    "root", "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

function e($v) {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

// TAHUN AJARAN OTOMATIS
$tahun = (int)date('Y');
$bulan = (int)date('n');

$namaTahunSekarang = $bulan >= 7
    ? "$tahun/" . ($tahun + 1)
    : ($tahun - 1) . "/$tahun";

$stmt = $pdo->prepare("SELECT id FROM t_tahun_ajaran WHERE nama=? LIMIT 1");
$stmt->execute([$namaTahunSekarang]);
$tahun_ajaran_id = (int)$stmt->fetchColumn();

// SIMPAN DAN EDIT
if (isset($_POST['simpan'])) {
    if (!$tahun_ajaran_id) {
        die("Tahun ajaran $namaTahunSekarang belum tersedia di database.");
    }

    $id = (int)($_POST['id'] ?? 0);
    $siswa = (int)($_POST['siswa_id'] ?? 0);
    $kelas = (int)($_POST['kelas_id'] ?? 0);
    $mulai = $_POST['tanggal_mulai'] ?: null;
    $selesai = $_POST['tanggal_selesai'] ?: null;
    $status = isset($_POST['status_aktif']) ? 1 : 0;

    $cek = $pdo->prepare("
        SELECT id FROM t_kelas_siswa
        WHERE siswa_id=? AND tahun_ajaran_id=? AND id!=?
    ");
    $cek->execute([$siswa, $tahun_ajaran_id, $id]);

    if ($cek->fetch()) {
        header("Location: penempatan_siswa.php?pesan=duplikat");
        exit;
    }

    if ($id) {
        $stmt = $pdo->prepare("
            UPDATE t_kelas_siswa SET
            siswa_id=?, tahun_ajaran_id=?, kelas_id=?,
            tanggal_mulai=?, tanggal_selesai=?,
            status_aktif=?, updated_at=NOW()
            WHERE id=?
        ");
        $stmt->execute([$siswa, $tahun_ajaran_id, $kelas, $mulai, $selesai, $status, $id]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO t_kelas_siswa
            (siswa_id,tahun_ajaran_id,kelas_id,tanggal_mulai,
            tanggal_selesai,status_aktif,created_at,updated_at)
            VALUES (?,?,?,?,?,?,NOW(),NOW())
        ");
        $stmt->execute([$siswa, $tahun_ajaran_id, $kelas, $mulai, $selesai, $status]);
    }

    header("Location: penempatan_siswa.php?pesan=berhasil");
    exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("
        DELETE FROM t_kelas_siswa WHERE id=? AND tahun_ajaran_id=?
    ");
    $stmt->execute([(int)$_GET['hapus'], $tahun_ajaran_id]);

    header("Location: penempatan_siswa.php?pesan=hapus");
    exit;
}

// FILTER DAN DATA
$kelas_id = $_GET['kelas_id'] ?? '';
$edit_id = (int)($_GET['edit'] ?? 0);
$tambah = isset($_GET['tambah']);
$action = $_GET['action'] ?? '';

$sql = "
    SELECT ks.*, s.nis, s.nama AS nama_siswa,
    s.jenis_kelamin, k.nama AS nama_kelas, ta.nama AS nama_tahun
    FROM t_kelas_siswa ks
    JOIN t_siswa s ON ks.siswa_id=s.id
    JOIN t_kelas k ON ks.kelas_id=k.id
    JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id=ta.id
    WHERE ks.tahun_ajaran_id=?
";

$params = [$tahun_ajaran_id];

if ($kelas_id !== '') {
    $sql .= " AND ks.kelas_id=?";
    $params[] = $kelas_id;
}

$sql .= " ORDER BY s.nama ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

// DATA EDIT
$edit = [
    'id'=>'', 'siswa_id'=>'', 'kelas_id'=>'',
    'tanggal_mulai'=>'', 'tanggal_selesai'=>'',
    'status_aktif'=>1
];

if ($edit_id) {
    $stmt = $pdo->prepare("
        SELECT * FROM t_kelas_siswa
        WHERE id=? AND tahun_ajaran_id=?
    ");
    $stmt->execute([$edit_id, $tahun_ajaran_id]);
    $edit = $stmt->fetch() ?: $edit;
}

// DROPDOWN
$listKelas = $pdo->query("SELECT id,nama FROM t_kelas ORDER BY nama")->fetchAll();
$listSiswa = $pdo->query("SELECT id,nis,nama FROM t_siswa ORDER BY nama")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Penempatan Siswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }

.sidebar {
    min-height:100vh;
    background:#212529;
    color:white;
    position:fixed;
    width:250px;
    top:0;
    left:0;
    overflow-y:auto;
}

.sidebar a {
    color:#adb5bd;
    text-decoration:none;
    display:block;
    padding:10px 20px;
    border-radius:5px;
}

.sidebar a:hover,.sidebar a.active {
    color:white;
    background:#0d6efd;
}

.main-content {
    margin-left:250px;
    padding:20px;
}

@media print {
    .sidebar,.no-print { display:none !important; }
    .main-content { margin:0; padding:0; }
}

@media(max-width:767.98px) {
    .sidebar { position:relative; width:100%; min-height:auto; }
    .main-content { margin-left:0; }
}
</style>
</head>

<body <?= $action === 'print' ? 'onload="window.print()"' : '' ?>>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="kelola_guru.php">Kelola Guru</a></li>
        <li class="nav-item mb-1"><a href="kelola_siswa.php">Kelola Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_kelas.php">Kelola Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
        <li class="nav-item mb-1"><a href="penempatan_siswa.php" class="active">Penempatan Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>

    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN -->
<div class="main-content">

<h2 class="mb-3">
    <?= $edit_id ? 'Edit Penempatan Siswa' : ($tambah ? 'Tambah Penempatan Siswa' : 'Penempatan Siswa'); ?>
</h2>
<hr>

<!-- PESAN -->
<?php if (isset($_GET['pesan'])): ?>
    <?php
    $pesan = [
        'berhasil' => ['success','Data penempatan berhasil disimpan.'],
        'hapus' => ['success','Data penempatan berhasil dihapus.'],
        'duplikat' => ['warning','Siswa sudah ditempatkan pada tahun ajaran tersebut.']
    ];
    ?>
    <?php if (isset($pesan[$_GET['pesan']])): ?>
        <div class="alert alert-<?= $pesan[$_GET['pesan']][0] ?>">
            <?= $pesan[$_GET['pesan']][1] ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($edit_id || $tambah): ?>

<!-- FORM TAMBAH / EDIT -->
<div class="card shadow-sm mb-4 no-print">
    <div class="card-header bg-primary text-white">
        <?= $edit_id ? 'Edit Penempatan Siswa' : 'Tambah Penempatan Siswa'; ?>
    </div>

    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id" value="<?= e($edit['id']); ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Siswa</label>
                    <select name="siswa_id" id="selectSiswa" class="form-select" required>
                        <option value=""> Pilih Siswa </option>
                        <?php foreach ($listSiswa as $s): ?>
                            <option value="<?= e($s['id']); ?>"
                                <?= $edit['siswa_id'] == $s['id'] ? 'selected' : ''; ?>>
                                <?= e($s['nis'].' - '.$s['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control"
                        value="<?= e($namaTahunSekarang); ?>" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" id="selectKelas" class="form-select" required>
                        <option value="">Pilih Kelas </option>
                        <?php foreach ($listKelas as $k): ?>
                            <option value="<?= e($k['id']); ?>"
                                <?= $edit['kelas_id'] == $k['id'] ? 'selected' : ''; ?>>
                                <?= e($k['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Aktif</label><br>
                    <input type="checkbox" name="status_aktif" value="1"
                        <?= $edit['status_aktif'] ? 'checked' : ''; ?>>
                    Aktif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                        value="<?= e($edit['tanggal_mulai']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                        value="<?= e($edit['tanggal_selesai']); ?>">
                </div>

            </div>

            <button type="submit" name="simpan" class="btn btn-primary"
                <?= !$tahun_ajaran_id ? 'disabled' : ''; ?>>
                <?= $edit_id ? 'Update Data' : 'Simpan Data'; ?>
            </button>

            <a href="penempatan_siswa.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php else: ?>

<!-- FILTER -->
<div class="card shadow-sm mb-4 no-print">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control"
                    value="<?= e($namaTahunSekarang); ?>" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas </option>
                    <?php foreach ($listKelas as $k): ?>
                        <option value="<?= e($k['id']); ?>"
                            <?= $kelas_id == $k['id'] ? 'selected' : ''; ?>>
                            <?= e($k['nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="penempatan_siswa.php" class="btn btn-secondary">Reset</a>
                <a href="penempatan_siswa.php?tambah=1" class="btn btn-primary">
                    + Tambah Penempatan
                </a>
            </div>

        </form>
    </div>
</div>

<!-- TABEL -->
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        Data Penempatan Siswa
    </div>

    <div class="card-body table-responsive">
        <table id="tabelPenempatan" class="table table-bordered table-striped table-hover align-middle w-100">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>L/P</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th class="no-print">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data as $i => $r): ?>
                <tr>
                    <td><?= $i + 1; ?></td>
                    <td><?= e($r['nama_tahun']); ?></td>
                    <td><?= e($r['nis']); ?></td>
                    <td><?= e($r['nama_siswa']); ?></td>
                    <td><?= e($r['jenis_kelamin']); ?></td>
                    <td><?= e($r['nama_kelas']); ?></td>
                    <td>
                        <span class="badge bg-<?= $r['status_aktif'] ? 'success' : 'danger'; ?>">
                            <?= $r['status_aktif'] ? 'Aktif' : 'Non-Aktif'; ?>
                        </span>
                    </td>
                    <td class="text-nowrap no-print">
                        <a href="penempatan_siswa.php?edit=<?= (int)$r['id']; ?>"
                            class="btn btn-warning btn-sm">Edit</a>

                        <a href="penempatan_siswa.php?hapus=<?= (int)$r['id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($data)): ?>
            <p class="text-center text-muted mt-3">Tidak ada data penempatan siswa.</p>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>

</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    if ($('#tabelPenempatan').length) {
        $('#tabelPenempatan').DataTable({
            language: {
                search: "Cari data:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data penempatan siswa",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10,
            columnDefs: [
                { targets: 7, orderable: false, searchable: false }
            ]
        });
    }

    $('#selectSiswa, #selectKelas').select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true
    });
});
</script>

</body>
</html>
