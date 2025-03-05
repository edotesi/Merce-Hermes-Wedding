document.addEventListener('DOMContentLoaded', function() {
    const events = document.querySelectorAll('.event-marker');
    const paginationDots = document.querySelectorAll('.pagination-dot');
    const content = document.querySelector('.event-content');
    let currentEventIndex = 0;
    let isTransitioning = false;

    const prevButton = document.getElementById('prevEvent');
    const nextButton = document.getElementById('nextEvent');

    function updateNavButtons() {
        prevButton.disabled = currentEventIndex === 0;
        nextButton.disabled = currentEventIndex === events.length - 1;
    }

    function updatePaginationDots(index) {
        paginationDots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    function updateEvent(index) {
        if(index < 0 || index >= events.length) return;
        if (isTransitioning) return;
        isTransitioning = true;

        // Añadir efecto de desvanecimiento
        content.style.opacity = '0';
        content.style.transform = 'translateY(20px)';

        setTimeout(() => {
            // Actualizar estado activo
            events.forEach(event => event.classList.remove('active'));
            events[index].classList.add('active');

            // Actualizar dots de paginación
            updatePaginationDots(index);

            // Actualizar contenido
            const event = eventData[events[index].dataset.event];
            const img = document.getElementById('eventImage');

            // Precargar imagen para transición más suave
            const tempImg = new Image();
            tempImg.onload = function() {
                img.src = this.src;
                document.getElementById('eventTitle').textContent = event.title;
                document.getElementById('eventLocation').textContent = event.location;
                document.getElementById('eventTime').textContent = event.time;
                document.getElementById('mapButton').href = event.maps_url;

                // Animar entrada
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';

                currentEventIndex = index;
                updateNavButtons();

                setTimeout(() => {
                    isTransitioning = false;
                }, 300);
            };
            tempImg.src = `${window.location.origin}/images/${event.image}`;
        }, 300);
    }

    // Manejador de botón previo
    prevButton.addEventListener('click', () => {
        if (currentEventIndex > 0) {
            updateEvent(currentEventIndex - 1);
        }
    });

    // Manejador de botón siguiente
    nextButton.addEventListener('click', () => {
        if (currentEventIndex < events.length - 1) {
            updateEvent(currentEventIndex + 1);
        }
    });

    // Clic en eventos del timeline
    events.forEach((event, index) => {
        event.addEventListener('click', () => {
            updateEvent(index);
        });
    });

    // Clic en dots de paginación móvil
    paginationDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            updateEvent(index);
        });
    });

    // Navegación con teclado
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            if (currentEventIndex < events.length - 1) {
                updateEvent(currentEventIndex + 1);
            }
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            if (currentEventIndex > 0) {
                updateEvent(currentEventIndex - 1);
            }
        }
    });

    // Inicializar estado de botones
    updateNavButtons();
});
