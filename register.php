<?php
include "includes/config.php";

// Cek jika form pendaftaran sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $notelpon = $_POST["notelpon"];
    $role = "konsumen"; // Role default

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan user baru ke database
    $query = "INSERT INTO users (username, password, fullname, email, notelpon, role) VALUES ('$username', '$hashed_password', '$fullname', '$email', '$notelpon', '$role')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Jika pendaftaran berhasil, redirect ke halaman login
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Gagal melakukan pendaftaran. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - SERVQUAL & SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            font-family: 'Montserrat', sans-serif;
        }

        .register-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 30px;
            height: 600px;
            width: 500px;
        }

        .register-card h2 {
            color: #8e2de2;
            font-weight: bold;
        }

        .register-card .form-control {
            border-radius: 5px;
        }

        .register-card .btn-primary {
            background-color: #8e2de2;
            border-color: #8e2de2;
            border-radius: 5px;
            font-weight: bold;
        }

        .register-card .btn-primary:hover {
            background-color: #4a00e0;
            border-color: #4a00e0;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="register-card">
            <h2 class="text-center mb-4">Daftar Akun</h2>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                    <label for="fullname" class="text-muted">Full Name:</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="username" class="text-muted">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password" class="text-muted">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="email" class="text-muted">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="notelpon" class="text-muted">No. Telepon:</label>
                    <input type="text" class="form-control" id="notelpon" name="notelpon" required>
                </div>
                <!-- Input untuk role dengan nilai default 'user' dan disabled -->
                <input type="hidden" name="role" value="user">
                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
