<template>
  <div>
    <div style="width: 90%;margin: 20px auto;margin-top:120px;">
      <el-button
        v-if="userType == 0"
        type="primary"
        icon="el-icon-plus"
        style="width: 100%;"
        @click="addUserDialogFormVisible = true"
      >添加用户</el-button>
    </div>
    <el-table :data="userTableData" stripe style="width: 90%;margin: 30px auto;">
      <el-table-column type="index" align="center" width="50"></el-table-column>
      <el-table-column prop="uname" label="用户名" align="center"></el-table-column>
      <el-table-column prop="utype" label="类别" align="center"></el-table-column>
      <el-table-column prop="last_login_time" label="最后登陆时间" width="220" align="center"></el-table-column>
      <el-table-column prop="last_login_ip" label="最后登陆IP" align="center"></el-table-column>
      <el-table-column label="操作" align="center" width="360" v-if="userType == 0">
        <template slot-scope="scope">
          <el-button
            @click="resetPwd(scope.row)"
            type="primary"
            size="small"
            icon="el-icon-setting"
          >重置密码</el-button>
          <el-button @click="edit(scope.row)" type="warning" size="small" icon="el-icon-edit">更改类别</el-button>
          <el-button @click="delUser(scope.row)" type="danger" size="small" icon="el-icon-delete">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 添加用户对话框 -->
    <el-dialog title="添加用户" :visible.sync="addUserDialogFormVisible">
      <el-form :model="addUserForm" :rules="addUserRules" ref="addUserForm">
        <el-alert title="添加用户的默认密码为123456，用户登陆后可在后台修改" type="warning" style="margin-bottom:30px;"></el-alert>
        <el-form-item label="用户名" :label-width="formLabelWidth" prop="uname">
          <el-input v-model="addUserForm.uname" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="用户类别" :label-width="formLabelWidth" prop="utype">
          <el-select v-model="addUserForm.utype" placeholder="请选择用户类别">
            <el-option label="管理员" value="0"></el-option>
            <el-option label="普通用户" value="1"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addUserDialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="submitUser('addUserForm')">确 定</el-button>
      </div>
    </el-dialog>
    <EditUser ref="EditUser" @successEdit="loadUserList()"></EditUser>
  </div>
</template>

<script>
import { timestampToTime } from "../../assets/js/public";
import { getCookie, delCookie } from "../../assets/js/cookie.js";
import EditUser from "./EditUser";
export default {
  name: "userManage",
  components: {
    EditUser
  },
  data() {
    return {
      userType: getCookie("utype"),
      //用户列表数据
      userTableData: [],
      addUserDialogFormVisible: false,
      addUserForm: {
        uname: "",
        utype: "",
        type: "addUser"
      },
      addUserRules: {
        name: [{ required: true, message: "请输入用户名", trigger: "blur" }],
        utype: [
          { required: true, message: "请选择用户类型", trigger: "change" }
        ]
      },
      formLabelWidth: "120px"
    };
  },
  mounted() {
    this.loadUserList();
  },
  methods: {
    //加载用户
    loadUserList() {
      this.$Axios.get("/handleUser.php?type=loadUserList").then(res => {
        res.data.forEach(ele => {
          if (ele.utype == 0) {
            ele.utype = "管理员";
          } else {
            ele.utype = "普通用户";
          }

          if (!ele.last_login_time) {
            ele.last_login_time = "未登陆";
          } else {
            ele.last_login_time = timestampToTime(ele.last_login_time, true);
          }
          if (!ele.last_login_ip) {
            ele.last_login_ip = "未登陆";
          }
        });

        this.userTableData = res.data;
        // console.log(res.data);
      });
    },
    //提交用户
    submitUser(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.$Axios
            .post("/handleUser.php", this.$qs.stringify(this.addUserForm))
            .then(res => {
              if (res.data == "1") {
                this.$message({
                  message: "恭喜你，用户添加成功",
                  type: "success"
                });
                this.loadUserList();
              } else {
                this.$message.error("不成功，用户名已存在！");
              }
            });
        } else {
          return false;
        }
      });
    },
    //删除用户
    delUser(row) {
      this.$Axios
        .post(
          "/handleUser.php",
          this.$qs.stringify({
            type: "delUser",
            id: row.id
          })
        )
        .then(res => {
          if (res.data) {
            this.$message({
              message: "删除用户成功！",
              type: "success"
            });
            this.loadUserList();
          }
        });
    },
    //重置密码
    resetPwd(row) {
      this.$Axios
        .post(
          "/handleUser.php",
          this.$qs.stringify({
            type: "resetPwd",
            id: row.id
          })
        )
        .then(res => {
          if (res.data) {
            this.$message({
              message: "用户密码重置成功！",
              type: "success"
            });
          }
        });
    },
    edit(row) {
      this.$refs.EditUser.dialogVisible = true;
      this.$refs.EditUser.ruleForm.utype = row.utype == "管理员" ? "0" : "1";
      this.$refs.EditUser.ruleForm.uname = row.uname;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.wrap90 {
  width: 90%;
  margin: 30px auto;
}
</style>
