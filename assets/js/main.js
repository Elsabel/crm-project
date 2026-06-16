$(document).ready(function () {
    // Sidebar Toggle
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);

    // Confirm Delete Function
    window.confirmDelete = function(message = 'Are you sure you want to delete this item?') {
        return confirm(message);
    };

    // Form Validation
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Dynamic Company-Contact loading
    $('#company_id').on('change', function() {
        var companyId = $(this).val();
        if (companyId) {
            // You can implement AJAX loading of contacts for selected company
            console.log('Loading contacts for company:', companyId);
        }
    });

    // Date formatting
    $('.date-format').each(function() {
        var date = new Date($(this).text());
        $(this).text(date.toLocaleDateString());
    });

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.searchable-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Pipeline drag and drop (basic implementation)
    $('.pipeline-stage').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('bg-light');
    });

    $('.pipeline-stage').on('dragleave', function(e) {
        $(this).removeClass('bg-light');
    });

    $('.pipeline-card').on('dragstart', function(e) {
        e.originalEvent.dataTransfer.setData('text/plain', $(this).data('deal-id'));
    });
});

// Global Functions
function updateDealStage(dealId, newStage) {
    // Implementation for updating deal stage via AJAX
    console.log('Updating deal', dealId, 'to stage', newStage);
}

function addNote(entityType, entityId) {
    // Implementation for adding notes
    console.log('Adding note to', entityType, entityId);
}