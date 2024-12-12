class ManageOrder{
    constructor(){
        this.calculoTotal();
    }

    calculoTotal(){
        let total = 0;

        // Selecciona todas las filas dentro del tbody
        const rows = document.querySelectorAll("tbody tr");

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
}