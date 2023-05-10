@extends("layout")

@section("content")

    <div class="row p-2 py-4 abalo_cart abalo_shadow align-content-start" id="cart_view" style="display: none">

        <div class="col-12">
            <p class="text-font-subtitle-less text-center font-weight-bold">Cart</p>
        </div>

        <div class="col-12 text-center mt-2">
            <table class="table mb-0 pb-0">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col"></th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="col-12 text-center" style="max-height: 200px; overflow-y: scroll; overflow-x: hidden;">
            <table class="table table-striped">
                <tbody id="cart_view_tbody">
                </tbody>
            </table>
        </div>
    </div>


    <div class="col-12 text-end align-self-center">
        <button style="min-width: 60px" onclick="toggleCartViewItems()"
                class="text-font-caption font-weight-bold btn btn-sm btn-outline-dark">
            <span class="font-weight-bold text-font-caption-more" id="cart">
                0
            </span>
            <span><i style="font-size: 14px; vertical-align: top;" class="bi bi-cart ml-2"></i></span>
        </button>
    </div>

    <div class="col-12 mt-3">
        <p class="font-weight-bold text-font-subtitle">Search for products</p>
    </div>

    <div class="col-12 mt-3">
        <div class="row">
            <div class="col flex-grow-1 align-self-center">
                <input required minlength="2" type="search" class="form-control" placeholder="Welcher Artikel?"
                       name="search" id="search">
            </div>

            <div class="col flex-grow-0 min-width-fit-content align-self-center">
                <button onclick="searchForArticles()"
                        class="text-font-caption btn-block font-weight-bold btn btn-dark ">Suchen
                </button>
            </div>
        </div>
    </div>

    <div class="col-12 mt-5">
        <p class="font-weight-bold text-font-subtitle">Products</p>
    </div>

    <div class="col-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Description</th>
                <th scope="col">Image</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody id="articles_body">

{{--            <td>--}}
{{--                @php($bild = glob("articlesimages/{$article->id}.*"))--}}
{{--                @if(empty($bild[0]) === false)--}}
{{--                    <img src="{{$bild[0]}}" width="50px">--}}
{{--                @endif--}}
{{--            </td>--}}
            <tbody>
        </table>
    </div>
@endsection

@section("js")
    <script>
        "use strict"

        function removeArticles() {
            document.getElementById("articles_body").replaceChildren();
        }

        function populateArticles(articles = []) {
            articles.forEach(article => {
                let element_tr = document.createElement("tr");

                let element_td_name = document.createElement("td");
                element_td_name.style = "vertical-align: middle;";
                element_td_name.className = "text-font-caption-less";
                element_td_name.innerText = article.ab_name;

                let element_td_price = document.createElement("td");
                element_td_price.style = "vertical-align: middle;";
                element_td_price.className = "text-font-caption-less";
                element_td_price.innerText = `${article.ab_price / 1000}€`;


                let element_td_description = document.createElement("td");
                element_td_description.style = "vertical-align: middle;"
                element_td_description.className = "text-font-caption-less";
                element_td_description.innerText = article.ab_description;


                let element_td_image = document.createElement("td");
                element_td_image.innerHTML = article.image ? `<img src="${article.image}" width="50px">` : ""

                // articlesimages/{$article.id}.*

                let element_td_cart = document.createElement("td");
                let element_td_btn = document.createElement("button");
                element_td_btn.style = "min-width: 30px";
                element_td_btn.className = "text-font-caption-less font-weight-semibold btn btn-sm ";

                element_td_btn.setAttribute("id", `cart_button_${article.id}`)
                element_td_btn.addEventListener("click", ev => {
                    ev.preventDefault();
                    queueCart({id: article.id, ab_name: article.ab_name, ab_price: article.ab_price })
                    return false;
                }, false);

                let element_td_btn_span = document.createElement("span")
                element_td_btn_span.setAttribute("id", `cart_span_${article.id}`);
                element_td_btn_span.className = "font-weight-bold text-font-caption-less";


                if(getCartProduct({id: article.id})) {
                    element_td_btn.className += "btn-dark"
                    element_td_btn_span.innerText = "-";
                } else {
                    element_td_btn.className += "btn-outline-dark"
                    element_td_btn_span.innerText = "+";
                }

                element_td_btn.appendChild(element_td_btn_span);
                element_td_cart.appendChild(element_td_btn)
                // btn-dark btn-dark-outline



                element_tr.appendChild(element_td_name);
                element_tr.appendChild(element_td_price);
                element_tr.appendChild(element_td_description);
                element_tr.appendChild(element_td_image);
                element_tr.appendChild(element_td_cart);

                document.getElementById("articles_body").appendChild(element_tr);
            })
        }

        document.addEventListener('DOMContentLoaded', function () {
            getArticles();
        }, false);

        function searchForArticles() {
            let search = document.getElementById("search").value
            if (search) {
                getArticles({search: search})
            } else {
                alert("Couldn't find any search input!");
            }
        }

        function getArticles(payload = null) {
            let query_string = ""

            if (payload) {
                query_string = "?" + new URLSearchParams(payload).toString()
            }

            const xhttp = new XMLHttpRequest();
            xhttp.open("GET", "/api/articles" + query_string);
            xhttp.onreadystatechange = function () {
                if (xhttp.readyState === 4) {
                    if (xhttp.status === 200) {
                        const response = JSON.parse(this.responseText)
                        const articles = response.articles
                        removeArticles();
                        populateArticles(articles);
                    } else {
                        console.error(xhttp.statusText);
                    }
                }
            }

            xhttp.onerror = function () {
                console.error(xhttp.statusText);
            };


            xhttp.send();
        }


    </script>
@endsection
