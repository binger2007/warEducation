<template>
  <div class="hello">{{baseUrl}}</div>
</template>

<script>
import { baseUrl } from "../assets/js/public";

export default {
  name: "manage",
  created() {
    this.baseUrl = baseUrl;
  },
  data() {
    return {
      baseUrl: ""
    };
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
