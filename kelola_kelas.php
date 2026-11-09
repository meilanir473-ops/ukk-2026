<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

// 1. PROSES SIMPAN (TAMBAH / EDIT)
if (isset($_POST['simpan'])) {
    $id = $_POST['id'] ?? '';
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tingkat = mysqli_real_escape_string($koneksi, $_POST['tingkat']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $status = $_POST['status_aktif'] == 'Aktif' ? 1 : 0;

    if (!empty($id)) {
        mysqli_query($koneksi, "UPDATE t_kelas SET nama='$nama', tingkat='$tingkat', jurusan='$jurusan', status_aktif='$status', updated_at=NOW() WHERE id='$id'");
    } else {
        mysqli_query($koneksi, "INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif, created_at, updated_at) VALUES ('$nama', '$tingkat', '$jurusan', '$status', NOW(), NOW())");
    }
    header("Location: kelola_kelas.php");
    exit();
}

// 2. PROSES HAPUS
if (isset($_GET['hapus'])) {
    $id_kelas = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE kelas_id='$id_kelas'");
    mysqli_query($koneksi, "DELETE FROM t_kelas_siswa WHERE kelas_id='$id_kelas'");
    mysqli_query($koneksi, "DELETE FROM t_wali_kelas WHERE kelas_id='$id_kelas'");
    mysqli_query($koneksi, "DELETE FROM t_kelas WHERE id='$id_kelas'");
    header("Location: kelola_kelas.php");
    exit();
}

$aksi = $_GET['aksi'] ?? '';
$edit_id = $_GET['edit'] ?? '';
$edit = $edit_id ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_kelas WHERE id='$edit_id'")) : null;

// AMBIL SEMUA DATA KELAS (Pengurutan dan limit diserahkan ke DataTables)
$data = mysqli_query($koneksi, "SELECT * FROM t_kelas ORDER BY nama ASC");
if (!$data) {
    die("Gagal mengambil data kelas: " . htmlspecialchars(mysqli_error($koneksi)));
}
$no = 1;

// NILAI FORM
$form = $edit ?: [
    'id' => '',
    'nama' => '',
    'tingkat' => '',
    'jurusan' => '',
    'status_aktif' => 1
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
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
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: #fff;
            background: #0d6efd;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-width: 0;
        }

        .card-header {
            padding: 10px 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 12px;
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
            <li class="nav-item mb-1"><a href="kelola_kelas.php" class="active">Kelola Kelas</a></li>
            <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
            <li class="nav-item mb-1"><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
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
            <?= $edit ? 'Edit Data Kelas' : ($aksi === 'tambah' ? 'Tambah Data Kelas' : 'Kelola Kelas'); ?>
        </h2>
        <hr>

        <?php if ($aksi === 'tambah' || $edit): ?>
            <!-- FORM TAMBAH / EDIT -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <?= $edit ? 'Edit Data Kelas' : 'Tambah Data Kelas'; ?>
                </div>

                <div class="card-body">
                    <form method="POST" action="kelola_kelas.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($form['id'] ?? ''); ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Contoh: XII RPL 1"
                                   value="<?= htmlspecialchars($form['nama'] ?? ''); ?>"
                                   required>
                        </div>

                        <!-- DROPDOWN TINGKAT -->
                        <div class="mb-3">
                            <label class="form-label">Tingkat</label>
                            <select name="tingkat" class="form-select" required>
                                <option value="" disabled <?= empty($form['tingkat']) ? 'selected' : ''; ?>>Pilih Tingkat</option>
                                <option value="X" <?= ($form['tingkat'] ?? '') == 'X' ? 'selected' : ''; ?>>X</option>
                                <option value="XI" <?= ($form['tingkat'] ?? '') == 'XI' ? 'selected' : ''; ?>>XI</option>
                                <option value="XII" <?= ($form['tingkat'] ?? '') == 'XII' ? 'selected' : ''; ?>>XII</option>
                                <option value="XIII" <?= ($form['tingkat'] ?? '') == 'XIII' ? 'selected' : ''; ?>>XIII</option>
                            </select>
                        </div>

                        <!-- DROPDOWN JURUSAN -->
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select" required>
                                <option value="" disabled <?= empty($form['jurusan']) ? 'selected' : ''; ?>>Pilih Jurusan</option>
                                <option value="RPL" <?= ($form['jurusan'] ?? '') == 'RPL' ? 'selected' : ''; ?>>RPL (Rekayasa Perangkat Lunak)</option>
                                <option value="TKJ" <?= ($form['jurusan'] ?? '') == 'TKJ' ? 'selected' : ''; ?>>TKJ (Teknik Komputer dan Jaringan)</option>
                                <option value="TBSM" <?= ($form['jurusan'] ?? '') == 'TBSM' ? 'selected' : ''; ?>>TBSM (Teknik Bisnis Sepeda Motor)</option>
                                <option value="OTKP" <?= ($form['jurusan'] ?? '') == 'OTKP' ? 'selected' : ''; ?>>OTKP (Otomatisasi Tata Kelola Perkantoran)</option>
                                <option value="AKL" <?= ($form['jurusan'] ?? '') == 'AKL' ? 'selected' : ''; ?>>AKL (Akuntansi dan Keuangan Lembaga)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Aktif</label>
                            <select name="status_aktif" class="form-select" required>
                                <option value="Aktif"
                                    <?= (($form['status_aktif'] ?? 1) == 1 || ($form['status_aktif'] ?? '') == 'Aktif') ? 'selected' : ''; ?>>
                                    Aktif
                                </option>
                                <option value="Tidak Aktif"
                                    <?= (($form['status_aktif'] ?? 1) == 0 || ($form['status_aktif'] ?? '') == 'Tidak Aktif') ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>
                            </select>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="kelola_kelas.php" class="btn btn-secondary">
                            Batal
                        </a>
                    </form>
                </div>
            </div>

        <?php else: ?>

            <!-- TOMBOL TAMBAH DATA KELAS -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Data Kelas</h5>
                <a href="kelola_kelas.php?aksi=tambah" class="btn btn-primary">
                    + Tambah Data Kelas
                </a>
            </div>

            <!-- TABEL DATA KELAS -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Data Kelas
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle w-100" id="tabelKelas">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($r = mysqli_fetch_assoc($data)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($r['nama']); ?></td>
                                        <td><?= htmlspecialchars($r['tingkat']); ?></td>
                                        <td><?= htmlspecialchars($r['jurusan']); ?></td>
                                        <td>
                                            <span class="badge bg-<?= ($r['status_aktif'] == 1 || $r['status_aktif'] == 'Aktif') ? 'success' : 'secondary'; ?>">
                                                <?= ($r['status_aktif'] == 1 || $r['status_aktif'] == 'Aktif') ? 'Aktif' : 'Tidak Aktif'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="kelola_kelas.php?edit=<?= $r['id']; ?>"
                                                   class="btn btn-sm btn-warning">
                                                    Edit
                                                </a>

                                                <a href="kelola_kelas.php?hapus=<?= $r['id']; ?>"
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Hapus data kelas ini?')">
                                                    Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Inisialisasi DataTables -->
    <script>
    $(document).ready(function () {
        $('#tabelKelas').DataTable({
            language: {
                search: "Cari data:",
                lengthMenu: "Tampilkan 10 data",
                infoEmpty: "Tidak ada data kelas",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Data kelas tidak ditemukan",
                emptyTable: "Belum ada data kelas",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[1, 'asc']], // Urutkan berdasarkan Nama Kelas
            columnDefs: [
                {
                    targets: [0, 5], // Kolom No dan Aksi tidak bisa di-sort dan search
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
    </script>
</body>
</html>