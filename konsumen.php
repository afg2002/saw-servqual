<?php
include "includes/config.php";
include "header.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
// Ambil data konsumen dari database
$konsumen_query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'konsumen'");
$konsumen_data = array();
while ($row = mysqli_fetch_assoc($konsumen_query)) {
    $konsumen_data[] = $row;
}

// Proses input konsumen baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"]))  {
    $nama_konsumen = $_POST["nama_konsumen"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $no_telp = $_POST["no_telp"];
    $alamat = $_POST["alamat"];
    $role = "konsumen";

    // Validasi input
    if ( empty($username) || empty($nama_konsumen) || empty($email) || empty($no_telp) || empty($alamat)) {
        $error_message = "Semua field harus diisi.";
    } else {
        //Password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data konsumen baru ke database
        $query = "INSERT INTO users ( username, password ,fullname, email, no_telp, alamat, role) VALUES ( '$username', '$hashed_password','$nama_konsumen', '$email', '$no_telp', '$alamat', '$role')";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Data konsumen berhasil disimpan.";
            // Refresh data konsumen
            $konsumen_query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'konsumen'");
            $konsumen_data = array();
            while ($row = mysqli_fetch_assoc($konsumen_query)) {
                $konsumen_data[] = $row;
            }
        } else {
            $error_message = "Terjadi kesalahan saat menyimpan data konsumen.";
        }
    }
}

// Proses edit konsumen
if (isset($_GET["edit"])) {
    $id_konsumen = $_GET["edit"];
    $query = "SELECT * FROM users WHERE id = $id_konsumen";
    $result = mysqli_query($koneksi, $query);
    $konsumen = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id_konsumen = $_POST["id"];
    $username = $_POST["username"];
    $nama_konsumen = $_POST["nama_konsumen"];
    $email = $_POST["email"];
    $no_telp = $_POST["no_telp"];
    $alamat = $_POST["alamat"];
    $role = "konsumen";


    // Validasi input
    if (empty($nama_konsumen) || empty($email) || empty($no_telp) || empty($alamat)) {
        $error_message = "Semua field harus diisi.";
    } else {
        // Update data konsumen di database
        $query = "UPDATE users SET fullname = '$nama_konsumen', email = '$email', no_telp = '$no_telp', alamat = '$alamat', role = '$role'";
        
        // Cek apakah password diisi dalam form
        if (!empty($_POST["password"])) {
            $password = $_POST["password"];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", password = '$hashed_password'";
        }

        $query .= " WHERE id = $id_konsumen";
        
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Data konsumen berhasil diupdate.";
            // Refresh data konsumen
            $konsumen_query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'konsumen'");
            $konsumen_data = array();
            while ($row = mysqli_fetch_assoc($konsumen_query)) {
                $konsumen_data[] = $row;
            }
        } else {
            $error_message = "Terjadi kesalahan saat meng-update data konsumen.";
        }
    }
}

// Proses hapus konsumen
if (isset($_GET["delete"])) {
    $id_konsumen = $_GET["delete"];
    $query = "DELETE FROM users WHERE id = $id_konsumen";
    if (mysqli_query($koneksi, $query)) {
        $success_message = "Data konsumen berhasil dihapus.";
        // Refresh data konsumen
        $konsumen_query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'konsumen'");
        $konsumen_data = array();
        while ($row = mysqli_fetch_assoc($konsumen_query)) {
            $konsumen_data[] = $row;
        }
    } else {
        $error_message = "Terjadi kesalahan saat menghapus data konsumen.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konsumen - SERVQUAL & SAW</title>
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f1f1f1;
            font-family: 'Montserrat', sans-serif;
        }

        .card {
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Konsumen</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success_message)) { ?>
                            <div class="alert alert-success alert-dismisible fade show"><?php echo $success_message; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger alert-dismisible fade show"><?php echo $error_message; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahKonsumen">
                            Tambah Konsumen
                        </button>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Konsumen</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            <?php 
                            $no = 1;
                            foreach ($konsumen_data as $row): ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['no_telp']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td>
                                        <a href='#' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal<?php echo $row['id']; ?>'>Edit</a>
                                        <a href='?delete=<?php echo $row['id']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Apakah Anda yakin ingin menghapus data ini?");'>Hapus</a>
                                    </td>
                                </tr>
                                <?php $no++; ?> 
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah konsumen -->
    <div class="modal" id="tambahKonsumen" tabindex="-1" role="dialog" aria-labelledby="tambahKonsumenLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKonsumenLabel">Tambah Konsumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
                    
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="nama_konsumen">Nama Konsumen:</label>
                            <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No. Telepon:</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="insert" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk edit konsumen -->
<?php foreach ($konsumen_data as $row) { ?>
    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Konsumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>"> 
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" value="" >
                        </div>
                        <div class="form-group">
                            <label for="nama_konsumen">Nama Konsumen:</label>
                            <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen" value="<?php echo $row['fullname']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No. Telepon:</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $row['no_telp']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $row['alamat']; ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>



<?php include "footer.php"; ?>

</body>
</html>
