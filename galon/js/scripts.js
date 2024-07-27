$(document).ready(function() {
    const price1 = 15000;
    const price2 = 40000;

    $('#quantity1, #quantity2').on('input', function() {
        const quantity1 = parseInt($('#quantity1').val()) || 0;
        const quantity2 = parseInt($('#quantity2').val()) || 0;
        const total = (quantity1 * price1) + (quantity2 * price2);
        $('#total').text(total);
    });

    $('#submit').click(function() {
        console.log("BEGIN");
        const name = $('#name').val().trim();
        const whatsapp = $('#whatsapp').val().trim();
        const total = parseInt($('#total').text());
        const quantity1 = parseInt($('#quantity1').val()) || 0;
        const quantity2 = parseInt($('#quantity2').val()) || 0;

        if (total === 0) {
            alert('Pesanan belum terisi.');
            return;
        }

        if (name === '' || whatsapp === '') {
            alert('Nama dan Nomor WhatsApp harus diisi.');
            return;
        }

        // Ambil public key dari server
        
        $.get('php/get_public_key.php', function(data) {
            const publicKey = JSON.parse(data);
            const n = publicKey[1];
            const e = publicKey[0];

            const encrypt = new JSEncrypt();
            encrypt.setPublicKey(`-----BEGIN PUBLIC KEY-----\n${publicKey}\n-----END PUBLIC KEY-----`);

            const encryptedName = encrypt.encrypt(name);
            const encryptedWhatsapp = encrypt.encrypt(whatsapp);

            if (encryptedName && encryptedWhatsapp) {
                $('#hiddenName').val(encryptedName);
                $('#hiddenWhatsapp').val(encryptedWhatsapp);
                $('#quantity1hidden').val(quantity1);
                $('#quantity2hidden').val(quantity2);
                $('#totalhidden').val(total);
                $('#orderForm').submit();
            } else {
                alert('Terjadi kesalahan saat mengenkripsi data.');
            }
        });
    });
});
