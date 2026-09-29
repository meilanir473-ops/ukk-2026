<?php
include 'config/koneksi.php';

// Data User 1: Admin
$nama     = 'Administrator';
$email    = 'admin@gmail.com';
$password = '123456';
$role = 'admin';

// Data User 2: Guru
$nama    = 'guru ajaran';
$email    = 'guru@gmail.com';
$password  = '123456';
$role        = 'guru';

$sql = "INSERT INTO t_users (name, email, password, role)";
$sql .= "VALUES ('$nama', '$email', '$password', '$role')";

// Menggunakan mysqli_query yang benar: (koneksi, query)
if (mysqli_query($koneksi, $sql)) {
    echo 'User Admin dan guru berhasil dibuat. Silahkan hapus file ini.';
} else {
    echo 'Gagal membuat user: ' . mysqli_error($koneksi);
}
?>