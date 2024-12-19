// Source: https://github.com/mercadopago/checkout-payment-sample/blob/master/client/html-js/js/index.js
class MercadoPagoComponent {
  constructor() {
    this.createPreference = this.createPreference.bind(this);
    this.init();
  }

  init() {
    var mpContainer = tools.nuevoElemento("div", "", {
      id: "wallet_container",
    });
    const submitButton = document.querySelector(".submit.form-submit");
    submitButton.addEventListener("click", (event) => {
      event.preventDefault();
      const paymentDiv = document.getElementById("payment");
      paymentDiv.appendChild(mpContainer);
      this.createPreference();
      submitButton.style.display = "none";
    });
    }

    createPreference() {
    console.log("Ejecutando createPreference()");
    let orderData = this.extractOrderData();
    console.log(orderData);
    fetch("http://localhost:8888/create_preference", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(orderData),
    })
      .then((response) => {
        return response.json();
      })
      .then((preference) => {
        this.createCheckoutButton(preference.id);
      })
      .catch(() => {
        alert("Error al generar el link de pago");
      });
  }

  createCheckoutButton(preferenceId) {
    const mp = new MercadoPago("APP_USR-4dc29a05-5685-475c-8d2a-dca4454e7b23"); // Public key
    const bricksBuilder = mp.bricks();
    console.log(`Ejecutando createCheckoutButton(${preferenceId})`);
    bricksBuilder.create("wallet", "wallet_container", {
      initialization: {
        preferenceId: preferenceId,
      },
      customization: {
        texts: {
          valueProp: "smart_option",
        },
      },
    });
    // const submitButton = document.querySelector(".submit.form-submit");
    // submitButton.style.display = "none";
  }

  // Función para extraer los datos de la tabla y crear la lista de orderData
  extractOrderData() {
    const orderDataList = [];
    const rows = document.querySelectorAll("tbody .component");

    rows.forEach((row) => {
      const description = row.querySelector("td:nth-child(1)").innerText;
      const price = parseFloat(row.querySelector(".price").dataset.price);
      const quantity = parseInt(
        row.querySelector(".quantity").dataset.quantity
      );

      const orderData = {
        description: description,
        price: price,
        quantity: quantity,
      };

      orderDataList.push(orderData);
    });

    return orderDataList;
  }
}