<?php
include "includes/config.php";
include "includes/functions.php";

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Ambil data konsumen dari database
$konsumen_query = mysqli_query($koneksi, "SELECT * FROM konsumen");
$konsumen_data = array();
while ($row = mysqli_fetch_assoc($konsumen_query)) {
    $konsumen_data[] = $row;
}

// Proses input konsumen baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_konsumen = $_POST["nama_konsumen"];
    $email = $_POST["email"];
    $no_telp = $_POST["no_telp"];
    $alamat = $_POST["alamat"];

    // Validasi input
    if (empty($nama_konsumen) || empty($email) || empty($no_telp) || empty($alamat)) {
        $error_message = "Semua field harus diisi.";
    } else {
        // Simpan data konsumen baru ke database
        $query = "INSERT INTO konsumen (nama_konsumen, email, no_telp, alamat) VALUES ('$nama_konsumen', '$email', '$no_telp', '$alamat')";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Data konsumen berhasil disimpan.";
            // Refresh data konsumen
            $konsumen_query = mysqli_query($koneksi, "SELECT * FROM konsumen");
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
    $query = "SELECT * FROM konsumen WHERE id_konsumen = $id_konsumen";
    $result = mysqli_query($koneksi, $query);
    $konsumen = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id_konsumen = $_POST["id_konsumen"];
    $nama_konsumen = $_POST["nama_konsumen"];
    $email = $_POST["email"];
    $no_telp = $_POST["no_telp"];
    $alamat = $_POST["alamat"];

    // Validasi input
    if (empty($nama_konsumen) || empty($email) || empty($no_telp) || empty($alamat)) {
        $error_message = "Semua field harus diisi.";
    } else {
        // Update data konsumen di database
        $query = "UPDATE konsumen SET nama_konsumen = '$nama_konsumen', email = '$email', no_telp = '$no_telp', alamat = '$alamat' WHERE id_konsumen = $id_konsumen";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Data konsumen berhasil diupdate.";
            // Refresh data konsumen
            $konsumen_query = mysqli_query($koneksi, "SELECT * FROM konsumen");
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
    $query = "DELETE FROM konsumen WHERE id_konsumen = $id_konsumen";
    if (mysqli_query($koneksi, $query)) {
        $success_message = "Data konsumen berhasil dihapus.";
        // Refresh data konsumen
        $konsumen_query = mysqli_query($koneksi, "SELECT * FROM konsumen");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f1f1f1;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
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
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php } ?>
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php } ?>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahKonsumen">
                            <i class="fas fa-plus"></i> Tambah Konsumen
                        </button>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
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
                                foreach ($konsumen_data as $row) {
                                    echo "<tr>";
                                    echo "<td>$no</td>";
                                    echo "<td>{$row['nama_konsumen']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['no_telp']}</td>";
                                    echo "<td>{$row['alamat']}</td>";
                                    echo "<td>
                                        <a href='?edit={$row['id_konsumen']}' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>
                                        <a href='?delete={$row['id_konsumen']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'><i class='fas fa-trash'></i></a>
                                    </td>";
                                    echo "</tr>";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah konsumen -->
    <div class="modal fade" id="tambahKonsumen" tabindex="-1" aria-labelledby="tambahKonsumenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKonsumenLabel">Tambah Konsumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk edit konsumen -->
    <?php if (isset($_GET["edit"])) { ?>
        <div class="modal fade" id="editKonsumen" tabindex="-1" aria-labelledby="editKonsumenLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKonsumenLabel">Edit Konsumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="modal-body">
                            <input type="hidden" name="id_konsumen" value="<?php echo $konsumen['id_konsumen']; ?>">
                            <div class="form-group">
                                <label for="nama_konsumen">Nama Konsumen:</label>
                                <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen" value="<?php echo $konsumen['nama_konsumen']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $konsumen['email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="no_telp">No. Telepon:</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $konsumen['no_telp']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat:</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $konsumen['alamat']; ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>