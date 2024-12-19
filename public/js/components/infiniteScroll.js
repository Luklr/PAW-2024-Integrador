class InfiniteScroll {
    constructor() {
        var css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/infiniteScroll.css"})
            document.head.appendChild(css);
        this.page = 0;
        this.query = '';
        this.type = '';

        this.loading = document.getElementById('loading');
        this.container = document.getElementsByClassName('products')[0];

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

            // Verifica si el filtro seleccionado es "all"
            const selectedType = document.querySelector('input[name="type"]:checked')?.value || 'all';
            this.type = (selectedType === 'all') ? '' : selectedType;

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
            const response = await fetch(`${window.location.origin}/products_page?page=${this.page}&query=${this.query}&type=${this.type}`);
            const data = await response.json();

            data.forEach(item => {
                const a = document.createElement("a");
                const art = document.createElement('article');
                const pic = document.createElement("picture");
                const img = document.createElement("img");
                const h3 = document.createElement("h3");
                const p =  document.createElement("p");

                a.href = `${window.location.origin}/product?id=${item["id"]}`;
                art.className = 'product';
                img.src = item["path_img"];
                img.className = "products-list";
                pic.appendChild(img);
                h3.textContent = item["description"];
                p.textContent = `$${item["price"]}`;
                
                art.appendChild(pic);
                art.appendChild(h3);
                art.appendChild(p);
                a.appendChild(art);
                if (item["stock"] > 0){
                    this.container.insertBefore(a, this.container.lastElementChild);
                }
            });

            this.page++;
        } catch (error) {
            console.error('Error loading more data:', error);
        }
    }
}