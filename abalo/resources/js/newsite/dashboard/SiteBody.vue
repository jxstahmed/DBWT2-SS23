<template>
  <Home v-if="!currentComponent || currentComponent === 'home'"></Home>

  <CartDialog v-if="currentComponent === 'articles'"></CartDialog>

  <Articles v-if="currentComponent === 'articles'"></Articles>

  <NewArticle v-if="currentComponent === 'newarticle'"></NewArticle>

  <Impressum v-if="currentComponent === 'impressum'"></Impressum>

  <CookiesConsent></CookiesConsent>
</template>
<script>
import Home from "../elements/Home.vue";
import Impressum from "../elements/Impressum.vue";
import Articles from "../elements/Articles.vue";
import CartDialog from "../elements/CartDialog.vue";
import NewArticle from "../elements/NewArticle.vue";
import CookiesConsent from "../elements/CookiesConsent.vue";

export default {
  props: ["current-component"],
  components: {
    Home,
    CookiesConsent,
    Articles,
    CartDialog,
    Impressum,
    NewArticle
  },
  data() {
    return {
      item_payload: {},
      views_payload: {
      }
    }
  },
  watch: {},
  methods: {
  }
}
</script>