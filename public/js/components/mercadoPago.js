// Source: https://github.com/mercadopago/checkout-payment-sample/blob/master/client/html-js/js/index.js
class MercadoPago {
  constructor() {
    this.createPreference = this.createPreference.bind(this);
    this.init();
  }

  init() {
    var mpContainer = tools.nuevoElemento("div", "", {
      id: "wallet_container",
    });
    const form = document.querySelector("form[name=confirmOrderForm]");
    form.appendChild(mpContainer);
    console.log(form);
    form.addEventListener("click", this.createPreference);
  }

  createPreference() {
    console.log("Ejecutando createPreference()");

    let submit = document.querySelector(".submit.form-submit");
    submit.disabled = true;

    let orderData = this.extractOrderData();
    console.log(orderData);
    fetch("http://localhost:8888/create_preference", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(orderData),
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (preference) {
        createCheckoutButton(preference.id);
      })
      .catch(function () {
        alert("Error al generar el link de pago");
        submit.disabled = false;
      });
  }

  createCheckoutButton(preferenceId) {
    const mp = new MercadoPago("TEST-82a70b33-6a9a-45b2-9d73-a1434246b4ad");
    // const mercadopago = new MercadoPago("YOUR_PUBLIC_KEY", {
    //   locale: "es-AR", // The most common are: 'pt-BR', 'es-AR' and 'en-US'
    // });
    const bricksBuilder = mp.bricks();

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
