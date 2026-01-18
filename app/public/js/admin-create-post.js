// Admin create/edit post page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea as content grows
    const textarea = document.getElementById('content');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
    
    // File upload preview
    const fileInput = document.getElementById('featured_image');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const wrapper = this.closest('.post-file-upload-wrapper');
                let preview = wrapper.querySelector('.post-image-preview');
                
                if (!preview) {
                    preview = document.createElement('div');
                    preview.className = 'post-image-preview';
                    wrapper.appendChild(preview);
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
