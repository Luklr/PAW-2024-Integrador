class SelectProductForm {
    constructor(pContenedor) {
        let contenedor = pContenedor.tagName ? pContenedor : document.querySelector(pContenedor);

        if (!contenedor) {
            console.error("Elemento HTML para generar el plan no encontrado");
            return;
        }

        const radioButtons = contenedor.querySelectorAll('input');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                const selectedValue = radio.value;
                const url = new URL(window.location);
                url.searchParams.set('type', selectedValue);
                window.location.href = url;
            });
        });

    }

    agregarListeners() {
        this.inputs.forEach(elemento => {
            elemento.addEventListener('click', function (event) {
                
            });
        });
    }


}