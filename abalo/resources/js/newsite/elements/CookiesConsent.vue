<template>
  <div class="row p-2 py-4 abalo-cookies-consent abalo-shadow align-content-start" id="cookies_consent" v-if="show">

    <div class="col-12">
      <p class="text-font-subtitle-less text-center font-weight-bold">We use cookies</p>
      <p class="text-font-caption text-center font-weight-semibold">Consent to get nothing, and decline to also get
        nothing. We just track everything! [cringe]</p>
    </div>

    <div class="col-12 text-center mt-2">
      <button @click="cookiesResponse(true)" type="button"
              class="fill-width text-font-caption btn-block font-weight-bold btn btn-dark">Accept
      </button>
    </div>

    <div class="col-12 text-center mt-3">
      <button @click="cookiesResponse(false)" type="button"
              class="fill-width text-font-caption btn-block font-weight-bold btn btn-light">Decline
      </button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      item_payload: {
        cookiesKey: "cookies_consent"
      }
    }
  },
  computed: {
    show() {
      let app = this
      const value = localStorage.getItem(app.item_payload.cookiesKey)
      return value != "true" && value != true
    }
  },
  methods: {
    cookiesResponse(userResponse) {
      let app = this
      if (userResponse) {
        console.log("User accepted the usage of cookies!")
      } else {
        console.log("User declined the usage of cookies!")
      }

      // save the state
      localStorage.setItem(app.item_payload.cookiesKey, userResponse)

      // load the element
      let cookies_overlay = document.getElementById("cookies_consent");
      if (cookies_overlay) {
        // hide the element
        cookies_overlay.style.display = 'none';
      }
    }
  }
}
</script>