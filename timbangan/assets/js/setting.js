document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('product-form');
    form.addEventListener('submit', handleSubmit);
});

function handleSubmit(event) {
    event.preventDefault();
    const id = document.getElementById('product-id').value;
    const name = document.getElementById('product-name').value;

    if (id) {
        updateProduct(id, name);
    } else {
        createProduct(name);
    }
}

function createProduct(name) {
    fetch('api/product_crud.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=create&name=${encodeURIComponent(name)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil ditambahkan');
            location.reload();
        } else {
            alert('Gagal menambahkan produk: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan produk');
    });
}

function editProduct(id, name) {
    document.getElementById('product-id').value = id;
    document.getElementById('product-name').value = name;
    document.getElementById('submit-btn').textContent = 'Update Produk';
}

function updateProduct(id, name) {
    fetch('api/product_crud.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&id=${id}&name=${encodeURIComponent(name)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil diupdate');
            location.reload();
        } else {
            alert('Gagal mengupdate produk: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate produk');
    });
}

function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        fetch('api/product_crud.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus produk: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus produk');
        });
    }
}