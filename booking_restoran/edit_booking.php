
<?php


session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM bookings WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Booking</h2>
        <form id="editBookingForm" method="post" action="update_booking.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_booking">Tanggal Booking</label>
                <input type="date" class="form-control" id="tanggal_booking" name="tanggal_booking" value="<?php echo $row['tanggal_booking']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nomor_hp">Nomor HP</label>
                <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" value="<?php echo $row['nomor_hp']; ?>" required>
            </div>
            <h4>Menu Makanan</h4>
            <div class="form-group">
                <label for="ayam_bakar">Ayam Bakar</label>
                <input type="number" class="form-control" id="ayam_bakar" name="ayam_bakar" min="0" value="<?php echo $row['ayam_bakar']; ?>">
            </div>
            <div class="form-group">
                <label for="ayam_goreng">Ayam Goreng</label>
                <input type="number" class="form-control" id="ayam_goreng" name="ayam_goreng" min="0" value="<?php echo $row['ayam_goreng']; ?>">
            </div>
            <div class="form-group">
                <label for="gurami_bakar">Gurami Bakar</label>
                <input type="number" class="form-control" id="gurami_bakar" name="gurami_bakar" min="0" value="<?php echo $row['gurami_bakar']; ?>">
            </div>
            <div class="form-group">
                <label for="gurami_goreng">Gurami Goreng</label>
                <input type="number" class="form-control" id="gurami_goreng" name="gurami_goreng" min="0" value="<?php echo $row['gurami_goreng']; ?>">
            </div>
            <h4>Minuman</h4>
            <div class="form-group">
                <label for="es_teh">Es Teh</label>
                <input type="number" class="form-control" id="es_teh" name="es_teh" min="0" value="<?php echo $row['es_teh']; ?>">
            </div>
            <div class="form-group">
                <label for="es_jeruk">Es Jeruk</label>
                <input type="number" class="form-control" id="es_jeruk" name="es_jeruk" min="0" value="<?php echo $row['es_jeruk']; ?>">
            </div>
            <div class="form-group">
                <label for="teh_hangat">Teh Hangat</label>
                <input type="number" class="form-control" id="teh_hangat" name="teh_hangat" min="0" value="<?php echo $row['teh_hangat']; ?>">
            </div>
            <div class="form-group">
                <label for="jeruk_hangat">Jeruk Hangat</label>
                <input type="number" class="form-control" id="jeruk_hangat" name="jeruk_hangat" min="0" value="<?php echo $row['jeruk_hangat']; ?>">
            </div>
            <div class="form-group">
                <label for="kopi">Kopi</label>
                <input type="number" class="form-control" id="kopi" name="kopi" min="0" value="<?php echo $row['kopi']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Booking</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
