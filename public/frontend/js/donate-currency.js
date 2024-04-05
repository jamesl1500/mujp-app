var seventy = document.getElementById("70");
var eighty = document.getElementById("80");
var ninety = document.getElementById("90");
var hundred = document.getElementById("100");

function getCurrency(){
    var active_currency = document.getElementById("select_currency").value;
    document.getElementById("currency_70").textContent = active_currency;
    document.getElementById("currency_80").textContent = active_currency;
    document.getElementById("currency_90").textContent = active_currency;
    document.getElementById("currency_100").textContent = active_currency;
    document.getElementById("total_currency").textContent = active_currency;
}

function getMoney(){
    var x = document.getElementById("donate_money").value;
    document.getElementById("donate_amount").value = x;

    seventy.classList.remove("active");
    eighty.classList.remove("active");
    ninety.classList.remove("active");
    hundred.classList.remove("active");
}

function get70(){
    document.getElementById("donate_amount").value="70";
    document.getElementById("donate_money").value="70";

    seventy.classList.add("active");
    eighty.classList.remove("active");
    ninety.classList.remove("active");
    hundred.classList.remove("active");
}

function get80(){
    document.getElementById("donate_amount").value="80";
    document.getElementById("donate_money").value="80";

    seventy.classList.remove("active");
    eighty.classList.add("active");
    ninety.classList.remove("active");
    hundred.classList.remove("active");
}

function get90(){
    document.getElementById("donate_amount").value="90";
    document.getElementById("donate_money").value="90";

    seventy.classList.remove("active");
    eighty.classList.remove("active");
    ninety.classList.add("active");
    hundred.classList.remove("active");
}

function get100(){
    document.getElementById("donate_amount").value="100";
    document.getElementById("donate_money").value="100";

    seventy.classList.remove("active");
    eighty.classList.remove("active");
    ninety.classList.remove("active");
    hundred.classList.add("active");
}