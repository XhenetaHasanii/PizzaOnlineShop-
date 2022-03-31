function addToCart(img) {
    "use strict";
    var name;
    var price;
    var select;
    var totalPrice = parseFloat(document.getElementById("totalPrice").textContent);

    name = img.title;
    price = parseFloat(img.dataset.price);
    totalPrice += price;
    var temp = totalPrice.toFixed(2);

    document.getElementById("totalPrice").textContent = temp.concat("€");

    select = document.getElementById("selectedPizzas"); 

    var tempoption = document.createElement('option'); 

    tempoption.appendChild(document.createTextNode(name)); 

    tempoption.dataset.price = price; 
    select.options[select.length] = tempoption; 
    checkSubmitButton();
    checkSubmitButton2();

}

function deleteAllPizzas() {
    "use strict";
    var select = document.getElementById("selectedPizzas");

    select.options.length = 0;
    document.getElementById("totalPrice").textContent="0.00 €";
    document.getElementById("sendOrder").disabled = true;
}

function deleteSelectedPizzas() {
    "use strict";
    var select = document.getElementById("selectedPizzas");

    var totalPrice = parseFloat(document.getElementById("totalPrice").textContent);
    var tempPrice;

    for (var i = select.length - 1; i >= 0; i--) {
        if (select.options[i].selected == true) {
            tempPrice = parseFloat(select.options[i].dataset.price);
            totalPrice -= tempPrice;
            select.remove(i);
        }
    }
    var temp = totalPrice.toFixed(2);
    document.getElementById("totalPrice").textContent = temp.concat("€");

    checkSubmitButton2();
}

function checkSubmitButton() {
    "use strict";
    var select = document.getElementById("selectedPizzas");

    if ((document.getElementById("address").value.length) && (select.length > 0)) {
        document.getElementById("sendOrder").disabled = false;
    }
}

function checkSubmitButton2() {
    "use strict";
    var select = document.getElementById("selectedPizzas");

    if (document.getElementById("address").value.length == 0 || (select.length == 0)) {
        document.getElementById("sendOrder").disabled = true;
    }
}

function selectPizzas() {
    "use strict";
    var select = document.getElementById("selectedPizzas");

    for (var i = 0; i < select.length; ++i) {
        select.options[i].selected = true;
    }
}

function sendBakerForm() {
    "use strict";
    document.getElementById("bakerForm").submit(); // form verschicken
}

function sendDriverForm() {
    "use strict";
    document.getElementById("driverForm").submit();
}
