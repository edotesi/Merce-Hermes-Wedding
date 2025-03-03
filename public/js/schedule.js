document.addEventListener('DOMContentLoaded', function() {
    const events = document.querySelectorAll('.event-marker');
    const content = document.querySelector('.event-content');
    let currentEventIndex = 0;
    let touchStartY = 0;
    let isTransitioning = false;

    const prevButton = document.getElementById('prevEvent');
    const nextButton = document.getElementById('nextEvent');

    function updateNavButtons() {
        prevButton.disabled = currentEventIndex === 0;
        nextButton.disabled = currentEventIndex === events.length - 1;
    }

    function updateEvent(index) {
        if(index < 0 || index >= events.length) return;
        if (isTransitioning) return;
        isTransitioning = true;

        content.style.opacity = '0';
        content.style.transform = 'translateY(20px)';

        setTimeout(() => {
            // Update active state
            events.forEach(event => event.classList.remove('active'));
            events[index].classList.add('active');

            // Update content
            const event = eventData[events[index].dataset.event];
            const img = document.getElementById('eventImage');

            const tempImg = new Image();
            tempImg.onload = function() {
                img.src = this.src;
                document.getElementById('eventTitle').textContent = event.title;
                document.getElementById('eventLocation').textContent = event.location;
                document.getElementById('eventTime').textContent = event.time + ' PM';
                document.getElementById('mapButton').href = event.maps_url;

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

    // Previous button handler
    prevButton.addEventListener('click', () => {
        if (currentEventIndex > 0) {
            updateEvent(currentEventIndex - 1);
        }
    });

    // Next button handler
    nextButton.addEventListener('click', () => {
        if (currentEventIndex < events.length - 1) {
            updateEvent(currentEventIndex + 1);
        }
    });

    // Mouse wheel navigation
    window.addEventListener('wheel', (e) => {
        if (window.innerWidth <= 768) return; // Disable on mobile

        e.preventDefault();
        if (e.deltaY > 0 && currentEventIndex < events.length - 1) {
            updateEvent(currentEventIndex + 1);
        } else if (e.deltaY < 0 && currentEventIndex > 0) {
            updateEvent(currentEventIndex - 1);
        }
    }, {
        passive: false
    });

    // Touch navigation
    window.addEventListener('touchstart', (e) => {
        touchStartY = e.touches[0].clientY;
    }, {
        passive: true
    });

    window.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touchEndY = e.touches[0].clientY;
        const diff = touchStartY - touchEndY;

        if (Math.abs(diff) > 50) {
            if (diff > 0 && currentEventIndex < events.length - 1) {
                updateEvent(currentEventIndex + 1);
            } else if (diff < 0 && currentEventIndex > 0) {
                updateEvent(currentEventIndex - 1);
            }
            touchStartY = touchEndY;
        }
    }, {
        passive: false
    });

    // Click on timeline events
    events.forEach((event, index) => {
        event.addEventListener('click', () => {
            updateEvent(index);
        });
    });

    // Keyboard navigation
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

    // Initialize button state
    updateNavButtons();
});
