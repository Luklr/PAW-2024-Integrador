class ProductABM {
    constructor() {
        // Asociar eventos de submit a cada formulario
        document.querySelector('.addStockForm').addEventListener('submit', this.handleAddStock.bind(this));
        document.querySelector('.reduceStockForm').addEventListener('submit', this.handleReduceStock.bind(this));
        document.querySelector('.DeleteProductForm').addEventListener('submit', this.handleDeleteStock.bind(this));
    }

    handleAddStock(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            "component_id": formData.get('product_id'),
            "quantity": formData.get('quantityAdd')
        };

        this.sendRequest('/add_component_stock', data, "Stock añadido correctamente!");
    }

    handleReduceStock(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            "component_id": formData.get('product_id'),
            "quantity": formData.get('quantityReduce')
        };

        this.sendRequest('/reduce_component_stock', data, "Stock reducido correctamente!");
    }

    handleDeleteStock(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            "component_id": formData.get('product_id')
        };

        this.sendRequest('/delete_component_stock', data, "Producto eliminado correctamente!");
    }

    sendRequest(url, data, successMessage) {
        fetch(`${window.location.origin}${url}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error ' + response.status + ': ' + response.statusText);
            } else {
                Swal.fire({
                    icon: "success",
                    title: successMessage,
                    timer: 5000,
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "assemblePcButton",
                        cancelButton: "assemblePcButton"
                    }
                });
            }
            return response.json();
        })
        .then(data => console.log('Success:', data))
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Ocurrió un error al procesar la solicitud.",
                customClass: {
                    title: "swal-title",
                    content: "swal-content",
                    confirmButton: "assemblePcButton",
                    cancelButton: "assemblePcButton"
                }
            });
        });
    }
}