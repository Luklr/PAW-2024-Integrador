class tools {
    //nuevoElemento("script","",{scr: URL, name: "nombreDelScript"})
    static nuevoElemento(tag, contenido, atributos = {}) {
        let elemento = document.createElement(tag);

        for (const atributo in atributos) {
            elemento.setAttribute(atributo, atributos[atributo])
        }

        if (contenido.tagName)
            elemento.elemento.appendChild(contenido);
        else
            elemento.appendChild(document.createTextNode(contenido));
        return elemento;
    }

    static cargarScript(nombre, url, fnCallBack) {
        let elemento = document.querySelector("script#" + nombre);
        if (!elemento) {
            elemento = this.nuevoElemento("script", "", { src: url, id: nombre });

            //Funcion de Callback
            if (fnCallBack)
                elemento.addEventListener("load", fnCallBack);

            document.head.appendChild(elemento);
        }
    }
}