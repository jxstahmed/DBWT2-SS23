import './bootstrap';

import {createApp} from "vue";

const app = createApp({
    data() {
        return {
            item_payload: {
                items: [],
                searchQuery: null
            },
            views_payload: {
                hasSearched: false,
                hasResetted: false,
                searchAjax: null,
                searchTimeout: null
            },
        }
    },
    mounted() {
        let app = this

        app.getArticles()
    },
    watch: {
        "item_payload.searchQuery"(newVal) {
            let app = this
            if (newVal && newVal.length > 2) {
                app.onSearchTimeout()
            } else {
                if (app.views_payload.hasSearched && !app.views_payload.hasResetted) {
                    app.views_payload.hasResetted = true
                    app.getArticles()
                }
            }
        }
    },
    methods: {
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
        onCartClicked(item) {
            let app = this
            queueCart({id: item.id, ab_name: item.ab_name, ab_price: item.ab_price})
        },
        searchForArticles() {
            let app = this
            let search = app.item_payload.searchQuery
            if (search) {
                app.getArticles({search: search})
            } else {
                alert("Couldn't find any search input!");
            }
        },
        getArticles(payload = null) {
            let app = this

            // if a search is in progress, cancel it
            if (app.views_payload.searchAjax)
                app.views_payload.searchAjax.abort()

            let query_string = ""

            if (payload) {
                query_string = "?" + new URLSearchParams(payload).toString()
            }

            const xhttp = new XMLHttpRequest();
            xhttp.open("GET", "/api/articles" + query_string);
            xhttp.onreadystatechange = function () {
                if (xhttp.readyState === 4) {
                    if (xhttp.status === 200) {
                        const response = JSON.parse(this.responseText)
                        app.item_payload.items = response.articles
                    } else {
                        console.error(xhttp.statusText);
                    }
                }
            }

            xhttp.onerror = function () {
                console.error(xhttp.statusText);
            };


            app.views_payload.searchAjax = xhttp
            xhttp.send();
        }
    }
});

app.mount("#app");




/*
Aufgabe 6
a)
Es würde der Fehler "zu viele Anfragen" auftreten, womit die API nicht mehr erreichbar wäre.
Um dies zu beheben, müsste man die Search Rate limitieren für den Benutzer, womit eine Suche nur alle x Sekunden stattfindet.
Auch eine Lösung wäre es bestimmte Suchen zu cachen per Redis oder ähnlichem.

b)
Es geschehen zu viele Anfragen auf einmal für die Suche.
Dies haben wir dadurch behoben, dass jede suche nach 500ms geschieht falls diese gültig ist.
Wenn der Benutzer seine Suche anpasst, wird die vorherige Suche gecancelled.
Durch diesen Timeout und Abbruch, verhindern wir Rate limiting von Laravel.
*/