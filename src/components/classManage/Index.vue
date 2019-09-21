<template>
  <div>
    <div style="width: 90%;margin: 20px auto; margin-top:120px;">
      <el-button
        v-if="userType == 0"
        type="primary"
        icon="el-icon-plus"
        style="width: 100%;"
        @click="append({id:0,p_ids:null})"
      >添加根类别</el-button>
    </div>
    <el-card class="box-card" shadow="hover">
      <el-tree
        :data="treeData"
        node-key="id"
        default-expand-all
        :expand-on-click-node="false"
        draggable
        @node-drop="handleDrop"
      >
        <span class="custom-tree-node" slot-scope="{ node, data }">
          <span>{{ node.label }}</span>
          <span>
            <!-- <el-button
              type="text"
              icon="el-icon-circle-plus-outline"
              size="mini"
              @click="() => addContent(data)"
            >添加内容</el-button>-->
            <el-button
              v-if="userType == 0"
              type="text"
              icon="el-icon-plus"
              size="mini"
              @click="() => append(data)"
            >增加</el-button>
            <el-button
              v-if="userType == 0"
              type="text"
              icon="el-icon-delete"
              size="mini"
              @click="() => remove(node, data)"
            >删除</el-button>
          </span>
        </span>
      </el-tree>
    </el-card>
    <el-dialog title="添加子类别" :visible.sync="addClassdialogVisible">
      <el-form :model="addClassForm" :rules="rules" ref="addClassForm">
        <el-form-item label="名称" :label-width="formLabelWidth" prop="cname">
          <el-input v-model="addClassForm.cname" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addClassdialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="submitClass('addClassForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getCookie, delCookie } from "../../assets/js/cookie.js";
import { generateOptions } from "../../assets/js/public";
export default {
  name: "classManage",
  data() {
    return {
      userType: getCookie("utype"),
      addClassdialogVisible: false,
      addClassForm: {
        cname: "",
        pid: "",
        pids: "",
        type: "addClass"
      },
      rules: {
        cname: [{ required: true, message: "请输入类别名称", trigger: "blur" }]
      },
      formLabelWidth: "120px",
      treeData: []
    };
  },
  mounted() {
    this.loadClass();
  },
  methods: {
    //加载分类信息
    loadClass() {
      this.$Axios.get("/handleClass.php?type=loadClass").then(res => {
        this.treeData = generateOptions(res.data);
      });
    },
    // 添加节点
    append(data) {
      this.addClassdialogVisible = true;
      this.addClassForm.pid = data.id;
      if (data.p_ids === null) {
        this.addClassForm.pids = "0";
      } else {
        this.addClassForm.pids = data.p_ids + "," + data.id;
      }
    },
    // 删除节点
    remove(node, data) {
      this.$confirm(
        "此操作将永久删除该分类以及分类下面的所有内容, 是否继续?",
        "提示",
        {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning"
        }
      )
        .then(() => {
          const parent = node.parent;
          const children = parent.data.children || parent.data;
          const index = children.findIndex(d => d.id === data.id);
          children.splice(index, 1);
          this.$Axios
            .get(
              "/handleClass.php?type=delClass&id=" +
                data.id +
                "&pids=" +
                data.p_ids
            )
            .then(res => {
              this.$message({
                type: "success",
                message: "删除成功!"
              });
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },

    submitClass(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.$Axios
            .post("/handleClass.php", this.$qs.stringify(this.addClassForm))
            .then(res => {
              if (res.data == "1") {
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.addClassForm.cname = "";
                this.loadClass();
              } else {
                this.$message.error("不成功，请重新输入！");
              }
            });
        } else {
          return false;
        }
      });
    },
    handleDrop(draggingNode, dropNode, dropType, ev) {
      var sql = "BEGIN; ";
      var fid;
      var fids;
      var arr = dropNode.parent.childNodes;
      var len = arr.length;
      // 先更新目标节点的顺序
      //注意：epcode不支持forEach语法
      for (var i = 0; i < len; i++) {
        sql +=
          "UPDATE category SET sortNum = '" +
          i +
          "' WHERE id = '" +
          arr[i].data.id +
          "';";
      }
      //再更新被操作节点的父节点和路径
      if (dropType == "inner") {
        fid = dropNode.data.id;
        fids = dropNode.data.p_ids + "," + fid;
      } else {
        fid = dropNode.data.p_id;
        fids = dropNode.data.p_ids;
      }
      sql +=
        "UPDATE category SET p_id = '" +
        fid +
        "',p_ids='" +
        fids +
        "' WHERE id = '" +
        draggingNode.data.id +
        "';";
      sql += " COMMIT;";
      this.$Axios
        .post(
          "/handleClass.php",
          this.$qs.stringify({
            type: "handleSort",
            sql: sql
          })
        )
        .then(res => {
          if (res.data) {
            this.$message({
              message: "排序成功",
              type: "success"
            });
          }
        });
    }
    // addContent(data) {
    //   this.$router.push({ path: "/admin/articleManage" });
    //   this.$store.commit("initAddArticle", data);
    //   this.$store.commit("changeActiveIndex", "2");
    // }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.box-card {
  width: 90%;
  margin: 20px auto;
}
.custom-tree-node {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
}
</style>
