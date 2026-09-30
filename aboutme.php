<?php
$nama = "Rani Meilani";
$jurusan = "Rekayasa Perangkat Lunak";
$sekolah = "SMK Muhammadiyah";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card shadow p-4 rounded-4 text-center bg-white" style="width: 350px;">
        <h3 class="fw-bold mb-3">Tentang Saya</h3>
        <p class="mb-2"><strong>Nama:</strong> <?= $nama; ?></p>
        <p class="mb-2"><strong>Jurusan:</strong> <?= $jurusan; ?></p>
        <p class="mb-4"><strong>Sekolah:</strong> <?= $sekolah; ?></p>
        <a href="dashboard.php" class="btn btn-dark btn-sm w-100">Kembali ke Dashboard</a>
    </div>

</body>
</html>