<template>
  <ul id="nav" class="abalo_nav">
    <li v-for="(item, index) in item_payload.items" :key="index" :class="{'navigation-item clickable': item.name}" @click="navigateItem(item.name)">
      {{ item.text }}
      <ul v-if="item.items && item.items.length > 0">
        <li v-for="(subItem, subIndex) in item.items" :key="subIndex" :class="{'navigation-item clickable': subItem.name}" @click="navigateItem(subItem.name)">
          {{ subItem.text }}
        </li>
      </ul>
    </li>
  </ul>
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
  emits: ["bus"],
  methods: {
    navigateItem(name) {
      if(!name) return

      let app = this
      app.$emit("bus", {type: "navigation", name: name})
    }
  }
}


</script>