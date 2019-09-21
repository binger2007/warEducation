
<template>
  <div id="app">
    <div class="banner">
      <router-link to="/admin" class="logo">教育动员系统</router-link>
      <div class="poolTitle">{{$route.query.title}}</div>
      <ul class="userInfo">
        <li>
          <i class="el-icon-thirdwode1"></i>
          您好，{{userName}}
        </li>
        <!-- <li>
          <i class="el-icon-house"></i>
          <router-link to="/admin">返回首页</router-link>
        </li>-->
        <!-- <li>
          <i class="el-icon-thirdshezhi"></i>
          <router-link :to="{path:'/admin/system'}">用户管理</router-link>
        </li>-->
        <li>
          <i class="el-icon-setting"></i>
          <a style="cursor: pointer;" @click="changePass()">修改密码</a>
        </li>
        <li>
          <i class="el-icon-thirdtuichu"></i>
          <a @click="logout" style="cursor: pointer;">退出</a>
        </li>
      </ul>
      <div class="clear"></div>
    </div>
    <div class="header">
      <el-menu
        :default-active="$store.state.activeIndex"
        mode="horizontal"
        background-color="#E4E7ED"
        active-text-color="#0087c2"
        @select="handleSelect"
      >
        <el-menu-item index="1">
          <router-link to="/admin">首页</router-link>
        </el-menu-item>
        <el-menu-item index="2">
          <router-link :to="{path:'/admin/articleManage'}">添加内容</router-link>
        </el-menu-item>
        <el-menu-item index="3">
          <router-link :to="{path:'/admin/classManage'}">类别管理</router-link>
        </el-menu-item>
        <el-menu-item index="4">
          <router-link :to="{path:'/admin/departmentManage'}">单位管理</router-link>
        </el-menu-item>

        <el-menu-item index="5">
          <router-link :to="{path:'/admin/userManage'}">用户管理</router-link>
        </el-menu-item>
      </el-menu>
    </div>
    <resetPass ref="resetPass"></resetPass>
    <div class="main">
      <router-view v-if="isRouterShow" />
    </div>
    <div class="footer">
      <span>版权所有：K70L</span>
      <span>设计制作：自动化站——王文兵</span>
      <span>电话：177XXXXXXXX</span>
    </div>
  </div>
</template>
<script>
import resetPass from "./ResetPass";
import { getCookie, delCookie } from "../assets/js/cookie.js";
export default {
  name: "admin",
  provide() {
    return {
      reload: this.reload
    };
  },
  components: {
    resetPass
  },
  data() {
    return {
      userType: getCookie("utype"),
      userName: "",
      isRouterShow: true
    };
  },

  computed: {},
  mounted() {
    if (!getCookie("userName")) {
      this.$router.push("/login");
    } else {
      this.userName = getCookie("userName");
    }
  },
  methods: {
    async reload() {
      this.isRouterShow = false;
      await this.$nextTick();
      this.isRouterShow = true;
    },
    logout() {
      delCookie("userName");
      this.$router.push("/login");
    },
    handleSelect(key, keyPath) {
      this.$store.commit("changeActiveIndex", key);
    },
    changePass() {
      this.$refs.resetPass.dialogVisible = true;
      this.$refs.resetPass.ruleForm.pass = "";
      this.$refs.resetPass.ruleForm.checkPass = "";
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
* {
  margin: 0px;
  padding: 0px;
  text-decoration: none;
}

.banner {
  position: fixed;
  z-index: 1000;
  top: 0px;
  left: 0px;
  height: 60px;
  width: 100%;
  background-color: #0087c2;
  color: white;
  font-size: 14px;
  /* position: relative; */
}
.banner .logo {
  display: block;
  position: absolute;
  top: 0px;
  left: 0px;
  height: 60px;
  line-height: 60px;
  background: url(../../static/img/logo.png) no-repeat 10px 15px;
  background-size: 75px;
  padding-left: 105px;
  font-size: 20px;
  z-index: 1000;
  color: #fff;
}
.banner .userInfo {
  position: absolute;
  right: 40px;
  top: 0px;
}
.banner .userInfo li {
  display: inline-block;
  height: 60px;
  line-height: 60px;
  padding: 0px 8px;
}
.banner .userInfo li span,
.banner .userInfo li a {
  color: white;
}
.header {
  position: fixed;
  z-index: 1000;
  top: 60px;
  left: 0px;
  width: 100%;
}
.header .el-menu {
  padding-left: 33%;
}

.header .el-menu li {
  width: 10%;
  text-align: center;
  height: 40px;
  line-height: 36px;
}

.header .el-menu li a {
  display: inline-block;
  width: 100%;
}

.main-wrapper {
  position: relative;
}
.main {
  margin-top: 100px;
  margin-bottom: 50px;
}
.footer {
  width: 100%;
  text-align: center;
  position: fixed;
  z-index: 999;
  bottom: 0px;
  left: 0px;
  height: 30px;
  line-height: 30px;
  background-color: rgb(228, 231, 237);
  /* color: #fff; */
  font-size: 12px;
}
.footer span {
  margin: 0px 10px;
}
</style>
