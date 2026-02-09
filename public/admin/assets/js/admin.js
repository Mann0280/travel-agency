// Admin JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete actions
    const deleteForms = document.querySelectorAll('form[method="DELETE"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
    
    // Image preview for forms
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            if (previewId) {
                const preview = document.getElementById(previewId);
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    });
    
    // Toggle status switches
    const statusToggles = document.querySelectorAll('.status-toggle');
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const itemId = this.getAttribute('data-id');
            const status = this.checked ? 'active' : 'inactive';
            
            fetch(`/admin/toggle-status/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: 'The status has been updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating status.',
                });
            });
        });
    });
    
    // Sortable tables
    const sortableTables = document.querySelectorAll('.sortable-table tbody');
    sortableTables.forEach(table => {
        new Sortable(table, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const items = Array.from(table.children);
                const order = items.map((item, index) => ({
                    id: item.getAttribute('data-id'),
                    order: index + 1
                }));
                
                fetch('/admin/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order: order })
                });
            }
        });
    });
});

// Date picker initialization
if (typeof flatpickr !== 'undefined') {
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
    });
}

// Rich text editor initialization
if (typeof ClassicEditor !== 'undefined') {
    document.querySelectorAll('.rich-text-editor').forEach(editor => {
        ClassicEditor
            .create(editor)
            .catch(error => {
                console.error(error);
            });
    });
}