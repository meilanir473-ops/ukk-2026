
<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$role = strtolower($_SESSION['role'] ?? '');
$is_wk = $_SESSION['is_wali_kelas'] ?? false;

// SIMPAN / EDIT
if (isset($_POST['simpan'])) {
    $id = $_POST['id'] ?? '';
    $siswa_id = (int)$_POST['siswa_id'];
    $pelanggaran_id = (int)$_POST['pelanggaran_id'];
    $tanggal = $_POST['tanggal'];

    $tahun_ajaran_id = 51;
    $ta = mysqli_query($koneksi, "SELECT id FROM t_tahun_ajaran WHERE status_aktif=1 LIMIT 1");
    if ($ta && mysqli_num_rows($ta)) {
        $tahun_ajaran_id = (int)mysqli_fetch_assoc($ta)['id'];
    }

    $nama_siswa = '-'; $kelas_id = 0; $nama_kelas = '-';
    $q = mysqli_query($koneksi, "SELECT s.nama,k.id kelas_id,k.nama nama_kelas FROM t_siswa s LEFT JOIN t_kelas_siswa ks ON s.id=ks.siswa_id AND ks.status_aktif=1 LEFT JOIN t_kelas k ON ks.kelas_id=k.id WHERE s.id=$siswa_id LIMIT 1");
    if ($q && mysqli_num_rows($q)) {
        $s = mysqli_fetch_assoc($q);
        $nama_siswa = $s['nama'];
        $kelas_id = (int)($s['kelas_id'] ?? 0);
        $nama_kelas = $s['nama_kelas'] ?? '-';
    }

    $nama_pelanggaran = '-'; $kategori_id = 0; $poin = 0;
    $q = mysqli_query($koneksi, "SELECT nama,poin,pelanggaran_kategori_id FROM t_pelanggaran WHERE id=$pelanggaran_id LIMIT 1");
    if ($q && mysqli_num_rows($q)) {
        $p = mysqli_fetch_assoc($q);
        $nama_pelanggaran = $p['nama'];
        $kategori_id = (int)$p['pelanggaran_kategori_id'];
        $poin = (int)$p['poin'];
    }

    $guru_id = 1; $nama_guru = 'Administrator';
    $uid = (int)($_SESSION['user_id'] ?? 0);
    $q = mysqli_query($koneksi, "SELECT id,nama FROM t_guru WHERE user_id=$uid LIMIT 1");
    if (!$q || !mysqli_num_rows($q)) $q = mysqli_query($koneksi, "SELECT id,nama FROM t_guru LIMIT 1");
    if ($q && mysqli_num_rows($q)) {
        $g = mysqli_fetch_assoc($q);
        $guru_id = (int)$g['id'];
        $nama_guru = $g['nama'];
    }

    $keterangan = '-'; $tindakan = '-'; $status = 'Pending';

    if ($siswa_id > 0 && $pelanggaran_id > 0) {
        if ($id) {
            $stmt = mysqli_prepare($koneksi, "UPDATE t_pelanggaran_siswa SET tahun_ajaran_id=?,siswa_id=?,nama_siswa=?,kelas_id=?,nama_kelas=?,pelanggaran_id=?,nama_pelanggaran=?,pelanggaran_kategori_id=?,guru_id=?,nama_guru=?,tanggal=?,poin=? WHERE id=?");
            if ($stmt) mysqli_stmt_bind_param($stmt,"iisisisiisssi",$tahun_ajaran_id,$siswa_id,$nama_siswa,$kelas_id,$nama_kelas,$pelanggaran_id,$nama_pelanggaran,$kategori_id,$guru_id,$nama_guru,$tanggal,$poin,$id);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO t_pelanggaran_siswa (tahun_ajaran_id,siswa_id,nama_siswa,kelas_id,nama_kelas,pelanggaran_id,nama_pelanggaran,pelanggaran_kategori_id,guru_id,nama_guru,tanggal,keterangan,poin,tindakan,status,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
            if ($stmt) mysqli_stmt_bind_param($stmt,"iisisisiisssiss",$tahun_ajaran_id,$siswa_id,$nama_siswa,$kelas_id,$nama_kelas,$pelanggaran_id,$nama_pelanggaran,$kategori_id,$guru_id,$nama_guru,$tanggal,$keterangan,$poin,$tindakan,$status);
        }

        if (isset($stmt) && $stmt && mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: catat_pelanggaran.php?msg=sukses"); exit();
        }
        die("Query Error: ".mysqli_error($koneksi));
    }
    header("Location: catat_pelanggaran.php?msg=gagal"); exit();
}

// HAPUS
if (isset($_GET['hapus'])) {
    $hapus = (int)$_GET['hapus'];
    mysqli_query($koneksi,"DELETE FROM t_pelanggaran_siswa WHERE id=$hapus");
    header("Location: catat_pelanggaran.php?msg=hapus"); exit();
}

$aksi = $_GET['aksi'] ?? '';
$edit_id = (int)($_GET['edit'] ?? 0);
$edit = $edit_id ? mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM t_pelanggaran_siswa WHERE id=$edit_id")) : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Catat Pelanggaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
body{background:#f8f9fa}
.sidebar{width:250px;height:100vh;position:fixed;top:0;left:0;background:#212529;padding:15px;overflow-y:auto;z-index:1000}
.sidebar a{display:block;padding:10px 20px;color:#adb5bd;text-decoration:none;border-radius:5px}
.sidebar a:hover,.sidebar a.active{background:#0d6efd;color:white}
.main{margin-left:250px;padding:30px}
.card{border:0;border-radius:7px;overflow:hidden}
.card-header{padding:12px 16px}
.card-body{padding:25px 28px}
#tabelPelanggaran{width:100%!important}
#tabelPelanggaran th{background:#212529;color:white;vertical-align:middle}
#tabelPelanggaran td{vertical-align:middle}
@media(max-width:768px){.sidebar{width:200px}.main{margin-left:200px;padding:15px}}
</style>
</head>

<body>

<!-- SIDEBAR SAMA DENGAN DASHBOARD -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item mb-1">
            <a href="dashboard.php">Dashboard</a>
        </li>

        <?php if ($role === 'admin'): ?>

            <li class="nav-item mb-1"><a href="kelola_guru.php">Kelola Guru</a></li>
            <li class="nav-item mb-1"><a href="kelola_siswa.php">Kelola Siswa</a></li>
            <li class="nav-item mb-1"><a href="kelola_kelas.php">Kelola Kelas</a></li>
            <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
            <li class="nav-item mb-1"><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
            <li class="nav-item mb-1"><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
            <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
            <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>

        <?php elseif ($role === 'guru'): ?>

            <li class="nav-item mb-1">
                <a href="catat_pelanggaran.php" class="active">Catat Pelanggaran</a>
            </li>

            <li class="nav-item mb-1"><a href="tindakan.php">Tindakan</a></li>
            <li class="nav-item mb-1"><a href="riwayat.php">Riwayat</a></li>
            <li class="nav-item mb-1"><a href="rekap_poin.php">Rekap Poin</a></li>

        <?php endif; ?>

        <?php if ($role === 'admin' || $is_wk === true): ?>
            <li class="nav-item mb-1">
                <a href="laporan.php">Laporan</a>
            </li>
        <?php endif; ?>

        <li class="nav-item mb-1">
            <a href="about_me.php">About Me</a>
        </li>

    </ul>

    <hr>
    <a href="logout.php" class="text-danger" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
</div>

<!-- KONTEN -->
<div class="main">
<h3 class="fw-bold mb-3">
<?= $edit?'Edit Catatan Pelanggaran':($aksi==='tambah'?'Tambah Catatan Pelanggaran':'Catat Pelanggaran') ?>
</h3>
<hr>

<?php if(isset($_GET['msg'])): ?>
<div class="alert alert-<?= $_GET['msg']==='gagal'?'danger':'success' ?>">
<?= $_GET['msg']==='sukses'?'Data berhasil disimpan.':($_GET['msg']==='hapus'?'Data berhasil dihapus.':'Gagal memproses data.') ?>
</div>
<?php endif; ?>

<?php if($aksi==='tambah'||$edit): ?>

<div class="card shadow-sm">
<div class="card-header bg-dark text-white">Form Pelanggaran</div>
<div class="card-body">
<form method="POST">
<input type="hidden" name="id" value="<?= htmlspecialchars($edit['id']??'') ?>">

<div class="mb-3">
<label class="form-label fw-bold">Tanggal</label>
<input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($edit['tanggal']??date('Y-m-d')) ?>" required>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Siswa</label>
<select name="siswa_id" class="form-select" required>
<option value="">-- Pilih Siswa --</option>
<?php
$q=mysqli_query($koneksi,"SELECT id,nis,nama FROM t_siswa ORDER BY nama ASC");
while($q && $r=mysqli_fetch_assoc($q)):
?>
<option value="<?= $r['id'] ?>" <?= (($edit['siswa_id']??'')==$r['id'])?'selected':'' ?>>
<?= htmlspecialchars(($r['nis']??'').' - '.$r['nama']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Pelanggaran</label>
<select name="pelanggaran_id" class="form-select" required>
<option value="">-- Pilih Pelanggaran --</option>
<?php
$q=mysqli_query($koneksi,"SELECT id,kode,nama,poin FROM t_pelanggaran ORDER BY kode ASC");
while($q && $r=mysqli_fetch_assoc($q)):
?>
<option value="<?= $r['id'] ?>" <?= (($edit['pelanggaran_id']??'')==$r['id'])?'selected':'' ?>>
<?= htmlspecialchars($r['kode'].' - '.$r['nama'].' ('.$r['poin'].' Poin)') ?>
</option>
<?php endwhile; ?>
</select>
</div>

<button type="submit" name="simpan" class="btn btn-primary"><?= $edit?'Update Data':'Simpan' ?></button>
<a href="catat_pelanggaran.php" class="btn btn-secondary">Batal</a>
</form>
</div>
</div>

<?php else: ?>

<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Data Pelanggaran Siswa</h5>
<a href="catat_pelanggaran.php?aksi=tambah" class="btn btn-primary btn-sm">+ Tambah Data</a>
</div>

<div class="card shadow-sm">
<div class="card-header bg-dark text-white">Daftar Pelanggaran</div>
<div class="card-body">
<div class="table-responsive">
<table id="tabelPelanggaran" class="table table-bordered table-striped table-hover align-middle">
<thead>
<tr>
<th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th>
<th>Pelanggaran</th><th>Poin</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$no=1;
$data=mysqli_query($koneksi,"SELECT ps.id,ps.tanggal,ps.poin,s.nama AS nama_siswa_asli,k.nama AS nama_kelas_asli,p.nama AS nama_pelanggaran_asli FROM t_pelanggaran_siswa ps LEFT JOIN t_siswa s ON ps.siswa_id=s.id LEFT JOIN t_kelas k ON ps.kelas_id=k.id LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id=p.id ORDER BY ps.tanggal DESC,ps.id DESC");
while($data && $row=mysqli_fetch_assoc($data)):
?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($row['tanggal']??'') ?></td>
<td><?= htmlspecialchars($row['nama_siswa_asli']??'-') ?></td>
<td><?= htmlspecialchars($row['nama_kelas_asli']??'-') ?></td>
<td><?= htmlspecialchars($row['nama_pelanggaran_asli']??'-') ?></td>
<td><span class="badge bg-danger"><?= (int)$row['poin'] ?></span></td>
<td>
<a href="catat_pelanggaran.php?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="catat_pelanggaran.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){
    $('#tabelPelanggaran').DataTable({
        language:{
            lengthMenu:'Tampilkan _MENU_ data',
            search:'Cari data:',
            info:'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty:'Tidak ada data',
            zeroRecords:'Data tidak ditemukan',
            emptyTable:'Belum ada data pelanggaran',
            paginate:{
                first:'Pertama',
                previous:'Sebelumnya',
                next:'Berikutnya',
                last:'Terakhir'
            }
        },
        pageLength:10,
        order:[[1,'desc']],
        columnDefs:[{targets:[0,6],orderable:false}]
    });
});
</script>

</body>
</html>
