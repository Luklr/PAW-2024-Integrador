class Product{
    constructor(){
        document.getElementById('addToCartForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío tradicional del formulario
        
            const form = event.target;
            const formData = new FormData(form);
            const data = {
                "component_id": formData.get('product_id'),
                "quantity": formData.get('quantity')
            };
        
            fetch(`${window.location.origin}/add_to_cart`, {
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
                        title: "Producto añadido correctamente!",
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
            .catch(error => console.error('Error:', error));
        });
    }
}