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

    <div id="app">

        <div class="col-12 mt-3">
            <p class="font-weight-bold text-font-subtitle">Search for products</p>
        </div>

        <div class="col-12 mt-3">
            <div class="row">
                <div class="col flex-grow-1 align-self-center">
                    <input required minlength="2" type="search" class="form-control" placeholder="Welcher Artikel?"
                           name="search" id="search" v-model="item_payload.searchQuery">
                </div>

                <div class="col flex-grow-0 min-width-fit-content align-self-center">
                    <button
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
                <tr v-for="(item, index) in item_payload.items" :key="`item_${index}`">

                    <td style="vertical-align: middle;" class="text-font-caption-less">
                        @{{ item.ab_name }}
                    </td>

                    <td style="vertical-align: middle" class="text-font-caption-less">
                        @{{ `${item.ab_price / 1000}€` }}
                    </td>

                    <td style="vertical-align: middle;" class="text-font-caption-less">
                        @{{ item.ab_description }}
                    </td>

                    <td>
                        <template v-if="item.image">
                            <img :alt="item.ab_name" :src="item.image" width="75"/>
                        </template>
                    </td>

                    <td>
                        <button :id="`cart_button_${item.id}`" style="min-width: 30px;"
                                class="text-font-caption-less font-weight-semibold btn btn-sm" @click="onCartClicked(item)"
                                :class="{'btn-block btn-dark': !item.cart_item, 'btn-outline-dark': item.cart_item}">
                            <span class="font-weight-bold text-font-caption-less">@{{ !item.cart_item ? "+" : "-" }}</span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section("js")
    <script src="./js/articlesVue.js"></script>
@endsection
