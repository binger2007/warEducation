<template>
  <el-card class="box-card">
    <div slot="header" class="header-wrap">
      <i class="el-icon-star-on"></i>
      <span>用户管理</span>
      <el-radio-group
        v-model="loadUserForm.isContainChildren"
        @change="loadUserList(1)"
        style="margin-left:30px;"
      >
        <el-radio :label="false">只显示本级</el-radio>
        <el-radio :label="true">包含子级</el-radio>
      </el-radio-group>
      <el-button
        class="adduser"
        type="primary"
        size="medium"
        icon="el-icon-plus"
        @click="openAddUserDialog()"
      >添加用户</el-button>
      <el-input placeholder="请输入姓名或身份号" v-model="loadUserForm.uname" class="searchName">
        <el-button slot="append" icon="el-icon-search" type="primary" @click="loadUserList(1)"></el-button>
      </el-input>
    </div>
    <el-table :data="userTableData" stripe v-loading="loadingUser">
      <el-table-column label="序号" type="index" width="80" align="center"></el-table-column>
      <el-table-column prop="姓名" label="姓名" align="center"></el-table-column>
      <el-table-column prop="单位名称" label="管理单位" align="center"></el-table-column>
      <el-table-column label="状态" align="center">
        <template slot-scope="scope">
          <el-switch
            style="display: block"
            v-model="scope.row.STATUS"
            @change="switchStatus(scope.row)"
          ></el-switch>
        </template>
      </el-table-column>
      <el-table-column label="最后一次时间" align="center">
        <template slot-scope="scope">
          <span v-if="scope">未登陆</span>
          <span v-else>{{scope}}</span>
        </template>
      </el-table-column>
      <el-table-column label="最后一次IP" align="center">
        <template slot-scope="scope">
          <span v-if="scope">未登陆</span>
          <span v-else>{{scope}}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="330" align="center">
        <template slot-scope="scope">
          <el-button
            type="success"
            size="small"
            icon="el-icon-setting"
            @click="permissionSetting(scope.row)"
          >权限设置</el-button>
          <el-button
            type="primary"
            size="small"
            icon="el-icon-refresh"
            @click="resetPassword(scope.row)"
          >重置密码</el-button>
          <el-button
            type="danger"
            size="small"
            @click="removeUser(scope.row)"
            style="margin-right:20px;"
          >移除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top:20px; text-align: center;">
      <el-pagination
        layout="prev, pager, next"
        :total="pagination_total"
        :page-size="rows"
        @current-change="pageindexchange"
      ></el-pagination>
    </div>
    <!-- 添加用户弹框 -->
    <el-dialog
      title="添加用户"
      @close="closeAddUserDialog()"
      :visible.sync="addUserDialogVisible"
      width="65%"
    >
      <PeopleList></PeopleList>
    </el-dialog>
    <!-- 权限设置对话框 -->
    <SetPermission></SetPermission>
  </el-card>
</template>

<script>
import PeopleList from "./PeopleList";
import SetPermission from "./SetPermission";
export default {
  name: "help",
  components: {
    PeopleList: PeopleList,
    SetPermission: SetPermission
  },
  watch: {
    //监听根节点变化
    "$store.state.rootDepartment": function() {
      this.loadUserList(1);
    }
  },
  data() {
    return {
      //添加用户的对话框是否显示
      addUserDialogVisible: false,
      //加载管理员列表的查询字段
      loadUserForm: {
        //通过姓名或身份号查询
        uname: "",
        type: "listUser",
        rootDepartment: "",
        isContainChildren: true, //是否包含子单位
        rows: 10
      },
      //分页参数
      rows: 10,
      pagination_total: 1,
      currentPage: 1,
      //显示加载中
      loadingUser: false,
      //管理员列表数据
      userTableData: []
    };
  },
  methods: {
    //加载用户
    loadUserList(page) {
      this.loadingUser = true;
      this.loadUserForm.page = page;
      this.loadUserForm.rootDepartment = this.$store.state.rootDepartment.机构名称码;
      this.$Axios.post("/handlePeople", this.loadUserForm).then(res => {
        res.data.rows.forEach(element => {
          if (element.STATUS == "0") {
            element.STATUS = false;
          } else {
            element.STATUS = true;
          }
        });
        this.userTableData = res.data.rows;
        this.pagination_total = res.data.total;
        this.loadingUser = false;
      });
    },
    //分页操作
    pageindexchange(currentPage) {
      this.loadUserList(currentPage);
      this.currentPage = currentPage;
    },
    //添加用户弹框
    openAddUserDialog() {
      //向对话框传递机构名称码
      this.addUserDialogVisible = true;
    },
    //关闭添加用户弹框
    closeAddUserDialog() {
      //刷新用户列表
      this.loadUserList(this.currentPage);
    },
    //禁用/启用用户
    switchStatus(row) {
      var status = row.STATUS ? 1 : 0;
      this.$Axios
        .post("/handlePeople", {
          uuid: row.UUID,
          status: status,
          type: "switchStatus"
        })
        .then(res => {
          if (res) {
            this.$message({
              message: "修改成功",
              type: "success"
            });
          }
        });
    },
    //权限设置
    permissionSetting(row) {
      console.log(row);
      this.$store.state.system.setPermissionDialogVisible = true;
      this.$store.state.system.setWho = row;
    },
    // 重置密码
    resetPassword(row) {
      this.$Axios
        .post("/handlePeople", {
          uuid: row.UUID,
          pwd: this.$md5("123456"),
          type: "resetPassword"
        })
        .then(res => {
          if (res) {
            this.$message({
              message: "重置密码成功，新密码为123456",
              type: "success"
            });
          }
        });
    },
    // 删除用户
    removeUser(row) {
      this.$Axios
        .post("/handlePeople", {
          uuid: row.UUID,
          type: "delUser"
        })
        .then(res => {
          if (res) {
            this.$message({
              message: "删除成功",
              type: "success"
            });
            //移出列表中的数据
            console.log(this.currentPage);
            this.loadUserList(this.currentPage);
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
  left: 350px;
}
.searchName {
  width: 300px;
  position: absolute;
  right: 0px;
  top: -11px;
}
</style>
