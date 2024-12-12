class EnterAddress
{
    constructor() {
        var css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/branchSelection.css"})
            document.head.appendChild(css);
        
        const addressItems = document.querySelectorAll('.address-item');
        const continueButton = document.querySelector('.form-submit');
        let selectedAddressId = null;

        // Manejar clic en los artículos de sucursal
        addressItems.forEach(function(item) {
            item.addEventListener('click', async function() {
                // Remover la clase 'article-selected' de todos los artículos
                addressItems.forEach(function(el) {
                    el.classList.remove('article-selected');
                });

                // Añadir la clase 'article-selected' al artículo clickeado
                this.classList.add('article-selected');
                
                // Guardar el ID del branch seleccionado
                const response = await fetch(`/set_address_order?id=${this.id}`);
                const data = await response.json();
                if (response.status === 200){
                    selectedAddressId = this.id;
                    localStorage.setItem('selectedAddressId', selectedAddressId);
                    console.log(data.address_id);
                    console.log(data.success);
                    Swal.fire({
                        icon: "success",
                        title: "Dirección elegida correctamente!",
                        timer: 5000,
                        customClass: {
                            title: "swal-title",
                            content: "swal-content",
                            confirmButton: "assemblePcButton",
                            cancelButton: "assemblePcButton"
                        }
                    });
                } else {
                    selectedAddressId = null;
                    localStorage.setItem('selectedAddressId', selectedAddressId);
                    console.log(data.message);
                }
                
            });
        });

        // Manejar clic en el botón de continuar
        continueButton.addEventListener('click', function() {
            if (selectedAddressId) {
                window.location.href = "/confirm_order";
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: 'Por favor, selecciona una sucursal',
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