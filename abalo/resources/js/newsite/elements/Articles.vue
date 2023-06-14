<template>

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
        <th scope="col">User</th>
        <th scope="col">Image</th>
        <th scope="col"></th>
      </tr>
      </thead>
      <tbody id="articles_body">
      <tr v-for="(item, index) in item_payload.items" :key="`item_${index}`">

        <td style="vertical-align: middle;" class="text-font-caption-less">
          {{ item.ab_name }}
        </td>

        <td style="vertical-align: middle" class="text-font-caption-less">
          {{ `${item.ab_price / 1000}€` }}
        </td>

        <td style="vertical-align: middle;" class="text-font-caption-less">
          {{ item.ab_description }}
        </td>

        <td style="vertical-align: middle;" class="text-font-caption-less">
          {{ item.user ? item.user.ab_mail : "" }}
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
            <span class="font-weight-bold text-font-caption-less">{{ !item.cart_item ? "+" : "-" }}</span>
          </button>

          <button style="min-width: 30px;"
                  class="mx-2 text-font-caption-less font-weight-semibold btn btn-sm btn-block btn-dark" @click="onSellClicked(item)"
                  >
            <span class="font-weight-bold text-font-caption-less">Kaufen</span>
          </button>

          <button style="min-width: 30px;" v-if="item.user && item.user.ab_mail === getEmail()"
                  class="text-font-caption-less font-weight-semibold btn btn-sm btn-block btn-dark" @click="onDiscountClicked(item)"
                  >
            <span class="font-weight-bold text-font-caption-less">Als Angebot stellen</span>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <template v-if="views_payload.init_connection">
    <div class="col-12 justify-center text-center">
      <span @click="onPagination(false)" v-show="views_payload.page >= 1" class="clickable"><i style="font-size: 14px;"
                                                                                               class="bi bi-arrow-left ml-3"></i></span>
      <span class="mx-3">{{ (views_payload.page + 1) }}</span>
      <span @click="onPagination(true)" class="clickable" v-show="(views_payload.page + 1) < views_payload.pages"><i
          style="font-size: 14px;" class="bi bi-arrow-right ml-3"></i></span>
    </div>

    <div class="col-12 justify-center text-center">
      <span class="font-weight-semibold text-font-caption-less">({{ views_payload.pages }}) pages</span>
    </div>
  </template>
</template>
<script>
export default {
  components: {},
  data() {
    return {
      item_payload: {
        items: [],
        total_count: 0,
        searchQuery: null
      },
      views_payload: {
        page: 0,
        pages: 0,
        init_connection: false,

        hasSearched: false,
        hasResetted: false,
        searchAjax: null,
        searchTimeout: null
      },
    }
  },
  mounted() {
    let app = this

    app.$eventBus.on("article-offer", (payload) => app.onOfferReceived(payload))
    app.$eventBus.on("cart-response", (payload) => app.onCartResponse(payload))
    app.getArticles(true)
  },
  beforeUnmount() {
    let app = this

    app.$eventBus.off("article-offer")
    app.$eventBus.off("cart-response")
  },
  watch: {
    "item_payload.searchQuery"(newVal) {
      let app = this
      if (newVal && newVal.length > 2) {
        app.onSearchTimeout()
      } else {
        if (app.views_payload.hasSearched && !app.views_payload.hasResetted) {
          app.views_payload.hasResetted = true
          app.getArticles(false)
        }
      }
    },
  },
  methods: {
    onOfferReceived(payload) {
      let app = this
      const id = payload.id
      const creator_email = payload.creator_email
      console.log(app.item_payload.items)
      console.log(payload)
      console.log(localStorage.getItem("email"))
      if(localStorage.getItem("email") !== creator_email) {
        // not same user
        const index = app.item_payload.items.findIndex(e => e.id == id)
        if(index >= 0) {
          // index has been found, show an alert
          alert(payload.message)
        }
      }
    },
    onCartResponse(payload) {
      let app = this
      const id = payload.item.id
      const state = payload.state
      const index = app.item_payload.items.findIndex(e => e.id === id)
      if (index >= 0 && app.item_payload.items[index]) {
        let item = app.item_payload.items[index]
        item.cart_item = state
        app.item_payload.items.splice(index, 1, item)
      }

    },
    onCartClicked(item) {
      let app = this

      app.$eventBus.emit("cart", item)
    },
    onPagination(isNext) {
      let app = this
      if (!isNext) {
        app.views_payload.page--
      } else {
        app.views_payload.page++
      }

      app.getArticles(false)
    },
    getEmail() {
      return localStorage.getItem('email')
    },
    onSearchTimeout() {
      let app = this

      if (app.views_payload.searchTimeout)
        clearTimeout(app.views_payload.searchTimeout)

      // Avoid too many attempts
      app.views_payload.searchTimeout = setTimeout(() => {
        app.views_payload.hasResetted = false
        app.views_payload.hasSearched = true
        app.searchForArticles()
      }, 500)
    },
    searchForArticles() {
      let app = this
      let search = app.item_payload.searchQuery
      if (search) {
        app.getArticles(true)
      } else {
        alert("Couldn't find any search input!");
      }
    },
    onSellClicked(item) {
      let app = this

      const xhttp = new XMLHttpRequest();
      xhttp.open("POST", `api/articles/${item.id}/sold`);
      app.views_payload.message = null
      xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
          if (xhttp.status === 200) {
            const data = JSON.parse(xhttp.response)
            alert(data.message)
          } else {
            try {
              const data = JSON.parse(xhttp.response)
              alert(data.message)
            } catch (e) {
              alert(xhttp.statusText)
            }
          }
        }
      }

      xhttp.onerror = function () {
        alert(xhttp.statusText)
      };

      xhttp.send();
    },
    onDiscountClicked(item) {
      let app = this

      app.$api.post(`api/articles/${item.id}/offer`).then(response => {
        const res = response.data
        alert(res.message)
      }).catch((error) => {
        alert(error.message)
      })
    },
    getArticles(is_refresh = false) {
      let app = this

      // if a search is in progress, cancel it
      if (app.views_payload.searchAjax)
        app.views_payload.searchAjax.abort()

      let query_string = ""

      if (is_refresh) {
        app.views_payload.page = 0
        app.views_payload.init_connection = false
      }

      let payload = {
        page: app.views_payload.page
      }

      if (app.item_payload.searchQuery) {
        payload.search = app.item_payload.searchQuery
      }

      query_string = "?" + new URLSearchParams(payload).toString()

      const xhttp = new XMLHttpRequest();
      xhttp.open("GET", "/api/articles" + query_string);
      xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
          if (xhttp.status === 200) {
            if (is_refresh) {
              app.item_payload.items = []
            }
            const response = JSON.parse(this.responseText)
            app.item_payload.items = response.articles
            app.item_payload.total_count = response.total_count

            app.views_payload.pages = Math.ceil(app.item_payload.total_count / 5)
          } else {
            console.error(xhttp.statusText);
          }
        }

        app.views_payload.init_connection = true
      }

      xhttp.onerror = function () {
        app.views_payload.init_connection = true
        console.error(xhttp.statusText);
      };


      app.views_payload.searchAjax = xhttp
      xhttp.send();
    }
  }
}
</script>