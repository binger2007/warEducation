<template>
  <el-container :style="{height:treeWrapHeight}">
    <el-aside :width="treeWrapWidth" class="treewrap">
      <div class="arrowWrap">
        <i
          v-if="$store.state.system.showTreeFlag"
          class="el-icon-arrow-left fixedicon"
          @click="switchShow"
        ></i>
        <i v-else class="el-icon-arrow-right fixedicon" @click="switchShow"></i>
        <h4 v-if="$store.state.system.showTreeFlag">单位序列</h4>
        <OrganizationTree></OrganizationTree>
      </div>
    </el-aside>
    <el-main class="main-wrapper">
      <el-tabs class="system-tabs">
        <el-tab-pane>
          <span slot="label">
            <i class="el-icon-departicon_zhanghao"></i>
            用户管理
          </span>
          <UserManage></UserManage>
        </el-tab-pane>
        <el-tab-pane>
          <span slot="label">
            <i class="el-icon-key"></i> 权限管理
          </span>
          <PermissionManage></PermissionManage>
        </el-tab-pane>
        <el-tab-pane>
          <span slot="label">
            <i class="el-icon-thirdtoutiao"></i> 模板管理
          </span>
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>卡片名称2</span>
              <el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>
            </div>
            <div v-for="o in 4" :key="o" class="text item">{{'列表内容 ' + o }}</div>
          </el-card>
        </el-tab-pane>
      </el-tabs>
    </el-main>
  </el-container>
</template>

<script>
import OrganizationTree from "../OrganizationTree";
import PermissionManage from "./PermissionManage";
import UserManage from "./UserManage";
export default {
  name: "system",
  data() {
    return {};
  },
  components: {
    OrganizationTree: OrganizationTree,
    PermissionManage: PermissionManage,
    UserManage: UserManage
  },
  computed: {
    treeWrapWidth() {
      if (this.$store.state.system.showTreeFlag) {
        return "230px";
      } else {
        return "30px";
      }
    },
    treeWrapHeight() {
      if (this.$route.query.showNavFlag) {
        return "calc(100vh - 101px)";
      } else {
        return "calc(100vh - 60px)";
      }
    }
  },
  watch: {},
  mounted() {},
  methods: {
    //是否显示
    switchShow() {
      this.$store.commit("changeShowTreeFlag");
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.system-tabs {
  margin: 20px;
}

.treewrap {
  width: 100%;
  background: rgb(244, 244, 245);
  position: relative;
}
.fixedicon {
  font-size: 24px;
  color: #606266;
  position: absolute;
  right: 0px;
  top: 45%;
  cursor: pointer;
}

.arrowWrap h4 {
  text-align: center;
  font-size: 16px;
  margin-top: 20px;
  color: #606266;
}
</style>
