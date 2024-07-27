<?php
include 'koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$tanggal_booking = $_POST['tanggal_booking'];
$nomor_hp = $_POST['nomor_hp'];
$ayam_bakar = $_POST['ayam_bakar'];
$ayam_goreng = $_POST['ayam_goreng'];
$gurami_bakar = $_POST['gurami_bakar'];
$gurami_goreng = $_POST['gurami_goreng'];
$es_teh = $_POST['es_teh'];
$es_jeruk = $_POST['es_jeruk'];
$teh_hangat = $_POST['teh_hangat'];
$jeruk_hangat = $_POST['jeruk_hangat'];
$kopi = $_POST['kopi'];

$sql = "UPDATE bookings SET nama='$nama', tanggal_booking='$tanggal_booking', nomor_hp='$nomor_hp', ayam_bakar='$ayam_bakar', ayam_goreng='$ayam_goreng', gurami_bakar='$gurami_bakar', gurami_goreng='$gurami_goreng', es_teh='$es_teh', es_jeruk='$es_jeruk', teh_hangat='$teh_hangat', jeruk_hangat='$jeruk_hangat', kopi='$kopi' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
