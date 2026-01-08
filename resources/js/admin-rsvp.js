// resources/js/admin-rsvp.js

class RsvpManager {
    constructor() {
        this.allRsvps = [];
        this.filteredRsvps = [];
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.currentFilter = 'all';
        
        this.init();
    }

    init() {
        // Get data from the page
        this.loadDataFromTable();
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Initial render
        this.filterAndPaginate();
    }

    loadDataFromTable() {
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 7) {
                const statusBadge = cells[3].querySelector('.status-badge');
                this.allRsvps.push({
                    name: cells[1].textContent.trim(),
                    email: cells[2].textContent.trim(),
                    attending: statusBadge.classList.contains('status-yes') ? 'yes' : 'no',
                    additionalGuests: cells[4].textContent.trim(),
                    message: cells[5].textContent.trim(),
                    submittedAt: cells[6].textContent.trim()
                });
            }
        });

        // Reverse to get oldest first order
        this.allRsvps.reverse();
        
        console.log('Loaded RSVPs:', this.allRsvps.length);
    }

    setupEventListeners() {
        // Filter dropdown
        const filterSelect = document.getElementById('attendingFilter');
        if (filterSelect) {
            filterSelect.addEventListener('change', (e) => {
                this.currentFilter = e.target.value;
                this.currentPage = 1; // Reset to first page
                this.filterAndPaginate();
            });
        }

        // Download button - update href with filter
        const downloadBtn = document.querySelector('.btn-download');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const baseUrl = downloadBtn.getAttribute('data-download-url');
                const url = `${baseUrl}?filter=${this.currentFilter}`;
                window.location.href = url;
            });
        }
    }

    filterAndPaginate() {
        // Filter data
        if (this.currentFilter === 'all') {
            this.filteredRsvps = [...this.allRsvps];
        } else {
            this.filteredRsvps = this.allRsvps.filter(rsvp => rsvp.attending === this.currentFilter);
        }

        console.log('Filtered RSVPs:', this.filteredRsvps.length, 'Filter:', this.currentFilter);

        // Update stats
        this.updateStats();

        // Render table
        this.renderTable();

        // Always render pagination
        this.renderPagination();
    }

    updateStats() {
        const totalCount = this.filteredRsvps.length;
        const attendingCount = this.filteredRsvps.filter(r => r.attending === 'yes').length;
        const notAttendingCount = this.filteredRsvps.filter(r => r.attending === 'no').length;

        const totalEl = document.querySelector('.stat-number.total');
        const attendingEl = document.querySelector('.stat-number.attending');
        const notAttendingEl = document.querySelector('.stat-number.not-attending');

        if (totalEl) totalEl.textContent = totalCount;
        if (attendingEl) attendingEl.textContent = attendingCount;
        if (notAttendingEl) notAttendingEl.textContent = notAttendingCount;
    }

    renderTable() {
        const tbody = document.querySelector('tbody');
        
        if (!tbody) return;

        if (this.filteredRsvps.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <h2>No RSVPs Found</h2>
                            <p>No results match your current filter.</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        const pageData = this.filteredRsvps.slice(start, end);

        tbody.innerHTML = pageData.map((rsvp, index) => {
            const globalIndex = start + index + 1;
            return `
                <tr>
                    <td><strong>${globalIndex}</strong></td>
                    <td><strong>${this.escapeHtml(rsvp.name)}</strong></td>
                    <td>${this.escapeHtml(rsvp.email)}</td>
                    <td>
                        <span class="status-badge status-${rsvp.attending}">
                            ${rsvp.attending === 'yes' ? 'Yes' : 'No'}
                        </span>
                    </td>
                    <td>${this.escapeHtml(rsvp.additionalGuests)}</td>
                    <td>${this.escapeHtml(rsvp.message)}</td>
                    <td>${this.escapeHtml(rsvp.submittedAt)}</td>
                </tr>
            `;
        }).join('');
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    renderPagination() {
        const totalPages = Math.ceil(this.filteredRsvps.length / this.itemsPerPage);
        const paginationContainer = document.getElementById('pagination');

        if (!paginationContainer) return;

        // Always show pagination, even with 1 page
        let paginationHtml = '<div class="pagination-wrapper">';
        
        // Previous button
        paginationHtml += `
            <button class="pagination-btn ${this.currentPage === 1 ? 'disabled' : ''}" 
                    data-page="${this.currentPage - 1}" 
                    ${this.currentPage === 1 ? 'disabled' : ''}
                    aria-label="Previous page">
                <span class="pagination-arrow">←</span>
                <span class="pagination-text">Previous</span>
            </button>
        `;

        // Page numbers
        if (totalPages > 0) {
            const maxVisible = 5;
            let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
            let endPage = Math.min(totalPages, startPage + maxVisible - 1);

            if (endPage - startPage < maxVisible - 1) {
                startPage = Math.max(1, endPage - maxVisible + 1);
            }

            // First page + ellipsis
            if (startPage > 1) {
                paginationHtml += `
                    <button class="pagination-btn" data-page="1" aria-label="Go to page 1">
                        1
                    </button>
                `;
                if (startPage > 2) {
                    paginationHtml += `<span class="pagination-ellipsis">...</span>`;
                }
            }

            // Visible page numbers
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <button class="pagination-btn ${i === this.currentPage ? 'active' : ''}" 
                            data-page="${i}"
                            aria-label="Go to page ${i}"
                            aria-current="${i === this.currentPage ? 'page' : 'false'}">
                        ${i}
                    </button>
                `;
            }

            // Ellipsis + last page
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHtml += `<span class="pagination-ellipsis">...</span>`;
                }
                paginationHtml += `
                    <button class="pagination-btn" data-page="${totalPages}" aria-label="Go to page ${totalPages}">
                        ${totalPages}
                    </button>
                `;
            }
        } else {
            // No data, show page 1
            paginationHtml += `
                <button class="pagination-btn active" data-page="1" aria-current="page">
                    1
                </button>
            `;
        }

        // Next button
        paginationHtml += `
            <button class="pagination-btn ${this.currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}" 
                    data-page="${this.currentPage + 1}"
                    ${this.currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}
                    aria-label="Next page">
                <span class="pagination-text">Next</span>
                <span class="pagination-arrow">→</span>
            </button>
        `;

        paginationHtml += '</div>';

        // Info text - separate for better mobile display
        const start = this.filteredRsvps.length === 0 ? 0 : (this.currentPage - 1) * this.itemsPerPage + 1;
        const end = Math.min(this.currentPage * this.itemsPerPage, this.filteredRsvps.length);
        
        paginationHtml += `
            <div class="pagination-info">
                Showing ${start}-${end} of ${this.filteredRsvps.length}
            </div>
        `;

        paginationContainer.innerHTML = paginationHtml;

        // Add event listeners to pagination buttons
        paginationContainer.querySelectorAll('.pagination-btn:not(.disabled)').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(btn.getAttribute('data-page'));
                if (page && page !== this.currentPage) {
                    this.currentPage = page;
                    this.filterAndPaginate();
                    
                    // Scroll to top of table smoothly
                    const tableContainer = document.querySelector('.table-container');
                    if (tableContainer) {
                        tableContainer.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }
                }
            });
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new RsvpManager();
    });
} else {
    new RsvpManager();
}