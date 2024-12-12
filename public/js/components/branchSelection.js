class BranchSelection
{
    constructor() {
        var css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/branchSelection.css"})
            document.head.appendChild(css);
        
        const branchItems = document.querySelectorAll('.branch-item');
        const continueButton = document.querySelector('.form-submit');
        let selectedBranchId = null;

        // Manejar clic en los artículos de sucursal
        branchItems.forEach(function(item) {
            item.addEventListener('click', async function() {
                // Remover la clase 'article-selected' de todos los artículos
                branchItems.forEach(function(el) {
                    el.classList.remove('article-selected');
                });

                // Añadir la clase 'article-selected' al artículo clickeado
                this.classList.add('article-selected');
                
                // Guardar el ID del branch seleccionado
                const response = await fetch(`/set_branch_order?id=${this.id}`);
                const data = await response.json();
                if (response.status === 200){
                    selectedBranchId = this.id;
                    localStorage.setItem('selectedBranchId', selectedBranchId);
                    console.log(data.branch_id);
                    console.log(data.success);
                } else {
                    selectedBranchId = null;
                    localStorage.setItem('selectedBranchId', selectedBranchId);
                    console.log(data.message);
                }
                
            });
        });

        // Manejar clic en el botón de continuar
        continueButton.addEventListener('click', function() {
            if (selectedBranchId) {
                window.location.href = "/confirm_order";
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Por favor, selecciona una sucursal",
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
}