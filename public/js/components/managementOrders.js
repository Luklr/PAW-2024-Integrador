class ManagementOrders {
    constructor() {
        this.initDragAndDrop(); // Inicializa el drag and drop
        this.startAutoFetch();  // Inicia el fetch automático
        this.sendAutoFetch();
    }

    initDragAndDrop() {
        // Asignar eventos a las secciones y artículos
        document.querySelectorAll('section').forEach(section => {
            section.addEventListener('dragover', this.allowDrop);
            section.addEventListener('drop', (event) => this.drop(event));
        });

        document.querySelectorAll('article').forEach(article => {
            article.setAttribute('draggable', true);
            article.addEventListener('dragstart', (event) => this.drag(event, article));
        });
    }

    allowDrop(event) {
        event.preventDefault(); // Permitir el drop en los elementos
    }

    drag(event, article) {
        event.dataTransfer.setData("text", event.target.id); // Guardar el ID del elemento arrastrado
        event.dataTransfer.setData("originalSection", article.parentElement.className); // Guardar la clase de la sección original
    }

    drop(event) {
        event.preventDefault(); // Prevenir el comportamiento por defecto
        const data = event.dataTransfer.getData("text"); // Obtener el ID del elemento arrastrado
        const originalSectionClass = event.dataTransfer.getData("originalSection"); // Obtener la clase de la sección original
        const element = document.getElementById(data);
        const targetSection = event.target.closest('section'); // Encontrar la sección de destino

        // Asegura que el elemento se añada a la sección correcta
        if (targetSection) {
            targetSection.appendChild(element);

            // Obtener el ID del artículo y la clase de la sección
            const articleId = parseInt((element.id).replace('order-', ''));
            const sectionClass = targetSection.className;

            // Enviar fetch a la URL correspondiente
            this.sendDropFetch(articleId, sectionClass, originalSectionClass, element);
        }
    }

    sendDropFetch(articleId, sectionClass, originalSectionClass, element) {
        const url = `/set_order_status`; // Reemplaza con tu URL de destino
        const data = {
            order_id: articleId,
            status: sectionClass
        };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw response; // Lanza la respuesta si no es ok
            }
            return response.json();
        })
        .then(data => {
            console.log('Success:', data);
        })
        .catch(error => {
            if (error.status === 400) {
                // Si el servidor responde con 400, devolver el artículo a su sección original
                const originalSection = document.querySelector(`section.${originalSectionClass}`);
                if (originalSection) {
                    originalSection.appendChild(element);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: 'Validacion fallida: ' + error,
                        customClass: {
                            title: "swal-title",
                            content: "swal-content",
                            confirmButton: "swal-confirm-button",
                            cancelButton: "swal-cancel-button"
                        }
                    });
                    console.error('Error: Validation failed. The order has been returned to the original section.');
                }
            } else {
                console.error('Error:', error);
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: error,
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "swal-confirm-button",
                        cancelButton: "swal-cancel-button"
                    }
                });
            }
        });
    }

    startAutoFetch() {
        setInterval(() => {
            this.sendAutoFetch();
        }, 20000); // 20 segundos
    }

    sendAutoFetch() {
        const url = `/get_orders_management`; // Reemplaza con tu URL de destino

        fetch(url, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => this.updateOrders(data.orders))
        .then(data => console.log('Auto Fetch Success:', data))
        .catch(error => console.error('Auto Fetch Error:', error));
    }

    updateOrders(orders) {
        for (const [status, ordersList] of Object.entries(orders)) {
            const section = document.querySelector(`section.${status}`);

            if (section && ordersList.length > 0) {
                // Ordenar la lista de órdenes por fecha
                ordersList.sort((a, b) => new Date(a.order_date.date) - new Date(b.order_date.date));

                ordersList.forEach(order => {
                    // verifico si ya existe
                    const orderExist = document.querySelector(`#order-${order.id}`);
                    if (!orderExist){
                        const article = document.createElement('article');
                        article.id = `order-${order.id}`;
                        article.draggable = true;
                        article.innerHTML = `
                            <p><strong>ID:</strong> ${order.id}</p>
                            <p><strong>Date:</strong> ${new Date(order.order_date.date).toLocaleString()}</p>
                            <p><strong>User ID:</strong> ${order.user_id}</p>
                            <p><strong>Branch:</strong> ${order.branch ? order.branch.name : 'N/A'}</p>
                            <p><strong>Address:</strong> ${order.address ? `${order.address.street} ${order.address.number}` : 'N/A'}</p>
                            <a href="/management_order?order_id=${order.id}"><p class="link">Detalles...</p></a>
                        `;
                        article.addEventListener('dragstart', (event) => this.drag(event, article));
                        section.appendChild(article);
                    }
                });
            }
        }
    }
}
