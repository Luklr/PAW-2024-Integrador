class ManageOrder {
    constructor() {
        this.calculoTotal();
        this.setupDeliveryForm();
    }

    calculoTotal() {
        let total = 0;

        // Selecciona todas las filas dentro del tbody
        const rows = document.querySelectorAll("tbody tr, tfoot tr");

        rows.forEach(row => {
            const priceElement = row.querySelector(".price");
            const quantityElement = row.querySelector(".quantity");

            if (priceElement && quantityElement) {
                const price = parseFloat(priceElement.dataset.price);
                const quantity = parseInt(quantityElement.dataset.quantity, 10);

                if (!isNaN(price) && !isNaN(quantity)) {
                    total += price * quantity;
                }
            }
        });

        // Actualiza el total en el elemento con id "totalPrice"
        const totalPriceElement = document.getElementById("totalPrice");
        if (totalPriceElement) {
            totalPriceElement.textContent = `${total.toFixed(2)}$`;
        }
    }

    setupDeliveryForm() {
        const deliveryForm = document.getElementById('deliveryForm');
        if (deliveryForm) {
            deliveryForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const deliveryPrice = document.getElementById('deliveryprice').value;
                const form = document.getElementById('deliveryForm');
                const orderId = form.getAttribute("data");
                const response = await fetch(`/set_delivery_price`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order_id:orderId, delivery_price: deliveryPrice })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
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

                        // Agregar el precio de envío a la tabla
                        const tfoot = document.querySelector('tfoot');
                        if (!document.querySelector('#deliveryRow')) {
                            const row = document.createElement('tr');
                            row.id = 'deliveryRow';
                            row.innerHTML = `
                                <td colspan="5">Envío</td>
                                <td colspan="2" class="price" data-price="${deliveryPrice}">${deliveryPrice}$</td>
                                <td colspan="3" class="quantity" data-quantity="1">1</td>
                            `;
                            tfoot.appendChild(row);
                        }

                        this.calculoTotal();
                    }
                } else {
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
                }
            });
        }
    }
}
