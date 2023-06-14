<template>
  <div class="col-12 nav--menu">
    <ul id="nav" class="nav--menu__ul">
      <li v-for="(item, index) in item_payload.items" :key="index" :class="{'nav--menu__item nav--menu__item--clickable': item.name, 'nav--menu__item--normal': !item.name}" @click="navigateItem(item.name)">
        {{ item.text }}
        <ul v-if="item.items && item.items.length > 0">
          <li v-for="(subItem, subIndex) in item.items" :key="subIndex" :class="{'nav--menu__item nav--menu__item--clickable': subItem.name, 'nav--menu__item--normal': !item.name}" @click="navigateItem(subItem.name)">
            {{ subItem.text }}
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  data() {
    return {
      item_payload: {
        items: [
          {
            text: "Home",
            name: "home"
          },
          {
            text: "Kategorien",
            name: "articles"
          },
          {
            text: "Verkaufen",
            name: "newarticle"
          },
          {
            text: "Unternehmen",
            items: [
              {
                text: "Philosophie",
                name: "home"
              },
              {
                text: "Karriere",
                name: "home"
              }
            ]
          },
        ]
      }
    }
  },
  methods: {
    navigateItem(name) {
      if(!name) return

      let app = this
      app.$eventBus.emit("navigation", {type: "navigation", name: name})
    }
  }
}


</script>