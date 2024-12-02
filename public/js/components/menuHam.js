class menuHam {
    constructor(pContenedor) {
        //Conseguir nodo NAV
        let contenedor = pContenedor.tagName
            ? pContenedor
            : document.querySelector(pContenedor);

        if (!contenedor) {
            console.error("Elemento HTML para generar el menu no encontrado");
            return;
        }

        contenedor.classList.add("menuHam");
        contenedor.classList.add("menuHam-Abrir");

        let css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/menuHam.css"})
        document.head.appendChild(css);
        // Armar Boton
        let boton = tools.nuevoElemento("button", "", {
            class:"menuHam-Inicial"
        });

        boton.addEventListener("click", (event) => {
            if ((event.target.classList.contains("menuHam-Abrir")) || (event.target.classList.contains("menuHam-Inicial"))) {
                event.target.classList.add("menuHam-Cerrar");
                event.target.classList.remove("menuHam-Abrir");
                event.target.classList.remove("menuHam-Inicial");
                contenedor.classList.add("menuHam-Cerrar");
                contenedor.classList.remove("menuHam-Abrir");
            } else {
                event.target.classList.add("menuHam-Abrir");
                event.target.classList.remove("menuHam-Cerrar");
                contenedor.classList.add("menuHam-Abrir");
                contenedor.classList.remove("menuHam-Cerrar");
            }
        })
    
        //Insertar boton en el NAX
        contenedor.prepend(boton);
    }
}