<template>
  <div class="col-12">
    <div class="row">
      <div class="col-12 mt-3">
        <div class="form-group">
          <label for="article_name">Name</label>
          <input v-model="item_payload.article_name" type="text" class="form-control" id="article_name"
                 name="article_name"
                 placeholder="Artikelname eingeben">
          <small id="emailHelp" class="form-text text-muted">Bitte einen passenden Titel verwenden!</small>
        </div>
      </div>

      <div class="col-12 mt-3">
        <div class="form-group mt-1">
          <label for="article_price">Preis</label>
          <input v-model="item_payload.article_price" min="1" type="number" class="form-control" id="article_price"
                 name="article_price"
                 placeholder="Artikelpreis in Cents eingeben">
        </div>
      </div>

      <div class="col-12 mt-3">
        <div class="form-group mt-1">
          <label for="article_description">Beschreibung</label>
          <textarea v-model="item_payload.article_description" class="form-control" id="article_description"
                    name="article_description"
                    placeholder="Was möchten Sie dazu sagen?" rows="4"></textarea>
        </div>
      </div>

      <div class="col-12 mt-3">
        <button class="mt-3 btn-block font-weight-bold btn btn-dark" @click="onSubmit()">Abschicken</button>
      </div>
    </div>

    <GDialog v-model="views_payload.dialog" max-width="500">
      <div class="wrapper">
        <div class="content">
          <div class="title">Ihr Artikel</div>

          <p>
            {{ views_payload.message }}
          </p>
        </div>

        <div class="actions">
          <button
              class="btn btn--outline-gray"
              @click="views_payload.dialog = false"
          >
            Schließen
          </button>
        </div>
      </div>
    </GDialog>

  </div>
</template>
<script>
import {GDialog} from "gitart-vue-dialog/dist/index";

export default {
  components: {GDialog},
  data() {
    return {
      item_payload: {
        article_name: null,
        article_price: null,
        article_description: null
      },
      views_payload: {
        dialog: false,
        message: null,
        error: false
      }
    }
  },
  watch: {
    "views_payload.dialog"(newVal) {
      if (!newVal) {
        let app = this

        if (!app.views_payload.error)
          app.$eventBus.emit("navigation", {type: "navigation", name: "articles"})
      }
    }
  },
  methods: {
    onSubmit() {
      let app = this

      let formData = new FormData();
      formData.append("email", localStorage.getItem("email") ?? "admin@abalo.example.com")
      formData.append("article_name", app.item_payload.article_name)
      formData.append("article_price", app.item_payload.article_price)
      formData.append("article_description", app.item_payload.article_description)

      app.views_payload.error = false
      const xhttp = new XMLHttpRequest();
      xhttp.open("POST", "/api/articles");
      app.views_payload.message = null
      xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
          app.views_payload.dialog = true
          if (xhttp.status === 200) {
            // reset after receiving a response
            app.item_payload.article_name = null
            app.item_payload.article_price = null
            app.item_payload.article_description = null
            app.views_payload.message = "Ihr Artikel wurde erfolgreich veröffentlicht!"
            //app.$eventBus.emit("navigation", {type: "navigation", name: "articles"})
          } else {
            app.views_payload.error = true
            try {
              const data = JSON.parse(xhttp.response)
              app.views_payload.message = data.message
            } catch (e) {
              app.views_payload.message = xhttp.statusText
            }
          }
        }
      }

      xhttp.onerror = function () {
        app.views_payload.error = true
        app.views_payload.dialog = true
        app.views_payload.message = xhttp.statusText
      };

      xhttp.send(formData);
    }
  }
}
</script>