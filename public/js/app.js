class App {
    constructor() {
        const currentUrl = window.location.pathname;
        /*
        //Inicializar la funcionalidad Menu
        var esDispositivoMovil = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (esDispositivoMovil){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("menuHam", "js/components/menuHam.js", () => {
                    let menu = new menuHam("nav");
                });
            });
        }
        */

        if (currentUrl === "/create_product") {
            //Cargar el script solo en la página de inicio
            tools.cargarScript("selectProductForm", "js/components/selectProductForm.js", () => {
                let selectProductForm = new SelectProductForm("fieldset.new-product-fieldset");
            });
        }

        if (currentUrl === "/create_product") {
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("dragAndDrop", "js/components/dragAndDrop.js", () => {
                    let dropArea = new dragAndDrop("div.drop-area",1);
                });
            });
        }

        if (currentUrl === "/products") {
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("infiniteScroll", "js/components/infiniteScroll.js", () => {
                    let products = new ScrollInfinito();
                });
            });
        }

        if (currentUrl === "/product") {
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("product", "js/components/product.js", () => {
                    let product = new Product();
                })
            })
        }
    }
}

let app = new App();