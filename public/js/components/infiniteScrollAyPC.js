class InfiniteScrollAyPC {
    constructor() {
        var css = tools.nuevoElemento("link", "", {rel: "stylesheet", href:"/js/components/styles/infiniteScroll.css"});
        document.head.appendChild(css);
        this.page = 0;
        this.query = '';
        this.type = '';  // Inicialmente vacío

        this.loading = document.getElementById('loading');
        this.container = document.getElementsByClassName('products')[0];
        const form = document.getElementById('search-form');
        this.type = form.getAttribute('data-type');
        this.addLoadingIcon();
        this.initSearch();
        this.initObserver();
    }

    addLoadingIcon() {
        fetch('/source/pictures/assembl-icon.svg')
        .then(response => response.text())
        .then(data => {
            this.loading.innerHTML = data;
    });
    }

    initSearch() {
        const form = document.getElementById('search-form');
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            this.page = 0;
            this.query = document.getElementById('searchbar-templates').value;
            this.type = form.getAttribute('data-type');  // Obtener el valor del atributo `data-type`
            
            // Borra solo los productos y no el formulario o el div de loading
            const productAnchors = document.querySelectorAll('.products a');
            productAnchors.forEach(anchor => anchor.remove());
    
            this.chargeMoreProducts();
        });
    }

    initObserver() {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                this.chargeMoreProducts();
            }
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        });

        observer.observe(this.loading);
    }

    async chargeMoreProducts() {
        try {
            const response = await fetch(`${window.location.origin}/assemble_pc_${this.type}_page?page=${this.page}&query=${this.query}`);
            const data = await response.json();

            data.forEach(item => {
                const a1 = document.createElement("a");
                const a2 = document.createElement("a");
                const art = document.createElement('article');
                const pic = document.createElement("picture");
                const img = document.createElement("img");
                const h3 = document.createElement("h3");
                const p1 =  document.createElement("p");
                const button = document.createElement("button");

                a1.href = ``;
                a2.href = `${window.location.origin}/product?id=${item["id"]}`;
                a2.textContent = "detalles...";
                a2.target = "_blank";

                button.textContent = "Seleccionar";
                button.className = "assemblePcButton";
                button.title = "Agregar componente a la PC";
                button.addEventListener('click', () => {
                    this.selectProduct(item["id"]);
                });

                art.className = 'product';
                img.src = item["path_img"];
                img.className = "products-list";
                pic.appendChild(img);
                h3.textContent = item["description"];
                const priceText = document.createTextNode(`$${item["price"]}`);
                p1.appendChild(priceText);
                // const lineBreak = document.createElement("br");
                // p1.appendChild(lineBreak);
                p1.appendChild(a2);

                art.appendChild(pic);
                art.appendChild(h3);
                art.appendChild(p1);
                art.appendChild(button);
                //a1.appendChild(art);
                if (item["stock"] > 0){
                    this.container.insertBefore(art, this.container.lastElementChild);
                }
            });

            this.page++;
        } catch (error) {
            console.error('Error loading more data:', error);
        }
    }

    async selectProduct(id) {
        try {
            const response = await fetch(`${window.location.origin}/assemble_pc_add_product`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, type: this.type })
            });

            if (response.ok) {
                console.log('Product selected successfully');
                window.location.href = `${window.location.origin}/assemble_pc`;
            } else {
                console.error('Error selecting product');
            }
        } catch (error) {
            console.error('Error during fetch:', error);
        }
    }
}