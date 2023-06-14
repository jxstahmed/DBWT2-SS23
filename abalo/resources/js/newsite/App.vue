<template>
  <div class="container py-4">
    <div class="row">
      <div class="col-12 col-md-4 col-lg-3 col-xl-2">
        <abalo-header></abalo-header>
      </div>

      <div class="col-12 col-md-8 col-lg-9 col-xl-10">
        <div class="row">
          <abalo-body :current-component="item_payload.currentComponent"></abalo-body>
        </div>
      </div>
    </div>
  </div>

  <abalo-footer></abalo-footer>

</template>
<script>
export default {
  components: {

  },
  data() {
    return {
      item_payload: {
        currentComponent: null
      }
    }
  },
  mounted() {
    let app = this

    app.$eventBus.on("navigation", (payload) => app.onBusReceived(payload))
  },
  beforeUnmount() {
    let app = this

    app.$eventBus.off("navigation")
  },
  methods: {
    onBusReceived(payload) {
      if(!payload) return

      let app = this

      const type = payload.type
      const name = payload.name

      if(type === "navigation" && name) {
        app.item_payload.currentComponent = name
      }
    }
  }
}
</script>