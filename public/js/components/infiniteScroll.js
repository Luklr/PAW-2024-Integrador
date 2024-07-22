class ScrollInfinito{
    constructor(){
        this.page = 0;
        
        this.loading = document.getElementById('loading');
        this.container = document.getElementsByClassName('products')[0];
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
        // this.loading.style.display = 'none';
    }

    async chargeMoreProducts() {
        try {
            // this.loading.style.display = 'block';
            const response = await fetch(`${window.location.origin}/products_page?page=${this.page}`);
            const data = await response.json();

            data.forEach(item => {
                const a = document.createElement("a");
                const art = document.createElement('article');
                const pic = document.createElement("picture");
                const img = document.createElement("img");
                const h3 = document.createElement("h3");
                const p =  document.createElement("p");
                console.log(item);
                a.href = `${window.location.origin}/product?id=${item["id"]}`;
                art.className = 'product';

                // img.src = item.fields["path_img"] ? item.fields["path_img"] : "#";
                pic.appendChild(img);
                h3.textContent = item["description"];
                p.textContent = `${item["stock"]} - $${item["price"]}`;
                
                art.appendChild(pic);
                art.appendChild(h3);
                art.appendChild(p);
                a.appendChild(art);
                this.container.insertBefore(a, this.container.lastElementChild);
            });

            this.page++;
        } catch (error) {
            console.error('Error loading more data:', error);
        } finally {
            // this.loading.style.display = 'none';
        }
    };
}