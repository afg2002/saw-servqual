<?php
include "includes/config.php";
include "header.php";

if (!isset($_SESSION["user_id"])) {
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

// ambil data jawaban lama
$jawaban_lama_query = mysqli_query($koneksi, "SELECT * FROM penilaian_servqual WHERE id_konsumen = '$_SESSION[user_id]'");
$jawaban_lama_data = array();
while ($row = mysqli_fetch_assoc($jawaban_lama_query)) {
    $jawaban_lama_data[] = $row;
}



// Proses simpan atau update penilaian SERVQUAL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['jawab_ulang'])) {
            // Hapus jawaban lama jika tombol jawab ulang ditekan
            $id_konsumen = $_SESSION['user_id']; // Pastikan ID konsumen diambil dengan benar
            $delete_query = "DELETE FROM penilaian_servqual WHERE id_konsumen = '$id_konsumen'";
            $delete_result = mysqli_query($koneksi, $delete_query);
            if ($delete_result) {
                // Tambahkan pesan sukses jika penghapusan berhasil
                $success_message = "Jawaban lama berhasil dihapus. Silakan isi penilaian baru.";
                header("Refresh:1");
            } else {
                // Tambahkan pesan error jika penghapusan gagal
                $error_message = "Terjadi kesalahan saat menghapus jawaban lama: " . mysqli_error($koneksi);
            }
        }
    }
    if(isset($_POST['simpan'])){
        // Simpan jawaban baru
        $id_konsumen = $_POST["id_konsumen"];
        $success = true;
        

        foreach ($_POST['id_pertanyaan'] as $index => $id_pertanyaan) {
            $nilai_persepsi = isset($_POST["nilai_persepsi"][$id_pertanyaan]) ? (int)$_POST["nilai_persepsi"][$id_pertanyaan] : 0;
            $nilai_harapan = isset($_POST["nilai_harapan"][$id_pertanyaan]) ? (int)$_POST["nilai_harapan"][$id_pertanyaan] : 0;
            // Simpan penilaian SERVQUAL ke database
            $query = "INSERT INTO penilaian_servqual (id_konsumen, id_pertanyaan, nilai_persepsi, nilai_harapan) VALUES ('$id_konsumen', '$id_pertanyaan', '$nilai_persepsi', '$nilai_harapan')";
            if (!mysqli_query($koneksi, $query)) {
                $success = false;
                $error_message = "Terjadi kesalahan saat menyimpan penilaian SERVQUAL: " . mysqli_error($koneksi);
                break;
            }   
        }
        if ($success) {
            $success_message = "Penilaian SERVQUAL berhasil disimpan.";
            header("Refresh:1");
        }
    }


// Ambil data penilaian yang sudah ada jika ada
$penilaian_exist_query = mysqli_query($koneksi, "SELECT * FROM penilaian_servqual WHERE id_konsumen = '$_SESSION[user_id]'");
$penilaian_exist_data = array();
while ($row = mysqli_fetch_assoc($penilaian_exist_query)) {
    $penilaian_exist_data[$row['id_pertanyaan']] = $row;
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
                    <?php if ($_SESSION['role'] === 'admin') { ?>
                        <select class="form-control" id="id_konsumen" name="id_konsumen" required>
                            <option value="">Pilih Konsumen</option>
                            <?php foreach ($konsumen_data as $konsumen) { ?>
                                <option value="<?php echo $konsumen['id']; ?>"><?php echo $konsumen['fullname']; ?></option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <input type="hidden" id="id_konsumen" name="id_konsumen" value="<?php echo $_SESSION['user_id']; ?>">
                        <p><?php echo $_SESSION['fullname']; ?></p>
                    <?php } ?>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Dimensi</th>
                            <th>Pertanyaan</th>
                            <th>Bagaimana pendapat anda tentang pelayanan yang diberikan ?</th>
                            <th>Seberapa pentingnya aspek ini menurut anda ?</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pertanyaan_data as $pertanyaan) { ?>
                        <tr>
                            <td><?php echo $dimensi_data[$pertanyaan['id_dimensi'] - 1]['nama_dimensi']; ?></td>
                            <td><?php echo $pertanyaan['pertanyaan']; ?></td>
                            <td>
                                <div class="rateYo" data-rateyo-name="nilai_persepsi[<?php echo $pertanyaan['id_pertanyaan']; ?>]" data-rateyo-rating="<?php echo isset($penilaian_exist_data[$pertanyaan['id_pertanyaan']]) ? $penilaian_exist_data[$pertanyaan['id_pertanyaan']]['nilai_persepsi'] : 0; ?>" <?php echo count($jawaban_lama_data) > 0 ? 'data-rateyo-read-only="true"' : ''; ?>></div>
                            </td>
                            <td>
                                <div class="rateYo" data-rateyo-name="nilai_harapan[<?php echo $pertanyaan['id_pertanyaan']; ?>]" data-rateyo-rating="<?php echo isset($penilaian_exist_data[$pertanyaan['id_pertanyaan']]) ? $penilaian_exist_data[$pertanyaan['id_pertanyaan']]['nilai_harapan'] : 0; ?>" <?php echo count($jawaban_lama_data) > 0 ? 'data-rateyo-read-only="true"' : ''; ?>></div>
                            </td>
                            <input type="hidden" name="id_pertanyaan[]" value="<?php echo $pertanyaan['id_pertanyaan']; ?>">
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php if (count($jawaban_lama_data) == 0) { ?>
                    <button type="submit" name="simpan" value="simpan" class="btn btn-primary">Simpan Penilaian</button>
                <?php } else { ?>
                    <button type="submit" name="jawab_ulang" value="jawab_ulang" class="btn btn-warning">Jawab Ulang</button>
                <?php
                    
            } ?>
            </form>
        </div>
    </div>
</div>
<script src="assets/js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="assets/css/jquery.rateyo.min.css">
<script src="assets/js/jquery.rateyo.min.js"></script>
<script>
jQuery(document).ready(function($) {
    $(".rateYo").rateYo({
        fullStar: true,
        onSet: function (rating, rateYoInstance) {
            var name = $(this).data("rateyo-name");
            var input = '<input type="hidden" name="' + name + '" value="' + rating + '">';
            $(this).append(input);
        }
    });
});
</script>
<?php include "footer.php"; ?>
</body>
</html>
