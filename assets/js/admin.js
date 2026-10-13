/**
 * assets/js/admin.js - Admin Panel File Upload & 1:1 Preview Handlers
 */

document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('postImages');
    const dropzone = document.getElementById('fileDropzone');
    const previewGrid = document.getElementById('imagesPreviewGrid');
    const MAX_FILES = 5;

    if (!fileInput || !dropzone || !previewGrid) return;

    // Drag & Drop visual indicators
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelection(fileInput.files);
        }
    });

    fileInput.addEventListener('change', () => {
        handleFileSelection(fileInput.files);
    });

    function handleFileSelection(files) {
        previewGrid.innerHTML = '';

        if (!files || files.length === 0) return;

        if (files.length > MAX_FILES) {
            alert(`Możesz dodać maksymalnie ${MAX_FILES} zdjęć. Wybrano ${files.length}. Wyświetlonych zostanie pierwsze ${MAX_FILES} zdjęć.`);
        }

        const countToProcess = Math.min(files.length, MAX_FILES);

        for (let i = 0; i < countToProcess; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) continue;

            const reader = new FileReader();
            reader.onload = (e) => {
                const item = document.createElement('div');
                item.className = 'preview-item aspect-square';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = `Zdjęcie ${i + 1}`;

                const badge = document.createElement('span');
                badge.className = 'preview-badge-ratio';
                badge.textContent = '1:1';

                item.appendChild(img);
                item.appendChild(badge);
                previewGrid.appendChild(item);
            };
            reader.readAsDataURL(file);
        }
    }
});
