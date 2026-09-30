<?php

session_start();

include "config/koneksi.php";

$error = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string(
        $koneksi,
        $_POST['email']
    );

    $password = $_POST['password'];

    if ($email == "" || $password == "") {

        $error = "Email dan password harus diisi!";

    } else {

        $query = mysqli_query(
            $koneksi,
            "SELECT * FROM t_users 
             WHERE email = '$email'
             AND role IN ('admin', 'guru')
             LIMIT 1"
        );

        if (!$query) {

            die("Query gagal: " . mysqli_error($koneksi));

        }

        if (mysqli_num_rows($query) > 0) {

            $user = mysqli_fetch_assoc($query);

            /*
             * Mengecek password yang sudah di-hash
             */
            if ($password == $user['password']) {
                

                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Password salah!";

            }

        } else {

            $error = "Email tidak ditemukan atau akun tidak memiliki akses!";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body>
<body class="bg-primary bg-opacity-10 min-vh-100 d-flex justify-content-center align-items-center">
<body class="bg-light min-vh-100 d-flex justify-content-center align-items-center">
<div  class="container text-center">
<div class="card shadow p-4 rounded-4 bg-white col-md-5 mx-auto">
<h2>LOGIN</h2>

<?php

if ($error != "") {

    echo "<p>$error</p>";

}

?>

<form method="POST">
    <p>
        Email
        <br>
        <input
            type="email"
            name="email"
            required
        >
    </p>

    <p>
        Password
        <br>
        <input
            type="password"
            name="password"
            required
        >
    </p>

    <p>
        <button
        class="btn btn-outline-primary"
            type="submit"
            name="login"
        >
            Login
        </button>
    </p>

</form>
</div>
</body>

</html>