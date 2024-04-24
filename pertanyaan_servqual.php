<?php
include "includes/config.php";
include "header.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

//ambil data dimensi
$dimensi_query = mysqli_query($koneksi, "SELECT * FROM dimensi_servqual");
$dimensi_data = array();
while ($row = mysqli_fetch_assoc($dimensi_query)) {
    $dimensi_data[] = $row;
}

// Ambil data pertanyaan_servqual dari database
$pertanyaan_query = mysqli_query($koneksi, "SELECT * FROM pertanyaan_servqual INNER JOIN dimensi_servqual ON pertanyaan_servqual.id_dimensi = dimensi_servqual.id_dimensi");
$pertanyaan_data = array();
while ($row = mysqli_fetch_assoc($pertanyaan_query)) {
    $pertanyaan_data[] = $row;
}

// Proses input pertanyaan_servqual baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"])) {
    $id_dimensi = $_POST["id_dimensi"];
    $pertanyaan = $_POST["pertanyaan"];

    // Validasi input
    if (empty($id_dimensi) || empty($pertanyaan)) {
        $error_message = "Semua field harus diisi.";
    } else {
        // Simpan data pertanyaan_servqual baru ke database
        $query = "INSERT INTO pertanyaan_servqual (id_dimensi, pertanyaan) VALUES ('$id_dimensi', '$pertanyaan')";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Pertanyaan berhasil ditambahkan.";
            // Refresh data pertanyaan_servqual
            $pertanyaan_query = mysqli_query($koneksi, "SELECT * FROM pertanyaan_servqual INNER JOIN dimensi_servqual ON pertanyaan_servqual.id_dimensi = dimensi_servqual.id_dimensi");
            $pertanyaan_data = array();
            while ($row = mysqli_fetch_assoc($pertanyaan_query)) {
                $pertanyaan_data[] = $row;
            }
        } else {
            $error_message = "Terjadi kesalahan saat menambahkan pertanyaan.";
        }
    }
}

// Proses edit pertanyaan_servqual
if (isset($_GET["edit"])) {
    $id_pertanyaan = $_GET["edit"];
    $query = "SELECT * FROM pertanyaan_servqual WHERE id_pertanyaan = $id_pertanyaan " ;
    $result = mysqli_query($koneksi, $query);
    $pertanyaan = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id_pertanyaan = $_POST["id"];
    $id_dimensi = $_POST["id_dimensi"];
    $pertanyaan = $_POST["pertanyaan"];

    // Validasi input
    if (empty($id_dimensi) || empty($pertanyaan)) {
        $error_message = "Semua field harus diisi.";
    } else {
        // Update data pertanyaan_servqual di database
        $query = "UPDATE pertanyaan_servqual SET id_dimensi = '$id_dimensi', pertanyaan = '$pertanyaan' WHERE id_pertanyaan = $id_pertanyaan";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Pertanyaan berhasil diupdate.";
            // Refresh data pertanyaan_servqual
            $pertanyaan_query = mysqli_query($koneksi, "SELECT * FROM pertanyaan_servqual INNER JOIN dimensi_servqual ON pertanyaan_servqual.id_dimensi = dimensi_servqual.id_dimensi");
            $pertanyaan_data = array();
            while ($row = mysqli_fetch_assoc($pertanyaan_query)) {
                $pertanyaan_data[] = $row;
            }
        } else {
            $error_message = "Terjadi kesalahan saat meng-update data pertanyaan.";
        }
    }
}

// Proses hapus pertanyaan_servqual
if (isset($_GET["delete"])) {
    $id_pertanyaan = $_GET["delete"];
    $query = "DELETE FROM pertanyaan_servqual WHERE id_pertanyaan = $id_pertanyaan";
    if (mysqli_query($koneksi, $query)) {
        $success_message = "Pertanyaan berhasil dihapus.";
        // Refresh data pertanyaan_servqual
        $pertanyaan_query = mysqli_query($koneksi, "SELECT * FROM pertanyaan_servqual INNER JOIN dimensi_servqual ON pertanyaan_servqual.id_dimensi = dimensi_servqual.id_dimensi");
        $pertanyaan_data = array();
        while ($row = mysqli_fetch_assoc($pertanyaan_query)) {
            $pertanyaan_data[] = $row;
        }
    } else {
        $error_message = "Terjadi kesalahan saat menghapus data pertanyaan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Pertanyaan SERVQUAL</title>
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
                        <h4>Daftar Pertanyaan SERVQUAL</h4>
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
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahPertanyaanModal">
                            Tambah Pertanyaan
                        </button>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dimensi</th>
                                    <th>Pertanyaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($pertanyaan_data as $pertanyaan): ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $pertanyaan['nama_dimensi']; ?></td>
                                        <td><?php echo $pertanyaan['pertanyaan']; ?></td>
                                        <td>
                                            <a href='#' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal<?php echo $pertanyaan['id_pertanyaan']; ?>'>Edit</a>
                                            <a href='?delete=<?php echo $pertanyaan['id_pertanyaan']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Apakah Anda yakin ingin menghapus data ini?");'>Hapus</a>
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

    <!-- Modal untuk tambah pertanyaan_servqual -->
    <div class="modal" id="tambahPertanyaanModal" tabindex="-1" role="dialog" aria-labelledby="tambahPertanyaanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPertanyaanModalLabel">Tambah Pertanyaan SERVQUAL</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_dimensi">Dimensi</label>
                            <select name="id_dimensi" id="id_dimensi" class="form-control">
                                <option value="">Pilih Dimensi</option>
                                <?php foreach ($dimensi_data as $dimensi): ?>
                                    <option value="<?php echo $dimensi['id_dimensi']; ?>"><?php echo $dimensi['nama_dimensi']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pertanyaan">Pertanyaan:</label>
                            <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" required>
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

    <!-- Modal untuk edit pertanyaan_servqual -->
    <?php foreach ($pertanyaan_data as $pertanyaan): ?>
        <div class="modal fade" id="editModal<?php echo $pertanyaan['id_pertanyaan']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $pertanyaan['id_pertanyaan']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?php echo $pertanyaan['id_pertanyaan']; ?>">Edit Pertanyaan SERVQUAL</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $pertanyaan['id_pertanyaan']; ?>"> 
                            <div class="form-group">
                                <label for="edit_id_dimensi">ID Dimensi:</label>
                                <input type="text" class="form-control" id="edit_id_dimensi" name="id_dimensi" value="<?php echo $pertanyaan['id_dimensi']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_pertanyaan">Pertanyaan:</label>
                                <input type="text" class="form-control" id="edit_pertanyaan" name="pertanyaan" value="<?php echo $pertanyaan['pertanyaan']; ?>" required>
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
    <?php endforeach; ?>

    <?php include "footer.php"; ?>
</body>
</html>
