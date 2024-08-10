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
            alert('Please correct the errors in the form.');
        } else {
            alert('Form is valid and ready to submit.');
        }
    }

    async handleDelete(event) {
        // Find the closest table row and remove it
        const row = event.target.closest('tr');
        const id = row.getAttribute("id").replace('col-', '');


        const response = await fetch(`/delete_item_cart?id=${id}`);

        if (row) {
            row.remove();
            this.updateTotal();  // Update the total after removing an item
        }
    }
}
