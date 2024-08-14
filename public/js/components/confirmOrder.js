class ConfirmOrder {
    constructor() {
        this.updateTotal();
    }

    updateTotal() {
        let total = 0;
        document.querySelectorAll('.component').forEach((component) => {
            const priceElement = component.querySelector('.price');
            const quantityElement = component.querySelector('.quantity');

            const price = parseFloat(priceElement.getAttribute('data-price'));
            const quantity = parseInt(quantityElement.getAttribute('data-quantity'));

            total += price * quantity;
        });
        document.getElementById('totalPrice').innerText = total.toFixed(2) + '$';
    }
}