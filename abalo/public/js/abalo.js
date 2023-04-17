// setup the navigation

const COOKIES_STATE_KEY = 'cookies_state';

const navigation_items = [
    {
        text: "Home"
    },
    {
        text: "Kategorien"
    },
    {
        text: "Verkaufen"
    },
    {
        text: "Unternehmen",
        items: [
            {
                text: "Philosophie"
            },
            {
                text: "Karriere"
            }
        ]
    }
];

document.addEventListener("DOMContentLoaded", function () {
    validateNav();
    validateCookiesConsent();
});


function validateNav() {
    let nav = document.getElementById("nav");
    // verify that our element exists
    if (nav) {
        // loop through our const items of the navigation (main items)
        navigation_items.forEach(navItem => {
            // just push the element with a normal text
            let element_li = document.createElement('li');
            element_li.innerText = navItem.text
            nav.appendChild(element_li);

            // if the item has subitems, then we loop through them
            if (navItem.items && navItem.items.length > 0) {
                // we have sub items, we allow only 1-level subitems!
                // create another ul element and do the same process
                let element_ul = document.createElement('ul');
                navItem.items.forEach(subItem => {
                    let element_li = document.createElement('li');
                    element_li.innerText = subItem.text
                    element_ul.appendChild(element_li);
                });
                nav.appendChild(element_ul);
            }
        })
    }
}

function validateCookiesConsent() {
    // check if it was shown via the state
    let cookies_state = localStorage.getItem(COOKIES_STATE_KEY) === "true";
    if(cookies_state && cookies_state === true) return

    // load the element
    let cookies_overlay = document.getElementById("cookies_consent");
    if(cookies_overlay) {
        // show the element
        cookies_overlay.style.display = 'initial';
    }

}

function cookiesResponse(userResponse) {
    if(userResponse) {
        console.log("User accepted the usage of cookies!")
    } else {
        console.log("User declined the usage of cookies!")
    }

    // save the state
    localStorage.setItem(COOKIES_STATE_KEY, userResponse)

    // load the element
    let cookies_overlay = document.getElementById("cookies_consent");
    if(cookies_overlay) {
        // hide the element
        cookies_overlay.style.display = 'none';
    }
}