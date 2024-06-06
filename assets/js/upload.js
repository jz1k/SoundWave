

document.querySelector('button[type="submit"]').addEventListener('click', function(event) {
    const progressBar = document.getElementById('progressBar');

    const formData = new FormData(document.querySelector('form'));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', document.querySelector('form').action);

    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.value = percentComplete;
        }
    };

    xhr.onload = function() {
        // Limpiar la barra de progreso después de que la carga se complete
        progressBar.value = 0;
    };

    xhr.send(formData);
});

document.getElementById('fileInput').addEventListener('change', function() {
    const files = this.files;
    const progressBar = document.getElementById('progressBar');
    const uploadButton = document.getElementById('uploadButton');
    const fileInputText = document.getElementById('fileInputText');
    const selectedFiles = document.getElementById('selectedFiles');
    const fileList = document.getElementById('fileList');

    if (files.length > 0) {
        progressBar.style.display = 'block';
        uploadButton.style.display = 'block';
        fileInputText.innerText = 'Archivos seleccionados';
        selectedFiles.style.display = 'block';

        // Limpiar la lista de archivos seleccionados antes de actualizarla
        fileList.innerHTML = '';

        // Mostrar cada archivo seleccionado en la lista
        for (let i = 0; i < files.length; i++) {
            const listItem = document.createElement('li');
            listItem.textContent = files[i].name;
            fileList.appendChild(listItem);
        }
    } else {
        progressBar.style.display = 'none';
        uploadButton.style.display = 'none';
        fileInputText.innerText = 'Arrastra y suelta archivos aquí o haz clic para seleccionar';
        selectedFiles.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const selectedFiles = document.getElementById('selectedFiles');
    const uploadButton = document.getElementById('uploadButton');
    const fileInputText = document.getElementById('fileInputText');

    dropzone.addEventListener('dragover', function(event) {
        event.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', function(event) {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', function(event) {
        event.preventDefault();
        dropzone.classList.remove('dragover');

        const files = event.dataTransfer.files;
        fileInput.files = files;
        updateFileList();
    });

    fileInput.addEventListener('change', function() {
        updateFileList();
    });

    function updateFileList() {
        fileList.innerHTML = '';
        const files = fileInput.files;

        if (files.length > 0) {
            selectedFiles.style.display = 'block';
            uploadButton.style.display = 'block';

            for (let i = 0; i < files.length; i++) {
                const listItem = document.createElement('li');
                listItem.textContent = files[i].name;
                fileList.appendChild(listItem);
            }
        } else {
            selectedFiles.style.display = 'none';
            uploadButton.style.display = 'none';
        }
    }
});
