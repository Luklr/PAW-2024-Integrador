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
                        text: 'Validacion fallida',
                        customClass: {
                            title: "swal-title",
                            content: "swal-content",
                            confirmButton: "assemblePcButton",
                            cancelButton: "assemblePcButton"
                        }
                    });
                    console.error('Error: Validation failed. The order has been returned to the original section.');
                }
            } else {
                console.error('Error:', error);
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "assemblePcButton",
                        cancelButton: "assemblePcButton"
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
                // Ordenar las órdenes por fecha
                ordersList.sort((a, b) => new Date(a.order_date.date) - new Date(b.order_date.date));
    
                ordersList.forEach(order => {
                    // Verificar si la orden ya existe
                    if (!section.querySelector(`#order-${order.id}`)) {
                        const article = document.createElement('article');
                        article.id = `order-${order.id}`;
                        article.draggable = true;
    
                        // Crear contenido del artículo
                        article.innerHTML = `
                            <p><strong>ID:</strong> ${order.id}</p>
                            <p><strong>Fecha:</strong> ${new Date(order.order_date.date).toLocaleString()}</p>
                            <p><strong>ID usuario:</strong> ${order.user_id}</p>
                            ${order.branch 
                                ? `<p><strong>RETIRA EN:</strong> ${order.branch.name}</p>`
                                : `<p><strong>ENVÍO A:</strong> ${order.address.street} ${order.address.number}</p>`}
                            <div class="details-container">
                                ${order.address ? this.createDeliveryCostInput(order.id, order.delivery_price) : ''}
                                <a href="/management_order?order_id=${order.id}">
                                    <p class="link">Detalles...</p>
                                </a>
                            </div>
                        `;
                        article.classList.add(`${order.address ? "delivery" : "branch"}`);
    
                        // Agregar funcionalidad de arrastre
                        if (order.address)
                            this.setupDeliveryButton(article, order.id);
                        article.addEventListener('dragstart', (event) => this.drag(event, article));
                        section.appendChild(article);
                    }
                });
            }
        }
    }
    
    createDeliveryCostInput(orderId, delivery_price) {
        return `
            <div class="delivery-cost">
            ${delivery_price !== null 
                ? `<p><strong>Costo de envío:</strong> $${parseFloat(delivery_price).toFixed(2)}</p>` 
                : ""}
                <input type="number" placeholder="Costo de envío" class="delivery-input">
                <button class="delivery-button">Enviar</button>
            </div>`;
    }
    
    setupDeliveryButton(article, orderId) {
        const deliveryDiv = article.querySelector('.delivery-cost');
        const input = deliveryDiv.querySelector('.delivery-input');
        const button = deliveryDiv.querySelector('.delivery-button');
        let costParagraph = deliveryDiv.querySelector('p'); // Buscar el párrafo existente
    
        button.addEventListener('click', () => {
            const cost = parseFloat(input.value);
            if (!isNaN(cost) && cost > 0) {
                button.textContent = "Enviando...";
                button.disabled = true;
    
                fetch(`/set_delivery_price`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: orderId, delivery_price: cost })
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: "success",
                            title: "Costo de envío actualizado correctamente!",
                            timer: 5000,
                            customClass: {
                                title: "swal-title",
                                content: "swal-content",
                                confirmButton: "assemblePcButton",
                                cancelButton: "assemblePcButton"
                            }
                        });
                    }
                })
                .then(() => {
                    // Actualizar el texto del párrafo existente o crear uno nuevo
                    if (costParagraph) {
                        costParagraph.innerHTML = `<strong>Costo de envío:</strong> $${cost.toFixed(2)}`;
                    } else {
                        costParagraph = document.createElement('p');
                        costParagraph.innerHTML = `<strong>Costo de envío:</strong> $${cost.toFixed(2)}`;
                        deliveryDiv.insertBefore(costParagraph, deliveryDiv.firstChild);
                    }
                    button.textContent = "Enviar";
                    button.disabled = false;
                })
                .catch(error => {
                    console.error('Error al enviar el costo de envío:', error);
                    button.textContent = "Enviar";
                    button.disabled = false;
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "No se pudo enviar el costo de envío.",
                        customClass: {
                            title: "swal-title",
                            content: "swal-content",
                            confirmButton: "assemblePcButton",
                            cancelButton: "assemblePcButton"
                        }
                    });
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Por favor, ingresa un número válido para el costo de envío.",
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "assemblePcButton",
                        cancelButton: "assemblePcButton"
                    }
                });
            }
        });
    }
}
