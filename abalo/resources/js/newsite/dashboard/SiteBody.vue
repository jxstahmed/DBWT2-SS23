<template>
  <span v-if="!currentComponent">
    Welcome to Abalo
  </span>

  <CartDialog
      @cart="onCartInlineReceived($event)"
      :cart-payload="views_payload.articles_cart"
      @update:cart-payload="views_payload.cart_articles = $event"
      v-if="currentComponent === 'articles'"></CartDialog>

  <Articles
      @cart="onCartReceived($event)"
      :cart-payload="views_payload.cart_articles"
      @update:cart-payload="views_payload.articles_cart = $event"
      v-if="currentComponent === 'articles'"></Articles>

  <Impressum v-if="currentComponent === 'impressum'"></Impressum>

  <CookiesConsent></CookiesConsent>
</template>
<script>
import Impressum from "../elements/Impressum";
import Articles from "../elements/Articles";
import CartDialog from "../elements/CartDialog";
import CookiesConsent from "../elements/CookiesConsent";

export default {
  props: ["current-component"],
  components: {
    CookiesConsent,
    Articles,
    CartDialog,
    Impressum
  },
  data() {
    return {
      item_payload: {},
      views_payload: {
        articles_cart: null,
        cart_articles: null
      }
    }
  },
  watch: {},
  methods: {
    onCartInlineReceived(payload) {
      if (!payload) return

      let app = this
      console.log("Weiterleiten")
      app.views_payload.cart_articles = payload
    },
    onCartReceived(payload) {
      if (!payload) return

      let app = this
      app.views_payload.articles_cart = payload
    }
  }
}
</script>