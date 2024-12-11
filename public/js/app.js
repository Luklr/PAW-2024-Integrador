class App {
    constructor() {
        fetch('/source/pictures/assembl-icon.svg')
            .then(response => response.text())
            .then(data => {
                document.querySelector('.loader').innerHTML = data;
        });

        window.addEventListener("load", async () => {
            const loader = document.querySelectorAll(".section-loader")[0];
            const pageLoadTime = performance.now(); // Tiempo en milisegundos desde el inicio de la carga
            
            if (pageLoadTime < 1000) {
                // Si la carga tomó menos de 1 segundo
                loader.classList.add("none-loader");
            } else {
                // Si la carga tomó más de 1 segundo
                loader.classList.add("hide-loader");
                await delay(1000);
                loader.classList.add("none-loader");
            }
        });

        const currentUrl = window.location.pathname;

        var esDispositivoMovil = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (esDispositivoMovil){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("menuHam", "js/components/menuHam.js", () => {
                    let menu = new menuHam("nav.nav-header");
                });
            });
        }
        
        
        // Función de delay
        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

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
        tools.cargarScript("notifications", "js/components/notifications.js", () => {
            let notifications = new Notifications();
        });

        if (currentUrl === "/") {
            //Cargar el script solo en la página de inicio
            tools.cargarScript("carousell", "js/components/carousell.js", () => {
                let carousell = new Carousell();
            });
        }

        if (currentUrl === "/create_product") {
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
                    let infiniteScroll = new InfiniteScroll();
                });
                tools.cargarScript("geminiChat", "js/components/geminiChat.js", () => {
                    let geminiChat = new GeminiChat();
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

        if (currentUrl === "/cart"){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("cartPage", "js/components/cartPage.js", () => {
                    let cartPage = new CartPage();
                })
            })
        }

        if (currentUrl === "/branch_selection"){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("branchSelection", "js/components/branchSelection.js", () => {
                    let branchSelection = new BranchSelection();
                })
            })
        }

        if (currentUrl === "/enter_address"){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("enterAddress", "js/components/enterAddress.js", () => {
                    let enterAddress = new EnterAddress();
                })
            })
        }

        if (currentUrl === "/confirm_order"){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("confirmOrder", "js/components/confirmOrder.js", () => {
                    let confirmOrder = new ConfirmOrder();
                })
            })
        }

        if (currentUrl === "/management_orders"){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("managementOrders", "js/components/managementOrders.js", () => {
                    let managementOrders = new ManagementOrders();
                })
            })
        }

        if (currentUrl === "/assemble_pc"){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("assembleYourPc", "js/components/assembleYourPc.js", () => {
                    let assembleYourPc = new AssembleYourPc();
                })
            })
        }

        const validUrls = ["/assemble_pc_case", "/assemble_pc_cpu","/assemble_pc_gpu",
            "/assemble_pc_ram", "/assemble_pc_motherboard", "/assemble_pc_internal_hard_drive",
            "/assemble_pc_power_supply", "/assemble_pc_cpu_fan"];
        if (validUrls.includes(currentUrl)){
            document.addEventListener("DOMContentLoaded", () => {
                tools.cargarScript("infiniteScrollAyPC", "js/components/infiniteScrollAyPC.js", () => {
                    let infiniteScrollAyPC = new InfiniteScrollAyPC();
                })
            })
        }
    }
}

let app = new App();