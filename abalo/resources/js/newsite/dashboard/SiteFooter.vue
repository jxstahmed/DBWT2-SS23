<template>
  <footer>
    <hr>
    <ul>
      <li>(c) Abalo GmbH</li>
      <li>Ahmed Jumaa &amp; Mohammad Hammado</li>
      <li class="clickable navigation-item" @click="showImpressum()">Impressum</li>
    </ul>
  </footer>
</template>
<script>
export default {
  data() {
    return {

    }
  },
  emits: ["bus"],
  methods: {
    showImpressum() {
      let app = this
      app.$emit("bus", {type: "navigation", name: "impressum"})
    }
  }
}
</script>