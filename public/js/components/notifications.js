class Notifications {
    constructor() {
        var css = tools.nuevoElemento("link", "", {rel: "stylesheet", href:"/js/components/styles/notifications.css"});
        document.head.appendChild(css);
        
        const notificationIcon = document.getElementById('notification-icon');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const notificationList = document.getElementById('notification-list');

        // Set the default icon
        notificationIcon.src = './source/pictures/notification-none.svg';
        
        // Check for unread notifications in the DOM
        this.checkUnreadNotifications();

        notificationIcon.addEventListener('click', () => {
            // Toggle visibility of the dropdown
            notificationDropdown.classList.toggle('hidden');

            // Mark notifications as read when dropdown is shown
            if (!notificationDropdown.classList.contains('hidden')) {
                this.markNotificationsAsSeen();
            }
        });

        // Add event listeners to delete buttons
        this.addDeleteListeners();
    }

    checkUnreadNotifications() {
        const hasUnread = document.querySelector('.notSeen');
        if (hasUnread) {
            document.getElementById('notification-icon').src = './source/pictures/notification-unread.svg';
        } else {
            document.getElementById('notification-icon').src = './source/pictures/notification-none.svg';
        }
    }

    markNotificationsAsSeen() {
        fetch('/set_notifications_seen', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                document.getElementById('notification-icon').src = './source/pictures/notification-none.svg';
            } else {
                console.error('Error marking notifications as seen:', response.statusText);
            }
        })
        .catch(error => {
            console.error('Error marking notifications as seen:', error);
        });
    }

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
            } else {
                console.error('Error deleting notification:', response.statusText);
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Notifications();
});
