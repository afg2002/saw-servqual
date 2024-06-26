<?php
session_start();
if(!isset($_SESSION['username'])) {
  header('location: login.php');
  exit;
}

// Logout logic
if(isset($_GET['logout'])) {
    session_destroy();
    header('location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Servqual & SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome/css/fontawesome-all.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Servqual & SAW</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">Home<span class="sr-only">(current)</span></a>
                </li>

                <?php
                // Check if user is logged in and has admin role
                if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "admin") {
                ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="admin.php"> Admin </a>
                    </li>
                <?php } ?>

                    <?php
                    // Check if user is logged in and has admin role
                    if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "admin") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="konsumen.php"> Konsumen </a>
                        </li>
                        

                    <?php } ?>
                    <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Servqual
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="servqual.php">Kuisioner</a>
                                <?php if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "admin") { ?>
                                    <a class="dropdown-item" href="dimensi_servqual.php">Dimensi</a>
                                    <a class="dropdown-item" href="pertanyaan_servqual.php">Pertanyaan Servqual</a>
                                <?php } ?>
                            </div>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                               
                            </div>
                        </li>

                   
                    <?php
                    // Check if user is logged in and has admin role
                    if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "admin") {
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                SAW
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="nilai.php">Nilai</a>
                                <a class="dropdown-item" href="kriteria.php">Kriteria</a>
                                <a class="dropdown-item" href="alternatif.php">Alternatif</a>
                                <a class="dropdown-item" href="rangking.php">Rangking</a>
                            </div>
                        </li>
                    <?php } ?>

            </ul>

            <button class="btn btn-warning mr-3 text-white"> <?php echo $_SESSION['fullname'] ?></button>
            
            <!-- Logout Button -->
            <a href="?logout" class="btn btn-dark">Logout</a>
            
        </div>
    </div>
</nav>
