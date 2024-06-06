document.addEventListener('DOMContentLoaded', function () {
    var bucle = false;
    var segundos = 0;

    // Añade un controlador de eventos de clic a cada li
    function addClickEventToLiElements() {
        // Selecciona todos los elementos li que son hijos directos de un elemento con la clase 'canciones'
        var liElements = document.querySelectorAll('.canciones > li');
        liElements.forEach(function (li) {
            li.addEventListener('click', function () {
                // Elimina la clase 'marquee' de todos los elementos li
                liElements.forEach(function (otherLi) {
                    otherLi.classList.remove('marquee');
                    otherLi.classList.remove('reproduciendo');
                });

                this.classList.add('reproduciendo');

                // Comprueba si el li se desborda de su contenedor div
                const parentDiv = li.parentElement.parentElement;
                const isOverflowing = li.scrollWidth > parentDiv.clientWidth;

                // Si el li se desborda, añade la clase 'marquee'
                if (isOverflowing) {
                    this.classList.add('marquee');
                }
            });
        });
    }

    addClickEventToLiElements();

    /**
     */



    // Seleccionar grupo y actualizar cola con archivos del grupo
    document.querySelectorAll('.playlists-container div').forEach(function (grupoItem) {
        grupoItem.addEventListener('click', function () {
            // Desmarcar todos los grupos y marcar el seleccionado
            document.querySelectorAll('.playlists-container div').forEach(function (item) {
                item.classList.remove('activo');
            });
            this.classList.add('activo');

            // Obtener el grupo seleccionado
            let grupoId = this.getAttribute('value');

            // Enviar solicitud AJAX para obtener los archivos del grupo
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'config/cola.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Actualizar la lista de archivos del grupo
                    let archivoLista = document.querySelector('.canciones');
                    archivoLista.innerHTML = xhr.responseText;
                    addClickEventToLiElements();
                    addDblClickEventToArchivoItems()
                }
            };
            xhr.send('action=get_archivos_grupo&grupo_id=' + grupoId);
            addClickEventToLiElements();
            addDblClickEventToArchivoItems()
        });
    });

    // Controlar boton de "ver todas los archivos" subidos por el usuario iniciado
    document.querySelector('.ver-todas-canciones').addEventListener('click', function () {
        // Desmarcar todos los grupos y marcar el seleccionado
        document.querySelectorAll('.playlists-container div').forEach(function (item) {
            item.classList.remove('activo');
        });

        // Enviar solicitud AJAX para obtener todos los archivos del usuario
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/cola.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Actualizar la lista de archivos del usuario
                let archivoLista = document.querySelector('.canciones');
                archivoLista.innerHTML = xhr.responseText;
                addClickEventToLiElements();
                addDblClickEventToArchivoItems()
            }
        };
        xhr.send('action=get_archivos_usuario_activo');
    }
    );

    // Agrega un controlador de eventos de doble clic a cada li
    function addDblClickEventToArchivoItems() {
        var archivoElements = document.querySelectorAll('.canciones > li');
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        var eventType = isMobile ? 'click' : 'dblclick';

        archivoElements.forEach(function (archivo) {
            archivo.addEventListener(eventType, function () {
                var rutaArchivo = this.getAttribute('ruta');
                if (rutaArchivo) {
                    var archivoId = this.getAttribute('value');
                    var nombreArchivo = this.innerText;

                    // Eliminar la extensión del archivo del nombre
                    nombreArchivo = nombreArchivo.split('.').slice(0, -1).join('.');

                    // Reproducir el archivo
                    reproducirArchivo(rutaArchivo, nombreArchivo);
                } else {
                    console.error('No se ha encontrado la ruta del archivo.');
                }
            });
        });
    }

    function reproducirArchivo(rutaArchivo, nombreArchivo) {
        var reproductor = document.getElementById('reproductor');
        var rutaReproduciendo = document.getElementById('rutaReproduciendo');
        var songName = document.getElementById('currentFile');

        if (reproductor && rutaReproduciendo && songName) {
            reproductor.src = rutaArchivo;
            rutaReproduciendo.value = rutaArchivo;
            songName.innerText = nombreArchivo;
            var icon = document.getElementById('playPauseIcon');
            icon.classList.remove('fa-play');
            icon.classList.add('fa-pause');
            reproductor.play();


        } else {
            console.error('No se encontró el reproductor de música o elementos relacionados.');
        }
    }




    // Función para reproducir o pausar la canción
    function playPause() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            if (reproductor.paused) {
                var icon = document.getElementById('playPauseIcon');
                if (icon) {
                    icon.classList.remove('fa-play');
                    icon.classList.add('fa-pause');
                }
                reproductor.play();
            } else {
                var icon = document.getElementById('playPauseIcon');
                if (icon) {
                    icon.classList.remove('fa-pause');
                    icon.classList.add('fa-play');
                }
                reproductor.pause();
            }
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }

    // Cuando una cancion se completa, pasa a la siguiente de forma automatica
    // document.getElementById('reproductor').addEventListener('ended', function () {
    //     playNext();
    // });

    // Función para reproducir la canción anterior
    function playPrevious() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var archivoAnterior = document.querySelector('.reproduciendo').previousElementSibling;
            if (archivoAnterior) {
                var rutaArchivo = archivoAnterior.getAttribute('ruta');
                var nombreArchivo = archivoAnterior.innerText;
                nombreArchivo = nombreArchivo.split('.').slice(0, -1).join('.');
                // Eliminar la clase 'reproduciendo' del archivo actual
                document.querySelector('.reproduciendo').classList.remove('reproduciendo');

                // Añadir la clase 'reproduciendo' al archivo anterior
                archivoAnterior.classList.add('reproduciendo');


                reproducirArchivo(rutaArchivo, nombreArchivo);
            } else {
                console.error('No se encontró un archivo anterior.');
            }
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }

    // Función para reproducir la canción siguiente
    function playNext() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var archivoSiguiente = document.querySelector('.reproduciendo').nextElementSibling;
            if (archivoSiguiente) {
                var rutaArchivo = archivoSiguiente.getAttribute('ruta');
                var nombreArchivo = archivoSiguiente.innerText;
                nombreArchivo = nombreArchivo.split('.').slice(0, -1).join('.');
                // Añadir la clase 'reproduciendo' al archivo siguiente
                archivoSiguiente.classList.add('reproduciendo');
                // Eliminar la clase 'reproduciendo' del archivo actual
                document.querySelector('.reproduciendo').classList.remove('reproduciendo');

                reproducirArchivo(rutaArchivo, nombreArchivo);
            } else {
                console.error('No se encontró un archivo siguiente.');
            }
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }



    // Función para reproducir en modo aleatorio
    function shuffleSongs() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var archivoAleatorio = document.querySelector('.canciones').children[Math.floor(Math.random() * document.querySelector('.canciones').children.length)];
            var rutaArchivo = archivoAleatorio.getAttribute('ruta');
            var nombreArchivo = archivoAleatorio.innerText;
            nombreArchivo = nombreArchivo.split('.').slice(0, -1).join('.');
            // Eliminar la clase 'reproduciendo' del archivo actual
            document.querySelector('.reproduciendo').classList.remove('reproduciendo');
            // Si el elemento con la clase 'marquee' existe, eliminar la clase
            let elemento = document.querySelector('.marquee');
            if (elemento) {
                elemento.classList.remove('marquee');
            }

            // Añadir la clase 'reproduciendo' al archivo aleatorio
            archivoAleatorio.classList.add('reproduciendo');
            // Añadir la clase 'marquee' al archivo aleatorio si se desborda
            const parentDiv = archivoAleatorio.parentElement.parentElement;
            const isOverflowing = archivoAleatorio.scrollWidth > parentDiv.clientWidth;
            if (isOverflowing) {
                archivoAleatorio.classList.add('marquee');
            }

            reproducirArchivo(rutaArchivo, nombreArchivo);
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }

    // Actualiza la barra de progreso cuando se reproduce un archivo
    function updateProgress() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var progress = document.getElementById('progress');
            var value = 0;
            if (reproductor.currentTime > 0) {
                value = Math.floor((100 / reproductor.duration) * reproductor.currentTime);
            }
            progress.value = value;
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }

    // Agregar evento de clic a la barra de progreso
    var progressBar = document.querySelector('.progress-bar');
    progressBar.addEventListener('click', function (event) {
        // Calcular la posición del clic dentro de la barra de progreso
        var progressPosition = event.pageX - this.offsetLeft;
        var progressBarWidth = this.offsetWidth;
        // Calcular el porcentaje de progreso en función de la posición del clic
        var progressPercentage = (progressPosition / progressBarWidth) * 100;
        // Actualizar la posición de reproducción del audio
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var duration = reproductor.duration;
            if (!isNaN(duration)) {
                var newPosition = (progressPercentage / 100) * duration;
                reproductor.currentTime = newPosition;
            }
        }
    });

    // Función para mostrar la duración de la canción y su progreso en tiempo real (ejemplo: 2:30 - 3:00)
    function currentDuration() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var durationCurrent = document.getElementById('durationCurrent');
            var duration = reproductor.duration;
            var currentTime = reproductor.currentTime;
            if (!isNaN(duration)) {
                durationCurrent.innerText = formatTime(currentTime) + ' - ' + formatTime(duration);
            }
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }

    // Función auxiliar para formatear el tiempo en minutos y segundos
    function formatTime(time) {
        var minutes = Math.floor(time / 60);
        var seconds = Math.floor(time % 60);
        // Agregar un cero inicial si los segundos son menores que 10
        seconds = seconds < 10 ? '0' + seconds : seconds;
        return minutes + ':' + seconds;
    }

    // Controlar el volumen
    var volumeControl = document.getElementById('volume');
    volumeControl.addEventListener('input', function () {
        if (reproductor) {
            reproductor.volume = this.value;
        }
    });

    //Controlamos el boton loop
    document.querySelector('.loop').addEventListener('click', function () {
        var icon = this.querySelector('i');
        icon.classList.toggle('botonActivo');
        bucle = !bucle;
    });

    // Función para reproducir en bucle, si el bucle está activado volver a reproducir la misma cancion de nuevo
    // Reproducir en bucle si el bucle está activado
    reproductor.addEventListener('ended', function () {
        if (bucle) {
            reproductor.currentTime = 0;
            reproductor.play();
        } else {
            // Event listener para reproducir la siguiente canción cuando la actual termina
            document.getElementById('reproductor').addEventListener('ended', playNext);
        }
    });

    // Controlamos cuando el audio está en play para sumar cada segundo que pasa escuchando musica
    reproductor.addEventListener('play', function () {
        setInterval(function () {
            segundos++;
        }, 1000);
    });

    // Controlamos cuando el audio está en pause para parar el contador de segundos
    reproductor.addEventListener('pause', function () {
        clearInterval(segundos);
    });

    // Controlamos cuando el audio ha terminado para parar el contador de segundos
    reproductor.addEventListener('ended', function () {
        clearInterval(segundos);
    });

    // Controlamos cuando lleva 15 segundos escuchados para llamar a la funcion de updateHorasEscuchadas
    reproductor.addEventListener('timeupdate', function () {
        if (segundos === 15) {
            updateHorasEscuchadas(segundos);
            segundos = 0;
        }
    });

    // Funcion para actualizar las horas escuchadas de un usuario
    function updateHorasEscuchadas($horasEscuchadas) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'config/cola.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log('Horas escuchadas actualizadas correctamente.');
            }
        };
        xhr.send('action=update_horas_escuchadas&horas_escuchadas=' + $horasEscuchadas);
    }

    // Creamos la cookie con el id de la cancion que se esta reproduciendo
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    // Guarda el ID de la última canción reproducida cuando se cambia de canción
    document.getElementById('reproductor').addEventListener('play', function () {
        var lastSongId = document.querySelector('.reproduciendo').getAttribute('value');
        setCookie('lastSongId', lastSongId, 7);
    });

    // Carga la última canción reproducida cuando la página se carga
    window.addEventListener('load', function () {
        var lastSongId = getCookie('lastSongId');
        if (lastSongId) {
            // Carga todas las canciones de ese usuario
            document.querySelector('.ver-todas-canciones').click();
            // Espera a que se carguen las canciones 0.5 segundos
            setTimeout(function () {
                // Selecciona la última canción reproducida
                var lastSong = document.querySelector('.canciones > li[value="' + lastSongId + '"]');
                if (lastSong) {
                    // Reproduce la última canción, simula un doble clic
                    lastSong.click();
                    // Llama a la función de playSelected
                    playSelectedCookie();
                }
            }, 500);



        }
    });

    // Guardamos el id de la cancion que se esta reproduciendo para almacenarla como cookie y cuando vuelva a iniciar sesion aparezca la ultima cancion que estaba escuchando
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Funcion para reproducir la cancion seleccionada
    function playSelectedCookie() {
        var reproductor = document.getElementById('reproductor');
        if (reproductor) {
            var archivoSeleccionado = document.querySelector('.reproduciendo');
            if (archivoSeleccionado) {
                var rutaArchivo = archivoSeleccionado.getAttribute('ruta');
                var nombreArchivo = archivoSeleccionado.innerText;
                nombreArchivo = nombreArchivo.split('.').slice(0, -1).join('.');
                prepararCookie(rutaArchivo, nombreArchivo);
            } else {
                console.error('No se encontró un archivo seleccionado.');
            }
        } else {
            console.error('No se encontró el reproductor de música.');
        }
    }

    // Funcion para preparar la cookie con la cancion que se va a reproducir pero pausarla y solo reproducir si el usuario le da al play
    function prepararCookie(rutaArchivo, nombreArchivo) {
        var reproductor = document.getElementById('reproductor');
        var rutaReproduciendo = document.getElementById('rutaReproduciendo');
        var songName = document.getElementById('currentFile');

        if (reproductor && rutaReproduciendo && songName) {
            reproductor.src = rutaArchivo;
            rutaReproduciendo.value = rutaArchivo;
            songName.innerText = nombreArchivo;
            var icon = document.getElementById('playPauseIcon');
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
            reproductor.pause();
        } else {
            console.error('No se encontró el reproductor de música o elementos relacionados.');
        }
    }






    // Event listeners para los botones
    document.querySelector('.play-pause').addEventListener('click', playPause);
    document.querySelector('.previous').addEventListener('click', playPrevious);
    document.querySelector('.next').addEventListener('click', playNext);
    document.querySelector('.shuffle').addEventListener('click', shuffleSongs);

    // Event listener para la barra de progreso
    document.getElementById('reproductor').addEventListener('timeupdate', updateProgress);



    // Event listener para la duracion de la cancion
    document.getElementById('reproductor').addEventListener('timeupdate', function () {
        currentDuration();
        updateProgress();
    });

});


