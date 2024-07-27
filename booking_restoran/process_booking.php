<?php
include 'koneksi.php';

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

$sql = "INSERT INTO bookings (nama, tanggal_booking, nomor_hp, ayam_bakar, ayam_goreng, gurami_bakar, gurami_goreng, es_teh, es_jeruk, teh_hangat, jeruk_hangat, kopi)
VALUES ('$nama', '$tanggal_booking', '$nomor_hp', '$ayam_bakar', '$ayam_goreng', '$gurami_bakar', '$gurami_goreng', '$es_teh', '$es_jeruk', '$teh_hangat', '$jeruk_hangat', '$kopi')";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
