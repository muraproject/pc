function showDetail(idKwitansi, nama) {
    fetch(`api/get_kwitansi_detail.php?id_kwitansi=${idKwitansi}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let modalContent = `
                    <h4>Nama: ${nama}</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Nilai Timbang</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let totalHarga = 0;
                data.details.forEach((item, index) => {
                    const itemTotal = item.nilai_timbang * item.harga;
                    totalHarga += itemTotal;
                    modalContent += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nama_produk}</td>
                            <td>${item.nilai_timbang} kg</td>
                            <td>Rp ${item.harga.toLocaleString()}</td>
                            <td>Rp ${itemTotal.toLocaleString()}</td>
                        </tr>
                    `;
                });

                modalContent += `
                        </tbody>
                    </table>
                    <h5>Total Harga: Rp ${totalHarga.toLocaleString()}</h5>
                `;

                document.getElementById('modalBody').innerHTML = modalContent;
                $('#detailModal').modal('show');
            } else {
                alert('Gagal mengambil detail kwitansi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil detail kwitansi');
        });
}