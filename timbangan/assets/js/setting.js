document.addEventListener('DOMContentLoaded', function() {
    const productForm = document.getElementById('product-form');
    const personForm = document.getElementById('person-form');
    
    productForm.addEventListener('submit', handleProductSubmit);
    personForm.addEventListener('submit', handlePersonSubmit);

    // Reset product form when "Tambah Produk" is clicked
    // document.getElementById('product-submit-btn').addEventListener('click', function(event) {
    //     if (this.textContent === 'Tambah Produk') {
    //         resetProductForm();
    //     }
    // });
});

function resetProductForm() {
    document.getElementById('product-id').value = '';
    document.getElementById('product-name').value = '';
    document.getElementById('product-submit-btn').textContent = 'Tambah Produk';
}

function handleProductSubmit(event) {
    event.preventDefault();
    const id = document.getElementById('product-id').value;
    const name = document.getElementById('product-name').value;

    if (!name) {
        alert('Nama produk harus diisi');
        return;
    }

    if (id) {
        updateItem('produk', id, name);
    } else {
        createItem('produk', name);
    }
}

function handlePersonSubmit(event) {
    event.preventDefault();
    const id = document.getElementById('person-id').value;
    const name = document.getElementById('person-name').value;

    if (!name) {
        alert('Nama orang harus diisi');
        return;
    }

    if (id) {
        updateItem('orang', id, name);
    } else {
        createItem('orang', name);
    }
}

function createItem(type, name) {
    fetch('api/product_crud.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=create&type=${type}&name=${encodeURIComponent(name)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Gagal menambahkan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan');
    });
}

function editProduct(id, name) {
    document.getElementById('product-id').value = id;
    document.getElementById('product-name').value = name;
    document.getElementById('product-submit-btn').textContent = 'Update Produk';
}

function editPerson(id, name) {
    document.getElementById('person-id').value = id;
    document.getElementById('person-name').value = name;
    document.getElementById('person-submit-btn').textContent = 'Update Orang';
}

function updateItem(type, id, name) {
    fetch('api/product_crud.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&type=${type}&id=${id}&name=${encodeURIComponent(name)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Gagal mengupdate: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate');
    });
}

function deleteProduct(id) {
    deleteItem('produk', id);
}

function deletePerson(id) {
    deleteItem('orang', id);
}

function deleteItem(type, id) {
    if (confirm('Apakah Anda yakin ingin menghapus ini?')) {
        fetch('api/product_crud.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&type=${type}&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus');
        });
    }
}