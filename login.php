<?php
include "includes/config.php";

// Cek jika form login sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query untuk mencari user dengan username yang sesuai
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        // Jika user ditemukan, cek passwordnya
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user["password"])) {
            // Jika password sesuai, simpan session dan redirect ke halaman utama
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Username atau password salah.";
        }
    } else {
        $error_message = "Username atau password salah.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - SERVQUAL & SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            font-family: 'Montserrat', sans-serif;
        }

        .login-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 30px;
            height : 400px;
            width : 500px;
        }

        .login-card h2 {
            color: #8e2de2;
            font-weight: bold;
        }

        .login-card .form-control {
            border-radius: 5px;
        }

        .login-card .btn-primary {
            background-color: #8e2de2;
            border-color: #8e2de2;
            border-radius: 5px;
            font-weight: bold;
        }

        .login-card .btn-primary:hover {
            background-color: #4a00e0;
            border-color: #4a00e0;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="login-card">
            <h2 class="text-center mb-4">Aplikasi Servqual & Saw</h2>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                    <label for="username" class="text-muted">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password" class="text-muted">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Daftar</a></p>
        </div>
    </div>
</body>
</html>