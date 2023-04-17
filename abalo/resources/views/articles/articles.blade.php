@extends("layout")

@yield("header")

@section("content")

    <div class="row p-2 py-4 abalo_cart abalo_shadow align-content-start" id="cart_view"
        >

        <div class="col-12">
            <p class="text-font-subtitle-less text-center font-weight-bold">Cart</p>
        </div>

        <div class="col-12 text-center mt-2" style="max-height: 240px; overflow-y: scroll; overflow-x: hidden;">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col"> </th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                @foreach ($articles as $article)
                    <tr>
                        <td class="text-font-caption-less">{{ $article->ab_name }}</td>
                        <td class="text-font-caption-less">{{ $article->ab_price / 1000}}€</td>
                        <td>
                            <button style="min-width: 60px"
                                    class="btn-dark text-font-caption-less font-weight-semibold btn btn-sm" id="cart_view_button_{{$article->id}}"
                                    onclick="dequeueCart({id: '{{ $article->id }}'}, true)"
                            >

                            <span id="cart_view_span_{{$article->id}}" class="font-weight-bold text-font-caption-less">
                                Remove
                            </span>
                                <span><i style="font-size: 12px; vertical-align: middle;" class="bi bi-cart ml-2"></i></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tbody>
            </table>
        </div>
    </div>


    <div class="col-12 text-end align-self-center">
        <button style="min-width: 60px"
                class="text-font-caption font-weight-bold btn btn-sm btn-outline-dark">
            <span class="font-weight-bold text-font-caption-more" id="cart">
                <script>
                "use strict"
                    document.getElementById("cart").innerText = getCart().length;
                </script>
            </span>
            <span><i style="font-size: 14px; vertical-align: top;" class="bi bi-cart ml-2"></i></span>
        </button>
    </div>

    <div class="col-12 mt-3">
        <p class="font-weight-bold text-font-subtitle">Search for products</p>
    </div>

    <div class="col-12 mt-3">
        <form>
            <div class="row">
                <div class="col flex-grow-1 align-self-center">
                    <input required minlength="2" type="search" class="form-control" placeholder="Welcher Artikel?"
                           name="search"
                           value="{{ request('search') }}">
                </div>

                <div class="col flex-grow-0 min-width-fit-content align-self-center">
                    <button type="submit" class="text-font-caption btn-block font-weight-bold btn btn-dark ">Suchen
                    </button>
                </div>
            </div>


        </form>
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
                <th scope="col"> </th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            @foreach ($articles as $article)
                <tr>
                    <td class="text-font-caption-less">{{ $article->ab_name }}</td>
                    <td class="text-font-caption-less">{{ $article->ab_price / 1000}}€</td>
                    <td class="text-font-caption-less">{{ $article->ab_description }}</td>
                    <td>
                    @php($bild = glob("articlesimages/{$article->id}.*"))
                    @if(empty($bild[0]) === false)
                        <img src="{{$bild[0]}}" width="50px">
                    @endif
                    </td>
                    <td>
                        <button style="min-width: 60px"
                                class="text-font-caption-less font-weight-semibold btn btn-sm" id="cart_button_{{$article->id}}"
                                onclick="queueCart({id: '{{ $article->id }}', name: '{{ $article->name }}', price: '{{ $article->price }}' })"

                        >

                            <span id="cart_span_{{$article->id}}" class="font-weight-bold text-font-caption-less">
                                <script>
                                    "use strict"
                                    if(document.getElementById("cart_button_{{$article->id}}") && document.getElementById("cart_span_{{$article->id}}")) {
                                        if(getCartProduct({id: {{$article->id}}})) {
                                            document.getElementById("cart_span_{{$article->id}}").innerText = "Remove";

                                            document.getElementById("cart_button_{{$article->id}}").classList.remove("btn-outline-dark");

                                            document.getElementById("cart_button_{{$article->id}}").classList.add("btn-dark");
                                        } else {
                                            document.getElementById("cart_span_{{$article->id}}").innerText = "Add";

                                            document.getElementById("cart_button_{{$article->id}}").classList.remove("btn-dark");

                                            document.getElementById("cart_button_{{$article->id}}").classList.add("btn-outline-dark");
                                        }
                                    }
                                </script>
                            </span>
                            <span><i style="font-size: 12px; vertical-align: middle;" class="bi bi-cart ml-2"></i></span>
                        </button>
                    </td>
                </tr>
            @endforeach
            <tbody>
        </table>
    </div>
@endsection

@section("js")
    <script>
        "use strict"
    </script>
@endsection
