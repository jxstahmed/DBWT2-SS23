<template>
  <footer>
    <hr>
    <ul>
      <li>Logged in as {{views_payload.email}}</li>
      <li>(c) Abalo GmbH</li>
      <li>Ahmed Jumaa &amp; Mohammad Hammado</li>
      <li class="clickable navigation-item" @click="showImpressum()" style="width: fit-content">Impressum</li>
    </ul>
  </footer>
</template>
<script>
export default {
  data() {
    return {
      views_payload: {
        email: "admin@abalo.example.com"
      }
    }
  },
  computed: {

  },
  mounted() {
    let app = this

    app.email()
    setInterval(() => {
      app.email()
    }, 2500)
  },
  methods: {
    email() {
      let app = this
      const m = localStorage.getItem("email")
      app.views_payload.email = m ?? "admin@abalo.example.com"
    },
    showImpressum() {
      let app = this
      app.$eventBus.emit("navigation", {type: "navigation", name: "impressum"})
    }
  }
}
</script>