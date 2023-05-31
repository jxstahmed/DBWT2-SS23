<template>
  <div class="col-12 text-end align-self-center">
    <button style="min-width: 60px" @click="toggleCartDialog()"
            class="text-font-caption font-weight-bold btn btn-sm btn-outline-dark">
            <span class="font-weight-bold text-font-caption-more" id="cart">
                {{ item_payload.items.length }}
            </span>
      <span><i style="font-size: 14px; vertical-align: top;" class="bi bi-cart ml-3"></i></span>
    </button>
  </div>


  <div class="row p-2 py-4 abalo-cart abalo-shadow align-content-start" id="cart_view" style="display: none" v-show="views_payload.active">

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
        <tr v-for="(item, index) in item_payload.items" :key="`item_${index}`">

          <td style="vertical-align: middle;" class="text-font-caption-less">
            @{{ item.ab_name }}
          </td>

          <td style="vertical-align: middle" class="text-font-caption-less">
            @{{ `${item.ab_price / 1000}€` }}
          </td>

          <td>
            <button :id="`cart_button_${item.id}`" style="min-width: 30px;" class="text-font-caption-less font-weight-semibold btn btn-sm btn-outline-dark" @click="onCartClicked(item)">
              <span class="font-weight-bold text-font-caption-less">-</span>
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
export default {
  props: ["cart-payload"],
  data() {
    return {
      item_payload: {
        items: []
      },
      views_payload: {
        active: false
      }
    }
  },
  emits: ["cart", "update:cart-payload"],
  watch: {
    cartPayload(newVal) {
      if(newVal) {
        let app = this
        if(newVal.cart_item) {
          // we should remove
          app.dequeueCartItem(newVal)
        } else {
          // we should add
          app.enqueueCartItem(newVal)
        }
      }
    }
  },
  mounted() {
    let app = this

    app.loadCart()
  },
  methods: {
    toggleCartDialog() {
      let app = this

      app.views_payload.active = !app.views_payload.active
    },
    loadCart() {
      let app = this
      const xhttp = new XMLHttpRequest();
      xhttp.open("GET", "/api/articles/shoppingcart");
      xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
          if(xhttp.status === 200) {
            const response = JSON.parse(xhttp.responseText)
            app.item_payload.items = response.items.map(e => {
              return {
                ab_name: e.article.ab_name,
                ab_price: e.article.ab_price,
                ab_description: e.article.ab_description,
                image: e.article.image,
                ab_shoppingcart_id: e.ab_shoppingcart_id,
                id: e.ab_article_id,
              }
            })
          } else {
            console.error(xhttp.statusText);
          }
        }
      }

      xhttp.onerror = function(){
        console.error(xhttp.statusText);
      };

      xhttp.send();
    },
    onCartClicked(item) {
      if(!item) return

      let app = this


      item.callback = (isEnqueued) => {
        if(!isEnqueued) {
          // we should emit an update to the articles
          console.log("Sending to articles")
          app.$emit("cart", {action: "cart", id: item.id, state: isEnqueued})
        }
      }
      app.dequeueCartItem(item)
    },
    enqueueCartItem(payload) {
      if(!payload) return

      console.log(payload)

      let app = this
      let formData = new FormData();
      formData.append("articleid", payload.id)

      const xhttp = new XMLHttpRequest();
      xhttp.open("POST", "/api/articles/shoppingcart");
      xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
          if(xhttp.status === 200) {
            payload.callback(true)
            app.item_payload.items.push({
              id: payload.id,
              ab_name: payload.ab_name,
              ab_price: payload.ab_price,
            })
          } else {
            payload.callback(false)
            console.error(xhttp.statusText);
          }
        }
      }

      xhttp.onerror = function(){
        payload.callback(false)
        console.error(xhttp.statusText);
      };

      xhttp.send(formData);
    },
    dequeueCartItem(payload) {
      if(!payload) return

      let app = this

      const xhttp = new XMLHttpRequest();
      xhttp.open("POST", `/api/articles/shoppingcart/${payload.ab_shoppingcart_id ?? 0}/articles/${payload.id}`);
      xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
          if(xhttp.status === 200) {
            payload.callback(false)
            app.item_payload.items.splice(app.item_payload.items.findIndex(e => e.id === payload.id), 1)
          } else {
            payload.callback(true)
            console.error(xhttp.statusText);
          }
        }
      }

      xhttp.onerror = function(){
        payload.callback(true)
        console.error(xhttp.statusText);
      };

      xhttp.send();
    },
  },
}
</script>