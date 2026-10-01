<?php

session_start();

if (!isset($_SESSION['id'])) {

    header("Location: login.php");
    exit;

}

$nama = $_SESSION['name'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>About Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR (Menyesuaikan dengan Dashboard) -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 d-flex flex-column justify-content-between">
            <div>
                <h4 class="text-white mb-4">
                Sistem Pelanggaran
                </h4>
                
                <ul class="nav flex-column">
                    <?php if ($role === 'admin'): ?>
                    <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item mb-2"><a href="aboutme.php" class="nav-link active bg-primary text-white rounded">About Me</a></li>
                    <?php endif; ?>

                    <!-- Menu untuk Guru & Admin -->
                    
                    <?php if ($role === 'guru'): ?>
                    <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item mb-2"><a href="aboutme.php" class="nav-link active bg-primary text-white rounded">About Me</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- KONTEN ABOUT ME -->
        <main class="col-md-9 col-lg-10 p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
             
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-4 mb-md-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 130px; height: 130px; font-size: 50px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($nama); ?></h3>
                        <p class="text-muted mb-2">Hak Akses / Role: <strong><?php echo ucfirst($role); ?></strong></p>
                       <h3>Nama saya : Rani Meilani</h3>
                        <div class="mt-3">
                            <span class="badge bg-secondary me-1 p-2">PHP Native</span>
                            <span class="badge bg-secondary me-1 p-2">MySQL</span>
                            <span class="badge bg-secondary me-1 p-2">Bootstrap 5</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>