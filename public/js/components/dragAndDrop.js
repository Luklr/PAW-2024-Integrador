class dragAndDrop {
    constructor(pContenedor,maxImages) {
        //Conseguir nodo de Drag and Drop
        let contenedor = pContenedor.tagName
            ? pContenedor
            : document.querySelector(pContenedor);

        if (!contenedor) {
            console.error("Elemento HTML para generar la dropArea no encontrado");
            return;
        }

        // Cargar el archivo de estilos (CSS)
        let css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/dragAndDrop.css"})
        document.head.appendChild(css);

        const dropArea = document.querySelector(".drop-area");
        const dragText = document.querySelector("h3");
        const input = document.querySelector(".inputfile");
        let files;

        input.addEventListener("click", (event) => {
            input.click();
        })

        input.addEventListener("change", (event) => {
            files = input.files;
            dropArea.classList.add("active");
            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    processFile(file);
                });
            }
            dropArea.classList.remove("active");
        });

        // Al ocurrer la accion de dragover, se cambia la clase a "Active" (prevent default evita que se a bra la imagen en otra ventana)
        dropArea.addEventListener("dragover", (event) =>{
            event.preventDefault();
            dropArea.classList.add("active");
            dragText.textContent = "Suelta para subir los archivos"
        })

        // Al dejar de arrastrar un file sobre la zona, deberia volver a su estado inicial.
        dropArea.addEventListener("dragleave", (event) =>{
            event.preventDefault();
            dropArea.classList.remove("active");
            dragText.textContent = "Arrastra y suelta imagenes"
        })

        // Al droppear la imagen, se debe cargar la imagen en el sistema
        dropArea.addEventListener("drop", (event) => {
            event.preventDefault();
            dropArea.classList.remove("active");
            dragText.textContent = "Arrastra y suelta imágenes";
        
            // Crear un objeto DataTransfer
            const dataTransfer = new DataTransfer();
            
            // Obtener los archivos arrastrados
            files = event.dataTransfer.files;
            
            if (files.length > 0) {
                // Procesar cada archivo en `files`
                Array.from(files).forEach(file => {
                    processFile(file);
                    // Agregar cada archivo al objeto DataTransfer
                    dataTransfer.items.add(file);
                });
            }
        
            // Asignar los archivos del objeto DataTransfer a input.files
            input.files = dataTransfer.files;
        });

        // Muestra los archivos ya cargados
        function showFiles(files) {
            if ((files.length > 0) && (files.length < maxImages)) {
                // Procesar cada archivo en `files`
                Array.from(files).forEach(file => {
                    processFile(file);
                });
            } else if (files.length > maxImages) {
                alert("Solo puedes subir " + maxImages + " archivo/s como máximo");
            }
        }

        // Procesar archivo/s
        function processFile(file) {
            const docType = file.type;
            const validExtensions = ['image/jpeg','image/jpg','image/png','image/gif'];

            const fileContainers = document.querySelectorAll(".file-container");
            if (fileContainers.length >= maxImages) {
                alert("Solo puedes subir " + maxImages + " archivo/s como máximo");
                return; // Salir de la función para no procesar más archivos
            }

            if(validExtensions.includes(docType)) {
                const fileReader = new FileReader();
                const id = `file-${Math.random().toString(32).substring(7)}`;

                fileReader.addEventListener('load', (event) => {
                    const fileUrl = fileReader.result;
                    const image = `
                        <div id="${id}" class="file-container">
                            <img src="${fileUrl}" alt="${file.name}" width="50">
                            <div class="status">
                                <span>${file.name}</span>
                                <span class="status-text">
                                Loading...
                                </span>
                            </div>
                        </div>
                    `;
                    const html = document.querySelector('.preview').innerHTML;
                    document.querySelector('.preview').innerHTML = image + html;
                });
                fileReader.readAsDataURL(file);
                //uploadFile(file, id);
            } else {
                alert("No es un archivo válido");
            }

        }

    
        async function uploadFile(file, id) {
            const formData = new FormData();
            formData.append("file",file);

            try {
                const response = await fetch(`${window.location.origin}/upload`, {
                    method: "POST",
                    body: formData,
                });
                const responseText = await response.text();
                document.querySelector(
                    `#${id}.status-text`
                ).innerHTML = `<span class="success"> Archivo subido correctamente...</span>`;
            } catch(error) {
                document.querySelector(
                    `#${id}.status-text`
                ).innerHTML = `<span class="failure"> El archivo no pudo subirse...</span>`;
            }
        }
    
    }
}