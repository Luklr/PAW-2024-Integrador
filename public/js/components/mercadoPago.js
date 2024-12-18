// Source: https://github.com/mercadopago/checkout-payment-sample/blob/master/client/html-js/js/index.js
class MercadoPago {
  constructor() {
    this.init();
  }

    init() {
    document.getElementById("checkout-btn").addEventListener("click", getPreferenceId);
    var mpContainer = tools.nuevoElemento("div", "", {
      id: "wallet_container",
    });
    const form = document.querySelector("form[name=confirmOrderForm]");
    form.appendChild(mpContainer);

    const mp = new MercadoPago("YOUR_PUBLIC_KEY");
    // const mercadopago = new MercadoPago("YOUR_PUBLIC_KEY", {
    //   locale: "es-AR", // The most common are: 'pt-BR', 'es-AR' and 'en-US'
    // });
    const bricksBuilder = mp.bricks();

    bricksBuilder.create("wallet", "wallet_container", {
      initialization: {
        preferenceId: "<PREFERENCE_ID>",
      },
      customization: {
        texts: {
          valueProp: "smart_option",
        },
      },
    });
  }

  getPreferenceId() {
    // $("#checkout-btn").attr("disabled", true);

    // const orderData = {
    //   quantity: document.getElementById("quantity").value,
    //   description: document.getElementById("product-description").innerHTML,
    //   price: document.getElementById("unit-price").innerHTML,
    // };

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

        $(".shopping-cart").fadeOut(500);
        setTimeout(() => {
          $(".container_payment").show(500).fadeIn();
        }, 500);
      })
      .catch(function () {
        alert("Unexpected error");
        $("#checkout-btn").attr("disabled", false);
      });
  }
}
