<?php
include "includes/config.php";
include "header.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Ambil data dimensi_servqual dari database
$dimensi_query = mysqli_query($koneksi, "SELECT * FROM dimensi_servqual");
$dimensi_data = array();
while ($row = mysqli_fetch_assoc($dimensi_query)) {
    $dimensi_data[] = $row;
}

// Proses input dimensi_servqual baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"])) {
    $nama_dimensi = $_POST["nama_dimensi"];

    // Validasi input
    if (empty($nama_dimensi)) {
        $error_message = "Nama dimensi harus diisi.";
    } else {
        // Simpan data dimensi_servqual baru ke database
        $query = "INSERT INTO dimensi_servqual (nama_dimensi) VALUES ('$nama_dimensi')";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Dimensi berhasil ditambahkan.";
            // Refresh data dimensi_servqual
            $dimensi_query = mysqli_query($koneksi, "SELECT * FROM dimensi_servqual");
            $dimensi_data = array();
            while ($row = mysqli_fetch_assoc($dimensi_query)) {
                $dimensi_data[] = $row;
            }
        } else {
            $error_message = "Terjadi kesalahan saat menambahkan dimensi.";
        }
    }
}

// Proses edit dimensi_servqual
if (isset($_GET["edit"])) {
    $id_dimensi = $_GET["edit"];
    $query = "SELECT * FROM dimensi_servqual WHERE id_dimensi = $id_dimensi";
    $result = mysqli_query($koneksi, $query);
    $dimensi = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id_dimensi = $_POST["id"];
    $nama_dimensi = $_POST["nama_dimensi"];

    // Validasi input
    if (empty($nama_dimensi)) {
        $error_message = "Nama dimensi harus diisi.";
    } else {
        // Update data dimensi_servqual di database
        $query = "UPDATE dimensi_servqual SET nama_dimensi = '$nama_dimensi' WHERE id_dimensi = $id_dimensi";
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Data dimensi_servqual berhasil diupdate.";
            // Refresh data dimensi_servqual
            $dimensi_query = mysqli_query($koneksi, "SELECT * FROM dimensi_servqual");
            $dimensi_data = array();
            while ($row = mysqli_fetch_assoc($dimensi_query)) {
                $dimensi_data[] = $row;
            }
        } else {
            $error_message = "Terjadi kesalahan saat meng-update data dimensi_servqual.";
        }
    }
}

// Proses hapus dimensi_servqual
if (isset($_GET["delete"])) {
    $id_dimensi = $_GET["delete"];
    $query = "DELETE FROM dimensi_servqual WHERE id_dimensi = $id_dimensi";
    if (mysqli_query($koneksi, $query)) {
        $success_message = "Data dimensi_servqual berhasil dihapus.";
        // Refresh data dimensi_servqual
        $dimensi_query = mysqli_query($koneksi, "SELECT * FROM dimensi_servqual");
        $dimensi_data = array();
        while ($row = mysqli_fetch_assoc($dimensi_query)) {
            $dimensi_data[] = $row;
        }
    } else {
        $error_message = "Terjadi kesalahan saat menghapus data dimensi_servqual.";
    }
}
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Dimensi SERVQUAL</h4>
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
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahDimensiModal">
                            Tambah Dimensi
                        </button>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dimensi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($dimensi_data as $dimensi): ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $dimensi['nama_dimensi']; ?></td>
                                        <td>
                                            <a href='#' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal<?php echo $dimensi['id_dimensi']; ?>'>Edit</a>
                                            <a href='?delete=<?php echo $dimensi['id_dimensi']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Apakah Anda yakin ingin menghapus data ini?");'>Hapus</a>
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

    <!-- Modal untuk tambah dimensi_servqual -->
    <div class="modal" id="tambahDimensiModal" tabindex="-1" role="dialog" aria-labelledby="tambahDimensiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDimensiModalLabel">Tambah Dimensi SERVQUAL</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_dimensi">Nama Dimensi:</label>
                            <input type="text" class="form-control" id="nama_dimensi" name="nama_dimensi" required>
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

    <!-- Modal untuk edit dimensi_servqual -->
    <?php foreach ($dimensi_data as $dimensi): ?>
        <div class="modal fade" id="editModal<?php echo $dimensi['id_dimensi']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $dimensi['id_dimensi']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?php echo $dimensi['id_dimensi']; ?>">Edit Dimensi SERVQUAL</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $dimensi['id_dimensi']; ?>"> 
                            <div class="form-group">
                                <label for="edit_nama_dimensi">Nama Dimensi:</label>
                                <input type="text" class="form-control" id="edit_nama_dimensi" name="nama_dimensi" value="<?php echo $dimensi['nama_dimensi']; ?>" required>
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
