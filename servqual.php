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

// Ambil data dimensi SERVQUAL dari database
$dimensi_query = mysqli_query($koneksi, "SELECT * FROM dimensi_servqual");
$dimensi_data = array();
while ($row = mysqli_fetch_assoc($dimensi_query)) {
    $dimensi_data[] = $row;
}

// Ambil data pertanyaan SERVQUAL dari database
$pertanyaan_query = mysqli_query($koneksi, "SELECT * FROM pertanyaan_servqual");
$pertanyaan_data = array();
while ($row = mysqli_fetch_assoc($pertanyaan_query)) {
    $pertanyaan_data[] = $row;
}

// Proses simpan penilaian SERVQUAL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_konsumen = $_POST["id_konsumen"];
    $id_pertanyaan = $_POST["id_pertanyaan"];
    $nilai_persepsi = $_POST["nilai_persepsi"];
    $nilai_harapan = $_POST["nilai_harapan"];

    // Simpan penilaian SERVQUAL ke database
    $query = "INSERT INTO penilaian_servqual (id_konsumen, id_pertanyaan, nilai_persepsi, nilai_harapan) VALUES ('$id_konsumen', '$id_pertanyaan', '$nilai_persepsi', '$nilai_harapan')";
    if (mysqli_query($koneksi, $query)) {
        $success_message = "Penilaian SERVQUAL berhasil disimpan.";
    } else {
        $error_message = "Terjadi kesalahan saat menyimpan penilaian SERVQUAL.";
    }
}
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Penilaian SERVQUAL</h4>
        </div>
        <div class="card-body">
            <?php if (isset($success_message)) { ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php } ?>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="id_konsumen">Konsumen:</label>
                    <select class="form-control" id="id_konsumen" name="id_konsumen" required>
                        <option value="">Pilih Konsumen</option>
                        <?php foreach ($konsumen_data as $konsumen) { ?>
                            <option value="<?php echo $konsumen['id']; ?>"><?php echo $konsumen['fullname']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Dimensi</th>
                            <th>Pertanyaan</th>
                            <th>Nilai Persepsi (1-5)</th>
                            <th>Nilai Harapan (1-5)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pertanyaan_data as $pertanyaan) { ?>
                            <tr>
                                <td><?php echo $dimensi_data[$pertanyaan['id_dimensi'] - 1]['nama_dimensi']; ?></td>
                                <td><?php echo $pertanyaan['pertanyaan']; ?></td>
                                <td>
                                    <input type="number" class="form-control" name="nilai_persepsi[<?php echo $pertanyaan['id_pertanyaan']; ?>]" min="1" max="5" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="nilai_harapan[<?php echo $pertanyaan['id_pertanyaan']; ?>]" min="1" max="5" required>
                                </td>
                                <input type="hidden" name="id_pertanyaan[]" value="<?php echo $pertanyaan['id_pertanyaan']; ?>">
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
            </form>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>
