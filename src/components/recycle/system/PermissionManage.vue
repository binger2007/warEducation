<template>
  <el-card class="box-card">
    <div slot="header" class="header-wrap">
      <i class="el-icon-star-on"></i>
      <span>权限列表</span>
      <el-button
        @click="openAddPagePermissionDialog()"
        class="adduser"
        type="primary"
        size="medium"
        icon="el-icon-plus"
      >添加页面权限</el-button>
    </div>
    <el-table
      :data="permissionTableData"
      style="width: 100%;margin-bottom: 20px;"
      row-key="UUID"
      border
      default-expand-all
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
    >
      <el-table-column prop="TITLE" label="名称" width="240"></el-table-column>
      <el-table-column prop="DESCRIPTION" label="描述"></el-table-column>
      <el-table-column label="操作" width="350">
        <template slot-scope="scope">
          <el-button
            @click="editPermission(scope.row);"
            size="small"
            type="warning"
            icon="el-icon-edit"
          >修改</el-button>
          <el-button
            @click="delPermission(scope.row);"
            size="small"
            type="danger"
            icon="el-icon-delete"
          >删除</el-button>
          <el-button
            @click="addSonPermission(scope.row);"
            size="small"
            type="primary"
            icon="el-icon-plus"
            v-if="scope.row.PAGE_ID == 'null'"
          >添加按钮权限</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 添加权限对话框 -->
    <el-dialog
      :title="addPermissionDialogTitle"
      :visible.sync="addPagePermissionDialog"
      @closed="colsePermissionDialog"
    >
      <el-form :model="addPermissionForm" :rules="addPermissionRules" ref="addPermissionForm">
        <el-form-item label="名称" :label-width="formLabelWidth" prop="title">
          <el-input v-model="addPermissionForm.title" placeholder="请填入名称"></el-input>
        </el-form-item>
        <el-form-item label="描述" :label-width="formLabelWidth">
          <el-input v-model="addPermissionForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addPagePermissionDialog = false">取 消</el-button>
        <el-button type="primary" @click="submitPermission('addPermissionForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!-- 修改权限对话框 -->
    <el-dialog title="修改权限" :visible.sync="editPermissionDialog">
      <el-form :model="editPermissionForm" :rules="editPermissionRules" ref="editPermissionForm">
        <el-form-item label="名称" :label-width="formLabelWidth" prop="title">
          <el-input v-model="editPermissionForm.title" placeholder="请填入名称"></el-input>
        </el-form-item>
        <el-form-item label="描述" :label-width="formLabelWidth">
          <el-input v-model="editPermissionForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addPagePermissionDialog = false">取 消</el-button>
        <el-button type="primary" @click="submitEditPermission('editPermissionForm')">确 定</el-button>
      </div>
    </el-dialog>
  </el-card>
</template>

<script>
import { uuid } from "../../assets/js/public";
export default {
  name: "PermissionManage",
  data() {
    return {
      //添加权限对话框
      addPagePermissionDialog: false,
      formLabelWidth: "120px",
      //添加权限表单
      addPermissionForm: {
        id: "",
        pType: "", //0为按钮级，1为页面级
        title: "",
        description: "",
        pageID: "",
        createTime: "",
        type: "addPermission",
        system: "pool"
      },
      //验证添加权限
      addPermissionRules: {
        title: [{ required: true, message: "请输入权限名称", trigger: "blur" }]
      },
      //权限列表数据
      permissionTableData: [],
      //修改权限对话框
      editPermissionDialog: false,
      //添加权限表单
      editPermissionForm: {
        uuid: "",
        title: "",
        description: "",
        type: "editPermission"
      },
      //验证添加权限
      editPermissionRules: {
        title: [{ required: true, message: "请输入权限名称", trigger: "blur" }]
      }
    };
  },
  computed: {
    addPermissionDialogTitle() {
      if (this.addPermissionForm.pType == "1") {
        return "添加页面权限";
      } else {
        return "添加按钮权限";
      }
    }
  },
  mounted() {
    this.loadPermission();
  },
  methods: {
    //添加权限
    openAddPagePermissionDialog() {
      this.addPagePermissionDialog = true;
      this.addPermissionForm.pType = 1;
      this.addPermissionForm.pageID = "";
    },
    colsePermissionDialog() {
      this.addPermissionForm.title = "";
      this.addPermissionForm.description = "";
    },
    submitPermission(formName) {
      this.addPermissionForm.id = uuid();
      this.addPermissionForm.createTime = new Date().valueOf();
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.$Axios
            .post("/handlePermission", this.addPermissionForm)
            .then(res => {
              if (res.data) {
                this.$message({
                  message: "添加权限成功！",
                  type: "success"
                });
                this.addPermissionForm.title = "";
                this.addPermissionForm.description = "";

                this.loadPermission();
              }
            });
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    //加载权限列表
    loadPermission() {
      this.$Axios.get("/handlePermission?type=listPermission").then(res => {
        //处理返回的数据，得到父子结构数据
        var arr_f = [];
        var arr_s = [];
        res.data.forEach(ele => {
          if (ele.PAGE_ID != "null") {
            arr_s.push(ele);
          } else {
            arr_f.push(ele);
          }
        });
        arr_s.forEach(ele => {
          arr_f.forEach(ele2 => {
            if (ele.PAGE_ID == ele2.UUID) {
              if (!ele2.children) {
                ele2.children = [ele];
              } else {
                ele2.children.push(ele);
              }
            }
          });
        });
        this.permissionTableData = arr_f;
      });
    },
    editPermission(row) {
      this.editPermissionForm.uuid = row.UUID;
      this.editPermissionForm.title = row.TITLE;
      this.editPermissionForm.description = row.DESCRIPTION;
      this.editPermissionDialog = true;
    },
    delPermission(row) {
      this.$Axios
        .get("/handlePermission?type=delPermission&uuid=" + row.UUID)
        .then(res => {
          // console.log(res);
          this.$message({
            message: "删除成功",
            type: "success"
          });
          this.loadPermission();
        });
    },
    addSonPermission(row) {
      this.addPagePermissionDialog = true;
      this.addPermissionForm.pType = 0;
      this.addPermissionForm.pageID = row.UUID;
    },
    //提交编辑权限对话框
    submitEditPermission(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.$Axios
            .post("/handlePermission", this.editPermissionForm)
            .then(res => {
              if (res.data) {
                this.$message({
                  message: "修改权限成功！",
                  type: "success"
                });
                this.editPermissionDialog = false;
                this.loadPermission();
              }
            });
        } else {
          // console.log("error submit!!");
          return false;
        }
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.header-wrap {
  position: relative;
}
.header-wrap button.adduser {
  position: absolute;
  top: -7px;
  right: 0px;
}
</style>
