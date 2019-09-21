<template>
  <div id="app">
    <!-- 路由加载 -->
    <router-view />
  </div>
</template>
<script>
import { getCookie } from "./assets/js/cookie.js";
export default {
  name: "App",
  mounted() {
    /*页面挂载获取cookie，如果存在username的cookie，则跳转到主页，不需登录*/
    if (getCookie("userName")) {
      this.$router.push("/admin");
    } else {
      this.$router.push("/login");
    }
  }
};
</script>

<style>
* {
  margin: 0px;
  padding: 0px;
  text-decoration: none;
}
a {
  color: #1f2f3d;
}
.clear {
  clear: both;
}
.wrap90 {
  width: 90%;
  margin: 30px auto;
}
</style>
