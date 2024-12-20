class CartPage {
    constructor() {
        this.init();
    }

    init() {
        // Add event listeners to each quantity input
        document.querySelectorAll('.quantity-input').forEach((input) => {
            input.addEventListener('input', this.updateTotal.bind(this));
        });

        // Add event listener to the form submit event
        const form = document.querySelector('#cartForm');
        form.addEventListener('submit', this.handleSubmit.bind(this));

        // Add event listeners to each delete button
        document.querySelectorAll('.fa-times').forEach((button) => {
            button.addEventListener('click', this.handleDelete.bind(this));
        });

        // Initialize the total price on page load
        this.updateTotal();
    }

    updateTotal() {
        let total = 0;
        document.querySelectorAll('.quantity-input').forEach((input) => {
            const price = parseFloat(input.getAttribute('data-price'));
            const quantity = parseInt(input.value);
            total += price * quantity;
        });
        document.getElementById('totalPrice').innerText = total.toFixed(2) + '$';
    }

    async validateForm() {
        let valid = true;
        console.log("Starting validation...");

        // Collect promises for async validation
        const validationPromises = Array.from(document.querySelectorAll('.quantity-input')).map(async (input) => {
            const quantity = parseInt(input.value);
            const id = input.getAttribute('id').replace('quantity-', '');
            const max = await this.sendFetchServer(id);
            console.log(`Validating input with id ${id}: quantity = ${quantity}, max = ${max}`);

            if (quantity < 1 || quantity > max) {
                valid = false;
                input.classList.add('error');  // Add error class to indicate invalid input
            } else {
                input.classList.remove('error');
            }
        });

        // Wait for all async validations to complete
        await Promise.all(validationPromises);

        console.log("Validation result:", valid);
        return valid;
    }

    async sendFetchServer(id) {
        const response = await fetch(`/stock_id?id=${id}`);
        const data = await response.json();
        return data.stock;
    }

    async handleSubmit(event) {
        console.log("Form submit event triggered");
        const isValid = await this.validateForm();

        if (!isValid) {
            event.preventDefault();  // Prevent form submission if validation fails
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Por favor, corrija los errores en el formulario",
                customClass: {
                    title: "swal-title",
                    content: "swal-content",
                    confirmButton: "assemblePcButton",
                    cancelButton: "assemblePcButton"
                }
            });
        }
    }

    async handleDelete(event) {
        // Encuentra la fila más cercana al botón y obtén el ID del producto
        const row = event.target.closest('tr');
        const id = row.getAttribute("id").replace('col-', '');
    
        // Realiza el fetch para eliminar el producto del carrito
        const response = await fetch(`/delete_item_cart?id=${id}`);
    
        if (response.ok && row) {
            row.remove(); // Elimina la fila de la tabla
            this.updateTotal(); // Actualiza el total después de eliminar un elemento
    
            // Comprueba si todavía hay elementos en el carrito
            this.toggleSubmitButton();
        }
    }
    
    // Lógica para habilitar o deshabilitar el botón de compra
    toggleSubmitButton() {
        const submitButton = document.querySelector('.submit');
        const hasItems = document.querySelectorAll('tbody tr').length > 0;
    
        // Habilita o deshabilita el botón basado en la cantidad de elementos
        submitButton.disabled = !hasItems;
        location.reload();
    }
}
