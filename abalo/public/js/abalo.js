// setup the navigation

const KEY_COOKIE_STATE = 'cookies_state';
const KEY_CART = 'cart';

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
    // due to the assignment, we need to clear the cart
    // updateCart([])


    validateNav();
    validateCookiesConsent();
    validateCartViewItems();
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
    let cookies_state = localStorage.getItem(KEY_COOKIE_STATE) === "true";
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
    localStorage.setItem(KEY_COOKIE_STATE, userResponse)

    // load the element
    let cookies_overlay = document.getElementById("cookies_consent");
    if(cookies_overlay) {
        // hide the element
        cookies_overlay.style.display = 'none';
    }
}

function getCart() {
    let cart_items = localStorage.getItem(KEY_CART)
    return JSON.parse(cart_items ?? "[]");
}

function getCartProduct(payload) {
    if(!payload) return null

    const cart_items = getCart();

    let product_index = cart_items.findIndex(e => parseInt(e.id) == parseInt(payload.id));


    return cart_items[product_index];
}

function queueCart(payload) {
    if(!payload) return;

    let product = getCartProduct(payload);

    if(product) {
        // exists, we should dequeue
        dequeueCart(payload);
    } else {
        // doesn't exist, we should enqueue
        enqueueCart(payload);
    }
}

function enqueueCart(payload) {
    if(!payload) return;


    let cart_items = getCart();
    cart_items.push(payload)

    updateCart(cart_items);
    updateCartItem(payload);
    validateCartViewItems();
}

function dequeueCart(payload) {
    if(!payload) return;

    let cart_items = getCart();
    cart_items.splice(cart_items.findIndex(e => e.id == payload.id), 1)

    updateCart(cart_items);
    updateCartItem(payload);
    validateCartViewItems();
}

function updateCart(payload) {
    // save into local storage
    localStorage.setItem(KEY_CART, JSON.stringify(payload));
}

function updateCartItem(payload) {
    if(!payload) return;

    let cart_button = document.getElementById(`cart_button_${payload.id}`)
    let cart_span = document.getElementById(`cart_span_${payload.id}`)
    let cart = document.getElementById(`cart`)

    if(!cart_button || !cart_span || !cart) return;

    let product = getCartProduct(payload);

    if(product) {
        cart_span.innerText = "-";
        cart_button.classList.remove("btn-outline-dark");
        cart_button.classList.add("btn-dark");
    } else {
        cart_span.innerText = "+";
        cart_button.classList.remove("btn-dark");
        cart_button.classList.add("btn-outline-dark");
    }

    cart.innerText = getCart().length
}

function validateCartViewItems() {
    let cart_view = document.getElementById("cart_view");
    if(cart_view) {
        if(cart_view.style.display !== 'none') {
            loadCartViewItems();
        }
    }
}

function toggleCartViewItems() {
    let cart_view = document.getElementById("cart_view");
    if(cart_view) {
        if(cart_view.style.display === 'none') {
            cart_view.style.display = 'initial';
            loadCartViewItems();
        } else {
            cart_view.style.display = 'none';
        }
    }
}


function loadCartViewItems() {
    const cart_items = getCart();

    let tbody = document.getElementById("cart_view_tbody");
    if(tbody) {
        // delete all children
        tbody.innerHTML = '';

        cart_items.forEach(item => {
            let tr = document.createElement("tr");
            tr.innerHTML = `
            <tr> 
                <td class="text-font-caption-less align-self-center" style="vertical-align: middle">${item.name}</td>
                <td class="text-font-caption-less align-self-center" style="vertical-align: middle">${item.price}€</td>
                <td> 
                    <button style="min-width: 25px" class="btn-dark text-font-caption-less font-weight-semibold btn btn-sm" id="cart_view_button_${item.id}" 
                    onClick="">
                        <span id="cart_view_span_${item.id}" class="font-weight-bold text-font-caption-less"> - </span> 
                    </button>
                </td> 
            </tr>
            `

            tbody.appendChild(tr);

            let btn = document.getElementById(`cart_view_button_${item.id}`)
            if(btn) {
                btn.onclick = () => {
                    dequeueCart({id: item.id})
                }
            }
        })
    }
}