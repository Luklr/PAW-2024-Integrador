class Carousell {
    constructor(){
        // se inicializan variables
        this.principal = document.querySelector(".principal");
        this.principalCarousell = document.querySelector(".principal_carousell");
        this.images = this.principalCarousell.querySelectorAll("article");
        console.log(this.images);
        var css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/carousell.css"})
            document.head.appendChild(css);
        this.index = 1;
        this.prevButton = tools.nuevoElemento("button", "<", {class: "botonCarousell"});
        this.principal.appendChild(this.prevButton);

        this.nextButton = tools.nuevoElemento("button", ">", {class: "botonCarousell"});
        this.principal.appendChild(this.nextButton);
        this.transitionXCarousell(this.principal, this.principalCarousell, this.images, this.index, this.prevButton, this.nextButton);
    }

    transitionXCarousell (principal, principalCarousell, images, index, prevButton, nextButton){
        function medidaPantalla() {
            let anchoPantalla = window.innerWidth;
            if (anchoPantalla >= 1080) {
                return 62
            } else if (anchoPantalla < 1080){
                return 82
            }
        }
        prevButton.addEventListener("click", () => {
            let vwDeTrancision = medidaPantalla();
            index--;
            if (index < 0){
                index = images.length - 1; // Establecer el índice al último elemento
                principalCarousell.style.transform = "translateX(" + (index * -vwDeTrancision) + "vw)";
            } else {
                principalCarousell.style.transform = "translateX(" + (index * -vwDeTrancision) + "vw)";
            }
            clearTimeout(intervalo);
        });
        
        nextButton.addEventListener("click", () => {
            let vwDeTrancision = medidaPantalla();
            let percentage = index * -vwDeTrancision;
            index++;
            if (index > images.length){
                index = 1;
                principalCarousell.style.transform = "translateX(" + 0 + "vw)";
            } else
                principalCarousell.style.transform = "translateX(" + percentage + "vw)";
            clearTimeout(intervalo);
        });
        let intervalo = null;
        
        intervalo = setInterval (() => {
            let vwDeTrancision = medidaPantalla();
            let percentage = index * -vwDeTrancision;
            index++;
            if (index > images.length){
                index = 0;
            } else
            principalCarousell.style.transform = "translateX(" + percentage + "vw)";
            
        }, 6000);
    }

    
}