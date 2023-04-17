// setup the navigation

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

document.addEventListener("DOMContentLoaded", function() {
  validateNav();
});


function validateNav() {
    let nav = document.getElementById("nav");
    console.log(nav);
    if(nav) {
        navigation_items.forEach(navItem => {
            let element_li = document.createElement('li');
            if(navItem.items && navItem.items.length > 0) {
                // we have sub items, we allow only 1-level subitems!
                element_li.innerHTML = ``
            } else {
                element_li.innerText = navItem.text
            }

            nav.appendChild(element_li);
        })
    }
}


