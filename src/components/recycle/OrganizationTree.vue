<template>
  <div>
    <el-tree
      v-show="$store.state.system.showTreeFlag"
      class="tree"
      ref="tree"
      :data="treeData"
      :props="defaultProps"
      :highlight-current="true"
      @node-click="handleNodeClick"
      @node-expand="handleNodeClick"
      node-key="机构名称码"
      :default-expanded-keys="defaultExpandKeys"
    ></el-tree>
  </div>
</template>
<script>
export default {
  name: "OrganizationTree",
  data() {
    return {
      treeData: [],
      defaultProps: {
        children: "children",
        label: "单位名称"
      },
      defaultExpandKeys: ["DC"]
    };
  },
  methods: {
    //点击加载下级数据
    handleNodeClick(data) {
      var jgmcm = data.机构名称码;
      //把选择的机构名称码传递出去
      this.$store.commit("changeRoot", data);
      if (!data.flag) {
        this.$Axios.get("/getTrees?rootDepartment=" + jgmcm).then(res => {
          res.data.forEach(res => {
            res.children = [{}];
            //下级节点是否已加载
            res.flag = false;
          });
          data.children = res.data;
          data.flag = true;
        });
      }
    },
    //初始加载单位树
    loadTreeData() {
      this.$Axios.get("/getTrees").then(res => {
        //显示全部
        var arr = [];
        res.data[0].flag = true;
        res.data[0].children = [];
        res.data.forEach((ele, index) => {
          if (index > 0) {
            ele.children = [{}];
            //下级节点是否已加载
            ele.flag = false;
            res.data[0].children.push(ele);
          }
        });
        arr.push(res.data[0]);
        this.treeData = arr;
        this.$store.commit("changeRoot", this.treeData[0]);
      });
    }
  },
  mounted() {
    //从后台加载单位序列数据
    this.loadTreeData();
  }
};
</script>
<style >
.tree {
  background-color: rgb(244, 244, 245);
  margin: 20px 30px 10px 10px;
}
.el-tree--highlight-current .el-tree-node.is-current > .el-tree-node__content {
  background-color: #128075 !important;
}
.el-tree-node.is-current > .el-tree-node__content .el-tree-node__label {
  color: #fff;
}
</style>


