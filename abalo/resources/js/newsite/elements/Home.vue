<template>
  <div class="col-12">
    <div class="row">


      <div class="col-12">
        <p>Developer Tools</p>
      </div>


      <div class="col-4 mt-2 justify-center text-center">
        <button class="btn-block text-font-caption-more font-weight-semibold btn btn-dark" @click="connectWebsocket()">
          Mit Websocket verbinden
        </button>
      </div>

      <div class="col-4 mt-2 justify-center text-center">
        <button class="btn-block text-font-caption-more font-weight-semibold btn btn-dark" @click="checkWebsocketStatus()">
          Websocket prüfen
        </button>
      </div>

      <div class="col-4 mt-2 justify-center text-center">
        <button class="btn-block text-font-caption-more font-weight-semibold btn btn-dark" @click="enableMaintenance()">
          Wartungsmodus einschalten
        </button>

      </div>


      <div class="col-12 mt-5">
        <div class="form-group">
          <label for="article_name" class="mb-2">Email</label>
<!--          seller@abalo.example.com bis 3-->
<!--          visitor@abalo.example.com bis 3-->

          <select v-model="login_payload.email" class="form-select"
                 name="email">
            <option value="admin@abalo.example.com">admin@abalo.example.com</option>
            <option value="seller@abalo.example.com">seller@abalo.example.com</option>
            <option value="seller2@abalo.example.com">seller2@abalo.example.com</option>
            <option value="seller3@abalo.example.com">seller3@abalo.example.com</option>
            <option value="visitor@abalo.example.com">visitor@abalo.example.com</option>
            <option value="visitor2@abalo.example.com">visitor2@abalo.example.com</option>
            <option value="visitor3@abalo.example.com">visitor3@abalo.example.com</option>
          </select>
        </div>

        <button class="mt-2 btn-block text-font-caption-more font-weight-semibold btn btn-dark" @click="login()">
          Anmelden
        </button>

        <button style="margin-left: 14px;" class="mt-2 btn-block text-font-caption-more font-weight-semibold btn btn-dark" @click="user()">
          User prüfen
        </button>

      </div>

      <div class="col-12 mt-2 justify-center text-center" v-if="views_payload.message && !views_payload.dialog">
        <small id="emailHelp" class="form-text text-muted">{{views_payload.message}}</small>
      </div>



    </div>


    <!-- Dialog to give feedback -->
    <GDialog v-model="views_payload.dialog" max-width="500">
      <div class="wrapper">
        <div class="content">
          <div class="title">{{ views_payload.dialog_title }}</div>

          <p>
            {{ views_payload.dialog_message }}
          </p>
        </div>

        <div class="actions">
          <button
              class="btn btn--outline-gray"
              @click="views_payload.dialog = false">
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
        websocket: null
      },
      login_payload: {
        email: "admin@abalo.example.com"
      },
      views_payload: {
        dialog: false,
        message: null,
        dialog_title: "",
        dialog_message: null
      },
    }
  },
  mounted() {
    let app = this

    app.login_payload.email = localStorage.getItem("email") ?? "admin@abalo.example.com"

    app.connectWebsocket()
  },
  methods: {
    connectWebsocket() {
      let app = this

      if (app.item_payload.websocket) {
        // close existing connection
        app.item_payload.websocket.close()
        app.item_payload.websocket = null
      }

      const email = localStorage.getItem("email") ?? "admin@abalo.example.com"
      app.item_payload.websocket = new WebSocket(`ws://localhost:8085/broadcast?email=${email}`);

      app.item_payload.websocket.onopen = (event) => {
        console.log('Connection established');
      };
      app.item_payload.websocket.onclose = (closeEvent) => {
        console.log(
            'Connection closed' +
            ': code=', closeEvent.code,
            '; reason=', closeEvent.reason);
      };
      app.item_payload.websocket.onmessage = (msgEvent) => {
        let data = JSON.parse(msgEvent.data);
        console.log('Message received', data);

        if(data.type === "maintenance") {
          app.views_payload.dialog_title = "Wartungsnachricht"
          app.views_payload.dialog_message = data.message
          app.views_payload.dialog = true
        } else if(data.type === "article_sold") {
          app.views_payload.dialog_title = "Verkaufsmeldung"
          app.views_payload.dialog_message = data.message
          app.views_payload.dialog = true
        } else if(data.type === "article_offer") {
          app.$eventBus.emit("article-offer", {id: data.article_id, creator_email: data.creator_email, message: data.message})
        }
      }
    },
    checkWebsocketStatus() {
      let app = this


      if(app.item_payload.websocket.readyState === WebSocket.CONNECTING) {
        app.views_payload.dialog_message = "Websocket is connecting"
      } else if(app.item_payload.websocket.readyState === WebSocket.OPEN) {
        app.views_payload.dialog_message = "Websocket is connected."
      } else if(app.item_payload.websocket.readyState === WebSocket.CLOSING) {
        app.views_payload.dialog_message = "Websocket is closing."
      } else if(app.item_payload.websocket.readyState === WebSocket.CLOSED) {
        app.views_payload.dialog_message = "Websocket is closed."
      } else {
        app.views_payload.dialog_message = "No status of websocket was found."
      }

      app.views_payload.dialog_title = "Websocket"
      app.views_payload.dialog = true
    },
    enableMaintenance() {
      let app = this

      app.views_payload.error = false
      const xhttp = new XMLHttpRequest();
      xhttp.open("POST", "/api/websocket/maintenance");
      app.views_payload.message = null
      xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
          if (xhttp.status === 200) {
            // reset after receiving a response
            const data = JSON.parse(xhttp.response)
            app.views_payload.message = data.message

          } else {
            app.views_payload.dialog_title = "Maintenance"
            app.views_payload.dialog = true
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
        app.views_payload.dialog_title = "Maintenance"
        app.views_payload.dialog = true
        app.views_payload.message = xhttp.statusText
      };

      xhttp.send();
    },
    login() {
      let app = this

      localStorage.setItem("email", app.login_payload.email)

      app.views_payload.dialog_title = "Login"
      app.views_payload.dialog_message = "Saved into LocalStorage"
      app.views_payload.dialog = true
    },
    user() {
      let app = this

      app.views_payload.dialog_title = "User"
      app.views_payload.dialog_message = localStorage.getItem("email")
      app.views_payload.dialog = true
    }
  }
}
</script>