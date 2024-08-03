</div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('mahasiswaTable');
    const tbody = table.querySelector('tbody');
    const pagination = document.getElementById('pagination');
    const rowsPerPage = 10; // Jumlah baris per halaman
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const pageCount = Math.ceil(rows.length / rowsPerPage);
    let currentPage = 1;

    function displayTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });
    }

    function setupPagination() {
        pagination.innerHTML = '';
        
        // Previous button
        addPaginationButton('Previous', currentPage > 1 ? currentPage - 1 : 1, currentPage > 1);
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(pageCount, currentPage + 2);
        
        if (startPage > 1) {
            addPaginationButton(1, 1);
            if (startPage > 2) {
                pagination.appendChild(createEllipsis());
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            addPaginationButton(i, i, i === currentPage);
        }
        
        if (endPage < pageCount) {
            if (endPage < pageCount - 1) {
                pagination.appendChild(createEllipsis());
            }
            addPaginationButton(pageCount, pageCount);
        }
        
        // Next button
        addPaginationButton('Next', currentPage < pageCount ? currentPage + 1 : pageCount, currentPage < pageCount);
    }

    function addPaginationButton(text, page, isActive = false, isDisabled = false) {
        const li = document.createElement('li');
        li.classList.add('page-item');
        if (isActive) li.classList.add('active');
        if (isDisabled) li.classList.add('disabled');
        
        const a = document.createElement('a');
        a.classList.add('page-link');
        a.href = '#';
        a.textContent = text;
        
        if (!isDisabled) {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = page;
                displayTable(currentPage);
                setupPagination();
            });
        }
        
        li.appendChild(a);
        pagination.appendChild(li);
    }

    function createEllipsis() {
        const li = document.createElement('li');
        li.classList.add('page-item', 'disabled');
        const span = document.createElement('span');
        span.classList.add('page-link');
        span.textContent = '...';
        li.appendChild(span);
        return li;
    }

    displayTable(currentPage);
    setupPagination();
});
</script>
</body>
</html>