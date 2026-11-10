
<?php
require_once 'koneksi.php';

// Koneksi PDO
$pdo = new PDO(
    "mysql:host=localhost;dbname=db_ukk_2026;charset=utf8mb4",
    "root",
    "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// FUNGSI ESCAPE
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// PROSES SIMPAN DAN EDIT
if (isset($_POST['simpan'])) {
    $id = (int)($_POST['id'] ?? 0);
    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $tanggal_mulai = !empty($_POST['tanggal_mulai']) ? $_POST['tanggal_mulai'] : null;
    $tanggal_selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : null;
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

    // Cek penempatan ganda
    $cek = $pdo->prepare("
        SELECT id FROM t_kelas_siswa
        WHERE siswa_id = ? AND tahun_ajaran_id = ? AND id != ?
    ");
    $cek->execute([$siswa_id, $tahun_ajaran_id, $id]);

    if ($cek->fetch()) {
        header("Location: penempatan_siswa.php?pesan=duplikat");
        exit();
    }

    if ($id > 0) {
        // UPDATE
        $stmt = $pdo->prepare("
            UPDATE t_kelas_siswa
            SET siswa_id = ?, tahun_ajaran_id = ?, kelas_id = ?,
                tanggal_mulai = ?, tanggal_selesai = ?,
                status_aktif = ?, updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $siswa_id, $tahun_ajaran_id, $kelas_id,
            $tanggal_mulai, $tanggal_selesai, $status_aktif, $id
        ]);
    } else {
        // INSERT
        $stmt = $pdo->prepare("
            INSERT INTO t_kelas_siswa
            (siswa_id, tahun_ajaran_id, kelas_id,
             tanggal_mulai, tanggal_selesai, status_aktif,
             created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $siswa_id, $tahun_ajaran_id, $kelas_id,
            $tanggal_mulai, $tanggal_selesai, $status_aktif
        ]);
    }

    header("Location: penempatan_siswa.php?pesan=berhasil");
    exit();
}

// PROSES HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    $stmt = $pdo->prepare("DELETE FROM t_kelas_siswa WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: penempatan_siswa.php?pesan=hapus");
    exit();
}

// FILTER
$tahun_id = $_GET['tahun_id'] ?? '';
$kelas_id = $_GET['kelas_id'] ?? '';
$action = $_GET['action'] ?? '';
$edit_id = (int)($_GET['edit'] ?? 0);
$tambah = isset($_GET['tambah']);

$where = [];
$params = [];

if ($tahun_id !== '') {
    $where[] = "ks.tahun_ajaran_id = :th";
    $params[':th'] = $tahun_id;
}

if ($kelas_id !== '') {
    $where[] = "ks.kelas_id = :kl";
    $params[':kl'] = $kelas_id;
}

$sqlWhere = $where ? "WHERE " . implode(" AND ", $where) : "";

// DATA PENEMPATAN SISWA
$query = "
    SELECT ks.*, s.nis, s.nama AS nama_siswa,
           s.jenis_kelamin, k.nama AS nama_kelas,
           ta.nama AS nama_tahun
    FROM t_kelas_siswa ks
    JOIN t_siswa s ON ks.siswa_id = s.id
    JOIN t_kelas k ON ks.kelas_id = k.id
    JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    $sqlWhere
    ORDER BY s.nama ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

// DATA EDIT
$edit = [
    'id' => '',
    'siswa_id' => '',
    'tahun_ajaran_id' => '',
    'kelas_id' => '',
    'tanggal_mulai' => '',
    'tanggal_selesai' => '',
    'status_aktif' => 1
];

if ($edit_id > 0) {
    $stmtEdit = $pdo->prepare("
        SELECT * FROM t_kelas_siswa WHERE id = ?
    ");
    $stmtEdit->execute([$edit_id]);
    $edit = $stmtEdit->fetch() ?: $edit;
}

// EXPORT EXCEL
if ($action === 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=Penempatan_Siswa_" . date('Ymd') . ".xls");

    echo "\xEF\xBB\xBF";
    echo "<h3>Laporan Penempatan Siswa</h3>";
    echo "<table border='1'>";
    echo "<tr>
        <th>No</th>
        <th>Tahun Ajaran</th>
        <th>NIS</th>
        <th>Nama Siswa</th>
        <th>L/P</th>
        <th>Kelas</th>
        <th>Status</th>
    </tr>";

    foreach ($data as $i => $r) {
        echo "<tr>";
        echo "<td>" . ($i + 1) . "</td>";
        echo "<td>" . e($r['nama_tahun']) . "</td>";
        echo "<td>'" . e($r['nis']) . "</td>";
        echo "<td>" . e($r['nama_siswa']) . "</td>";
        echo "<td>" . e($r['jenis_kelamin']) . "</td>";
        echo "<td>" . e($r['nama_kelas']) . "</td>";
        echo "<td>" . ($r['status_aktif'] == 1 ? 'Aktif' : 'Non-Aktif') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    exit;
}

// DATA DROPDOWN
$listTahun = $pdo->query("
    SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC
")->fetchAll();

$listKelas = $pdo->query("
    SELECT id, nama FROM t_kelas ORDER BY nama ASC
")->fetchAll();

$listSiswa = $pdo->query("
    SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penempatan Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }

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

        @media print {
            .sidebar, .no-print,
            .dataTables_wrapper .dt-layout-row:not(:has(table)) {
                display: none !important;
            }

            .main-content {
                margin-left: 0;
                padding: 0;
            }

            body { background-color: #fff; }
        }

        @media (max-width: 767.98px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .main-content { margin-left: 0; }
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

<!-- KONTEN UTAMA -->
<div class="main-content">

    <h2 class="mb-3">
        <?= $edit_id > 0 ? 'Edit Penempatan Siswa' : ($tambah ? 'Tambah Penempatan Siswa' : 'Penempatan Siswa'); ?>
    </h2>
    <hr>

    <!-- PESAN -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] === 'berhasil'): ?>
            <div class="alert alert-success">Data penempatan berhasil disimpan.</div>
        <?php elseif ($_GET['pesan'] === 'hapus'): ?>
            <div class="alert alert-success">Data penempatan berhasil dihapus.</div>
        <?php elseif ($_GET['pesan'] === 'duplikat'): ?>
            <div class="alert alert-warning">Siswa sudah ditempatkan pada tahun ajaran tersebut.</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($edit_id > 0 || $tambah): ?>

        <!-- FORM TAMBAH / EDIT -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-header bg-primary text-white">
                <?= $edit_id > 0 ? 'Edit Penempatan Siswa' : 'Tambah Penempatan Siswa'; ?>
            </div>

            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= e($edit['id']); ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Siswa</label>
                            <select name="siswa_id" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($listSiswa as $s): ?>
                                    <option value="<?= e($s['id']); ?>"
                                        <?= (string)$edit['siswa_id'] === (string)$s['id'] ? 'selected' : ''; ?>>
                                        <?= e($s['nis'] . ' - ' . $s['nama']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" class="form-select" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <?php foreach ($listTahun as $t): ?>
                                    <option value="<?= e($t['id']); ?>"
                                        <?= (string)$edit['tahun_ajaran_id'] === (string)$t['id'] ? 'selected' : ''; ?>>
                                        <?= e($t['nama']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach ($listKelas as $k): ?>
                                    <option value="<?= e($k['id']); ?>"
                                        <?= (string)$edit['kelas_id'] === (string)$k['id'] ? 'selected' : ''; ?>>
                                        <?= e($k['nama']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Aktif</label><br>
                            <input type="checkbox" name="status_aktif" value="1"
                                <?= (int)$edit['status_aktif'] === 1 ? 'checked' : ''; ?>>
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

                    <button type="submit" name="simpan" class="btn btn-primary">
                        <?= $edit_id > 0 ? 'Update Data' : 'Simpan Data'; ?>
                    </button>

                    <a href="penempatan_siswa.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

    <?php else: ?>

        <!-- FILTER DAN AKSI -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-body">

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="penempatan_siswa.php?tambah=1" class="btn btn-primary">
                        + Tambah Penempatan
                    </a>

                    <a href="penempatan_siswa.php?<?= http_build_query(array_merge($_GET, ['action' => 'excel'])); ?>"
                       class="btn btn-success">
                        Export Excel
                    </a>

                    <a href="penempatan_siswa.php?<?= http_build_query(array_merge($_GET, ['action' => 'print'])); ?>"
                       class="btn btn-secondary">
                        Cetak
                    </a>
                </div>

                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tahun Ajaran:</label>
                        <select name="tahun_id" class="form-select">
                            <option value="">-- Semua Tahun Ajaran --</option>
                            <?php foreach ($listTahun as $t): ?>
                                <option value="<?= e($t['id']); ?>"
                                    <?= (string)$tahun_id === (string)$t['id'] ? 'selected' : ''; ?>>
                                    <?= e($t['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kelas:</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">-- Semua Kelas --</option>
                            <?php foreach ($listKelas as $k): ?>
                                <option value="<?= e($k['id']); ?>"
                                    <?= (string)$kelas_id === (string)$k['id'] ? 'selected' : ''; ?>>
                                    <?= e($k['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="penempatan_siswa.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL DATA -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                Data Penempatan Siswa
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelPenempatan" class="table table-bordered table-striped table-hover align-middle w-100">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tahun Ajaran</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">L/P</th>
                                <th>Kelas</th>
                                <th class="text-center">Status</th>
                                <th class="text-center no-print">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($data as $i => $r): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1; ?></td>
                                    <td><?= e($r['nama_tahun']); ?></td>
                                    <td><?= e($r['nis']); ?></td>
                                    <td><?= e($r['nama_siswa']); ?></td>
                                    <td class="text-center"><?= e($r['jenis_kelamin']); ?></td>
                                    <td><?= e($r['nama_kelas']); ?></td>
                                    <td class="text-center">
                                        <?php if ((int)$r['status_aktif'] === 1): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Non-Aktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center text-nowrap no-print">
                                        <a href="penempatan_siswa.php?edit=<?= (int)$r['id']; ?>"
                                           class="btn btn-warning btn-sm">Edit</a>

                                        <a href="penempatan_siswa.php?hapus=<?= (int)$r['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data penempatan siswa ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (empty($data)): ?>
                        <p class="text-center text-muted mt-3">
                            Tidak ada data penempatan siswa.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    if ($('#tabelPenempatan').length) {
        $('#tabelPenempatan').DataTable({
            language: {
                search: "Cari data:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
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
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'asc']],
            columnDefs: [
                { targets: 7, orderable: false, searchable: false }
            ]
        });
    }
});
</script>

</body>
</html>
