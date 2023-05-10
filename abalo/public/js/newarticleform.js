

document.addEventListener("DOMContentLoaded", function () {
    createForm();
});

function createForm() {
    let parent = document.getElementById("form_col");
    if(!parent) return

    // Name
    // Price > 0
    // Description


    let form = document.createElement("form");


    let token_value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let token = document.createElement("input");
    token.setAttribute("type", "hidden");
    token.setAttribute("name", "_token");
    token.setAttribute("id", "_token");
    token.setAttribute("value", token_value);

    let row = document.createElement("div");
    row.className = "row"

    let col_name = document.createElement("div");
    col_name.className = "col-12";

    let name = document.createElement("input");
    name.setAttribute('type',"text");
    name.setAttribute('name',"article_name");
    name.setAttribute('id', 'article_name');
    name.setAttribute('placeholder', "Name")

    col_name.appendChild(name);


    let col_price = document.createElement("div");
    col_price.className = "col-12 mt-2";

    let price = document.createElement("input");
    price.setAttribute('type',"number");
    price.setAttribute('min',0);
    price.setAttribute('name',"article_price");
    price.setAttribute('id', 'article_price');
    price.setAttribute('placeholder', "Preis")

    col_price.appendChild(price);

    let col_description = document.createElement("div");
    col_description.className = "col-12 mt-2";

    let description = document.createElement("textarea");
    description.setAttribute('type',"text");
    description.setAttribute('name',"article_description");
    description.setAttribute('id', 'article_description');
    description.setAttribute('placeholder', "Beschreibung")
    description.setAttribute("rows", "4");
    description.style.resize = "none";

    col_description.appendChild(description);

    let button = document.createElement("input");
    button.className = "mt-2 text-font-caption btn-block font-weight-bold btn btn-dark"
    button.setAttribute('type',"button");
    button.setAttribute('value',"Speichern");
    button.addEventListener("click", ev => {
        ev.preventDefault();
        sendViaAjax();
        return false;
    }, false);

    let message = document.createElement("p");
    message.className = "mt-2 text-font-caption font-weight-bold"
    message.setAttribute('id', 'message');


    form.appendChild(token);
    form.appendChild(col_name);
    form.appendChild(col_price);
    form.appendChild(col_description);
    form.appendChild(button);
    form.appendChild(message);

    parent.appendChild(form);
}

function sendViaAjax() {
    let formData = new FormData();

    let article_name = document.getElementById("article_name").value
    let article_price = document.getElementById("article_price").value
    let article_description = document.getElementById("article_description").value
    let _token = document.getElementById("_token").value

    formData.append("article_name", article_name);
    formData.append("article_price", article_price);
    formData.append("article_description", article_description);

    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/api/articles");
    xhttp.setRequestHeader("X-CSRF-TOKEN", _token);
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                const response = JSON.parse(xhttp.responseText)
                document.getElementById("message").innerText = `ID: ${response.id}, ${response.message}`
            } else {
                try {
                    document.getElementById("message").innerText = JSON.parse(xhttp.responseText).message
                } catch (e) {
                    document.getElementById("message").innerText = xhttp.statusText
                }

                console.error(xhttp.statusText);
            }
        }
    }

    xhttp.onerror = function(){
        console.error(xhttp.statusText);
    };


    xhttp.send(formData);
}