```php
<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$pdo = new PDO(
    "mysql:host=localhost;dbname=db_ukk_2026;charset=utf8mb4",
    "root", "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

$tahun_id = $_GET['tahun_id'] ?? '';
$kelas_id = $_GET['kelas_id'] ?? '';
$action = $_GET['action'] ?? '';

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

$query = "SELECT ks.*, s.nis, s.nama AS nama_siswa,
          s.jenis_kelamin, k.nama AS nama_kelas,
          ta.nama AS nama_tahun
          FROM t_kelas_siswa ks
          JOIN t_siswa s ON ks.siswa_id = s.id
          JOIN t_kelas k ON ks.kelas_id = k.id
          JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
          $sqlWhere ORDER BY ks.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

if ($action === 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=Penempatan_Siswa_" . date('Ymd') . ".xls");

    echo "\xEF\xBB\xBF";
    echo '<table border="1"><tr>
          <th>No</th><th>Tahun Ajaran</th><th>NIS</th>
          <th>Nama Siswa</th><th>L/P</th><th>Kelas</th><th>Status</th>
          </tr>';

    foreach ($data as $i => $r) {
        echo '<tr>';
        echo '<td>'.($i+1).'</td>';
        echo '<td>'.htmlspecialchars($r['nama_tahun']).'</td>';
        echo '<td>'.htmlspecialchars($r['nis']).'</td>';
        echo '<td>'.htmlspecialchars($r['nama_siswa']).'</td>';
        echo '<td>'.htmlspecialchars($r['jenis_kelamin']).'</td>';
        echo '<td>'.htmlspecialchars($r['nama_kelas']).'</td>';
        echo '<td>'.($r['status_aktif'] ? 'Aktif' : 'Non-Aktif').'</td>';
        echo '</tr>';
    }

    echo '</table>';
    exit;
}

$listTahun = $pdo->query(
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC"
)->fetchAll();

$listKelas = $pdo->query(
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
)->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penempatan Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh; background: #212529; color: white;
            position: fixed; width: 250px; top: 0; left: 0;
            overflow-y: auto;
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

        @media print {
            .sidebar, .no-print, .dt-search, .dt-length,
            .dt-paging, .dt-info { display: none !important; }
            .main-content { margin: 0; padding: 0; }
        }
    </style>
</head>

<body <?= $action === 'print' ? 'onload="window.print()"' : '' ?>>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="kelola_guru.php">Kelola Guru</a>
    <a href="kelola_siswa.php">Kelola Siswa</a>
    <a href="kelola_kelas.php">Kelola Kelas</a>
    <a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a>
    <a href="penempatan_siswa.php" class="active">Penempatan Siswa</a>
    <a href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
    <a href="kelola_kategori_pelanggaran.php">Kategori Pelanggaran</a>
    <a href="laporan.php">Laporan</a>
    <a href="kelola_jenis_pelanggaran.php">Jenis Pelanggaran</a>
    <a href="about_me.php">About Me</a>
    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2>Penempatan Siswa</h2>
    <p><a href="dashboard.php">Dashboard</a> / Penempatan Siswa</p>

    <!-- FILTER DAN EXPORT -->
    <div class="card shadow-sm mb-4 no-print">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_id" class="form-select">
                        <option value="">Semua Tahun Ajaran</option>
                        <?php foreach ($listTahun as $t): ?>
                            <option value="<?= (int)$t['id'] ?>"
                                <?= $tahun_id == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($listKelas as $k): ?>
                            <option value="<?= (int)$k['id'] ?>"
                                <?= $kelas_id == $k['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="penempatan_siswa.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <hr>

            <a href="penempatan_siswa.php?<?= http_build_query(array_merge($_GET, ['action'=>'print'])) ?>"
               target="_blank" class="btn btn-dark btn-sm">Cetak / Print</a>

            <a href="penempatan_siswa.php?<?= http_build_query(array_merge($_GET, ['action'=>'excel'])) ?>"
               class="btn btn-success btn-sm ms-2">Export Excel</a>
        </div>
    </div>

    <!-- TABEL PENEMPATAN SISWA -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Data Penempatan Siswa</div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelPenempatan"
                       class="table table-bordered table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>L/P</th>
                            <th>Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($data as $i => $r): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($r['nama_tahun']) ?></td>
                            <td><?= htmlspecialchars($r['nis']) ?></td>
                            <td><?= htmlspecialchars($r['nama_siswa']) ?></td>
                            <td><?= htmlspecialchars($r['jenis_kelamin']) ?></td>
                            <td><?= htmlspecialchars($r['nama_kelas']) ?></td>
                            <td>
                                <?php if ($r['status_aktif'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Non-Aktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- LIBRARY JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- DATATABLES -->
<script>
$(document).ready(function () {
    $('#tabelPenempatan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        columnDefs: [
            { targets: 0, orderable: false, searchable: false }
        ],
        language: {
            search: "Cari data:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data penempatan",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data penempatan siswa",
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