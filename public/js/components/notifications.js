class Notifications {
    constructor() {
        var css = tools.nuevoElemento("link", "", {rel: "stylesheet", href:"/js/components/styles/notifications.css"});
        document.head.appendChild(css);

        const notificationLink = document.querySelector('.notifications-anchor'); // Enlace de notificaciones
        const notificationDropdown = document.getElementById('notification-dropdown');
        const notificationList = document.getElementById('notification-list');

        // Establecer el icono predeterminado
        this.updateNotificationIcon();

        // Verificar las notificaciones no leídas en el DOM
        this.checkUnreadNotifications();

        notificationLink.addEventListener('click', () => {
            // Alternar visibilidad del dropdown
            notificationDropdown.classList.toggle('hidden');
            // this.adjustDropdownPosition();
            // Marcar las notificaciones como leídas cuando el dropdown se muestra
            if (!notificationDropdown.classList.contains('hidden')) {
                this.markNotificationsAsSeen();
            }
        });

        // Añadir escuchadores de eventos a los botones de eliminar
        this.addDeleteListeners();
        // this.adjustDropdownPosition();
    }

    // adjustDropdownPosition() {
    //     const notificationDropdown = document.getElementById('notification-dropdown');
    //     const rect = notificationDropdown.getBoundingClientRect();
    //     const windowHeight = window.innerHeight;

    //     // Si la ventana de notificaciones se sale de la pantalla, ajustarla hacia arriba
    //     if (rect.bottom > windowHeight) {
    //         notificationDropdown.style.top = `-${rect.height}px`; // Ajusta hacia arriba
    //     }
    // }

    // Función para actualizar el icono de notificaciones
    updateNotificationIcon() {
        const notificationLink = document.querySelector('.notifications-anchor');
        const hasUnread = document.querySelector('.notSeen');
        if (hasUnread) {
            notificationLink.style.backgroundImage = "url('/source/pictures/notification-unread.svg')";
        } else {
            notificationLink.style.backgroundImage = "url('/source/pictures/notification-none.svg')";
        }
    }

    // Comprobar si hay notificaciones no leídas
    checkUnreadNotifications() {
        const hasUnread = document.querySelector('.notSeen');
        if (hasUnread) {
            this.updateNotificationIcon(); // Cambiar icono si hay notificaciones no leídas
        } else {
            this.updateNotificationIcon(); // Icono predeterminado
        }
    }

    // Marcar las notificaciones como leídas
    markNotificationsAsSeen() {
        fetch('/set_notifications_seen', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                this.updateNotificationIcon(); // Restablecer icono cuando se marcan como leídas
            } else {
                console.error('Error marking notifications as seen:', response.statusText);
            }
        })
        .catch(error => {
            console.error('Error marking notifications as seen:', error);
        });
    }

    // Añadir escuchadores de eventos a los botones de eliminar
    addDeleteListeners() {
        const deleteButtons = document.querySelectorAll('.notification-art button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const notificationElement = event.target.closest('li');
                const notificationId = event.target.closest('.notification-art').id;

                this.deleteNotification(notificationId, notificationElement);
            });
        });
    }

    // Eliminar una notificación
    deleteNotification(notificationId, notificationElement) {
        fetch(`/delete_notification?id=${notificationId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                notificationElement.remove();
                this.checkUnreadNotifications(); // Actualizar icono después de eliminar
            } else {
                console.error('Error deleting notification:', response.statusText);
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
        });
    }
}
