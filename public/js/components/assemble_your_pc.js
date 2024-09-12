class AssembleYourPc {
    constructor () {
        this.init();
    }

    init() {
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".article-part-assemble button");
        
            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    const type = button.classList[0];
                    const url = button.getAttribute("data-url");
        
                    fetch('/verify_assemble_pc_next_component', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ type: type }),
                    })
                    .then(response => {
                        if (response.status === 200) {
                            window.location.href = url;
                        } else if (response.status === 400) {
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: "Siga el orden de elección de componentes",
                                customClass: {
                                    title: "swal-title",
                                    content: "swal-content",
                                    confirmButton: "swal-confirm-button",
                                    cancelButton: "swal-cancel-button"
                                }
                            });
                            alert("El componente seleccionado no es válido.");
                        } else {
                            console.error('Error inesperado:', response.status);
                        }
                    })
                    .catch(error => {
                        console.error('Error en el fetch:', error);
                    });
                });
            });
        });
    }
}