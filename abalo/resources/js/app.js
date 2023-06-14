import './bootstrap';
import App from "./newsite/App.vue";
import SiteHeader from "./newsite/dashboard/SiteHeader.vue";
import SiteBody from "./newsite/dashboard/SiteBody.vue";
import SiteFooter from "./newsite/dashboard/SiteFooter.vue";
const Emitter = require('tiny-emitter');
import API from "axios"

// main.js
import 'gitart-vue-dialog/dist/style.css'
import { plugin as dialogPlugin } from 'gitart-vue-dialog'

import {createApp} from "vue";

const app = createApp({})

// Global emitter
app.config.globalProperties.$eventBus = new Emitter()
app.config.globalProperties.$api = API

app.use(dialogPlugin)
app.component('App', App)
app.component('abalo-header', SiteHeader)
app.component('abalo-body', SiteBody)
app.component('abalo-footer', SiteFooter)

app.mount("#app");