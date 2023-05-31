import './bootstrap';
import App from "./newsite/App";
import SiteHeader from "./newsite/dashboard/SiteHeader";
import SiteBody from "./newsite/dashboard/SiteBody";
import SiteFooter from "./newsite/dashboard/SiteFooter";

import {createApp} from "vue";

const app = createApp({})

app.component('App', App)
app.component('abalo-header', SiteHeader)
app.component('abalo-body', SiteBody)
app.component('abalo-footer', SiteFooter)

app.mount("#app");