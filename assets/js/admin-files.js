document.addEventListener("DOMContentLoaded", function () {
    const nuevoGrupoBtn = document.getElementById("nuevo-grupo");
    const nuevoGrupoForm = document.getElementById("nuevo-grupo-form");

    nuevoGrupoBtn.addEventListener("click", function () {
        nuevoGrupoForm.style.display = nuevoGrupoForm.style.display === "none" ? "flex" : "none";
    });


    // Filtro de búsqueda
    document.getElementById('buscar-archivo').addEventListener('input', function () {
        var input = this.value.toLowerCase();
        var archivos = document.querySelectorAll('.archivos li');

        archivos.forEach(function (archivo) {
            var nombreArchivo = archivo.querySelector('.nombre-archivo').textContent.toLowerCase();
            if (nombreArchivo.includes(input)) {
                archivo.style.display = '';
            } else {
                archivo.style.display = 'none';
            }
        });
    });

    // Cuando se pulse en un grupo se añade la clase "activo"
    // Seleccionar grupo y actualizar archivos del grupo
    document.querySelectorAll('.grupo-lista li').forEach(function (grupoItem) {
        grupoItem.addEventListener('click', function () {
            // Desmarcar todos los grupos y marcar el seleccionado
            document.querySelectorAll('.grupo-lista li').forEach(function (item) {
                item.classList.remove('activo');
            });
            this.classList.add('activo');

            // Obtener el grupo seleccionado
            let grupoId = this.getAttribute('value');

            // Enviar solicitud AJAX para obtener los archivos del grupo
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'config/grupos-conf.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Actualizar la lista de archivos del grupo
                    let archivoLista = document.querySelector('.archivo-lista');
                    archivoLista.innerHTML = xhr.responseText;
                    addRemoveEventListeners();
                }
            };
            xhr.send('action=get_archivos_grupo&grupo_id=' + grupoId);
            // Inicializar los manejadores de eventos de eliminación
            addRemoveEventListeners();
            
        });
    });

    // Cuando se pulse en un archivo que se le añada la clase "activo"
    document.querySelectorAll('.archivos li').forEach(function (archivo) {
        archivo.addEventListener('click', function () {
            document.querySelectorAll('.archivos li').forEach(function (archivo) {
                archivo.classList.remove('activo');
            });
            archivo.classList.add('activo');
        });
    });

    // Selector para el ícono de plus en los archivos
    document.querySelectorAll('.add-to-group').forEach(function (plusIcon) {
        plusIcon.addEventListener('click', function () {
            // Obtener el archivo seleccionado
            let archivoId = this.getAttribute('value');

            // Obtener el grupo seleccionado
            let grupoSeleccionado = document.querySelector('.grupo-lista .activo');
            if (grupoSeleccionado) {
                let grupoId = grupoSeleccionado.getAttribute('value');

                // Enviar solicitud AJAX para agregar el archivo al grupo
                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'config/grupos-conf.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        // Aquí puedes actualizar la interfaz de usuario si es necesario
                        console.log(xhr.responseText);

                    }
                };
                xhr.send('action=add_archivo_grupo&grupo_id=' + grupoId + '&archivo_id=' + archivoId);
            } else {
                alert('Por favor, selecciona un grupo primero.');
            }
        });
    });

    // Agregar manejadores de eventos a los íconos de eliminación para los archivos de grupo
    function addRemoveEventListeners() {
        document.querySelectorAll('.remove-from-group').forEach(function (removeIcon) {
            removeIcon.addEventListener('click', function () {
                let archivoId = this.getAttribute('value');
                let grupoId = document.querySelector('.grupo-lista .activo').getAttribute('value');
                if (confirm('¿Estás seguro de que quieres eliminar este archivo del grupo?')) {
                    quitarArchivoGrupo(archivoId, grupoId);
                }
            });
        });
    }

    // Inicializar los manejadores de eventos de eliminación de archivos en grupos
    addRemoveEventListeners();

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


    function eliminarGrupo(grupoId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/grupos-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                window.location.reload();
            }
        };
        xhr.send('action=eliminar_grupo&grupo_id=' + grupoId);
    }

    function eliminarArchivo(archivoId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/grupos-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                window.location.reload();
            }
        };
        xhr.send('action=eliminar_archivo&archivo_id=' + archivoId);
    }

    function quitarArchivoGrupo(archivoId, grupoId) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/grupos-conf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Actualizar la lista de archivos del grupo
                let archivoLista = document.querySelector('.archivo-lista');
                archivoLista.innerHTML = xhr.responseText;
                addRemoveEventListeners(); // Volver a agregar los manejadores de eventos
            }
        };
        xhr.send('action=remove_archivo_grupo&grupo_id=' + grupoId + '&archivo_id=' + archivoId);
    }

});


