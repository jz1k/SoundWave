document.addEventListener('DOMContentLoaded', function () {

    // Controlar boton con id nuevoUsuario y si se pulsa poner display flex en add-user-form
    document.getElementById('nuevoUsuario').addEventListener('click', function () {
        var addUserForm = document.querySelector('.add-user-form');
        if (addUserForm.style.display === 'flex') {
            addUserForm.style.display = 'none';
        } else {
            addUserForm.style.display = 'flex';
        }
    });
    

    // Funcion para controlar el boton de ver archivos
    document.querySelectorAll('.btn-archivo').forEach(function (deleteButton) {
        deleteButton.addEventListener('click', function () {
            let usuarioId = this.parentElement.getAttribute('value');
            verArchivos(usuarioId);
        });
    });


    // Si se pulsa sobre "ver grupo", obtener value del padre y mostrar grupos de ese usuario dentro de <div class ="file-group-list">
    document.querySelectorAll('.btn-grupo').forEach(function (deleteButton) {
        deleteButton.addEventListener('click', function () {
            let usuarioId = this.parentElement.getAttribute('value');
            verGrupos(usuarioId);
        });
    });

    // Funcion para controlar el boton de ver grupos
    function verGrupos(usuarioId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/admin-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Actualizar la lista de archivos del grupo
                let gruposUsuario = document.querySelector('.file-group-list');
                gruposUsuario.innerHTML = xhr.responseText;
                addRemoveEventListeners();
            }
        };
        xhr.send('action=ver_grupos&usuario_id=' + usuarioId);
        addRemoveEventListeners();
    }

    // Funcion para controlar el boton de ver archivos
    function verArchivos(usuarioId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/admin-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Actualizar la lista de archivos del grupo
                let archivosGrupo = document.querySelector('.file-group-list');
                archivosGrupo.innerHTML = xhr.responseText;
                addRemoveEventListeners();
            }
        };
        xhr.send('action=ver_archivos&usuario_id=' + usuarioId);
        addRemoveEventListeners();
    }

    // Funcion para añadir manejadores de eventos de eliminacion de archivos o grupo
    function addRemoveEventListeners() {
        // Manejar clic en botones de borrar grupo
        document.querySelectorAll('.delete-group').forEach(function (deleteButton) {
            deleteButton.addEventListener('click', function () {
                let grupoId = this.parentElement.getAttribute('value');
                if (confirm('¿Estás seguro de que quieres eliminar este grupo?')) {
                    eliminarGrupo(grupoId);
                }
            });
        });

        // Manejar clic en botones de borrar archivo
        document.querySelectorAll('.delete-file').forEach(function (deleteButton) {
            deleteButton.addEventListener('click', function () {
                let archivoId = this.parentElement.getAttribute('value');
                if (confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
                    eliminarArchivo(archivoId);
                }
            });
        });
    }

    // Funcion para eliminar un grupo
    function eliminarGrupo(grupoId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/admin-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Actualizar la lista de grupos
                let gruposUsuario = document.querySelector('.file-group-list');
                gruposUsuario.innerHTML = xhr.responseText;
                addRemoveEventListeners();
            }
        };
        xhr.send('action=eliminar_grupo&grupo_id=' + grupoId);
    }

    // Funcion para eliminar un archivo
    function eliminarArchivo(archivoId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/admin-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Actualizar la lista de archivos
                let archivosGrupo = document.querySelector('.file-group-list');
                archivosGrupo.innerHTML = xhr.responseText;
                addRemoveEventListeners();
            }
        };
        xhr.send('action=eliminar_archivo&archivo_id=' + archivoId);
    }





});