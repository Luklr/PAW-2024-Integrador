class AssembleYourPc {
    constructor() {
        this.init();
    }

    init() {
        const buttons = document.querySelectorAll(".article-part-assemble button");
    
        buttons.forEach(button => {
            if (button.innerText === "Elegir") {
                button.addEventListener("click", () => this.handleChoose(button));
            } else if (button.innerText === "Eliminar") {
                button.addEventListener("click", () => this.handleDelete(button));
            }
        });

        const buyButton = document.querySelector("#buy-button");
        if (buyButton) {
            buyButton.addEventListener("click", (event) => this.handleBuy(event));
        }
    }

    handleChoose(button) {
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
            } else {
                console.error('Error inesperado:', response.status);
            }
        })
        .catch(error => {
            console.error('Error en el fetch:', error);
        });
    }

    handleBuy(event) {
        // Prevenir el enlace de ser activado si los componentes no están elegidos
        event.preventDefault();

        // Verificar si todos los componentes requeridos están seleccionados
        fetch('/complete_assemble_pc')
        .then(response => {
            if (response.status === 200) {
                window.location.href = "/cart";
            } else if (response.status === 400) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Debe elegir todos los componentes antes de comprar.",
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "swal-confirm-button",
                        cancelButton: "swal-cancel-button"
                    }
                });
            } else {
                console.error('Error inesperado:', response.status);
            }
        })
        .catch(error => {
            console.error('Error en el fetch:', error);
        });
    }

    camelToSnake(str) {
        return str.replace(/([A-Z])/g, '_$1').toLowerCase();
    }

    handleDelete(button) {
        const type = button.classList[0];
        const url = button.getAttribute("data-url");
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: type }),
        })
        .then(response => {
            if (response.status === 200) {
                Swal.fire({
                    icon: "success",
                    title: "Componente eliminado correctamente!",
                    //toast: true,
                    position: "top-end",
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "swal-confirm-button",
                    }
                });
                // Actualizar la vista eliminando el componente
                const article = button.closest(".article-part-assemble");
                this.resetComponent(article);

                // Actualiza eliminando a los componentes siguientes
                let nextSibling = article.nextElementSibling;
                while (nextSibling && nextSibling.classList.contains("article-part-assemble")) {
                    this.resetComponent(nextSibling);
                    nextSibling = nextSibling.nextElementSibling;
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "No se pudo eliminar el componente",
                    text: `Error: ${response.status}`,
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "swal-confirm-button",
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error en el fetch:', error);
        });
    }

    resetComponent(article) {
        // Eliminar contenido relacionado con el componente
        const picture = article.querySelector("picture");
        if (picture) picture.remove();
        
        const paragraph = article.querySelector("p");
        if (paragraph) paragraph.innerText = "requerido";

        const deleteButton = article.querySelector("button");
        const type = deleteButton.classList[0];
        if (deleteButton) deleteButton.remove();

        // Validar si el tipo existe
        if (!type) {
            console.error("Error: El atributo 'data-type' no está definido en el artículo.");
            return;
        }
        const newButton = document.createElement("button");
        newButton.innerText = "Elegir";
        newButton.setAttribute("data-url", `/assemble_pc_${this.camelToSnake(type)}`);
        newButton.classList.add(type);
        newButton.classList.add("assemblePcButton");
        newButton.addEventListener("click", () => this.handleChoose(newButton));

        article.appendChild(newButton);
    }
}