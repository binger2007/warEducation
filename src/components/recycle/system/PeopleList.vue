<template>
  <!-- 人员列表 有简单搜索功能 -->
  <el-row>
    <el-col :span="24">
      <el-form :inline="true" :model="searchForm" style="text-align: left;">
        <el-form-item style="color:#128075">
          <i class="el-icon-star-on"></i>
          当前单位：{{this.$store.state.rootDepartment.单位名称}}
        </el-form-item>
        <el-form-item style="float:right">
          <el-button type="primary" @click="search">查询</el-button>
        </el-form-item>
        <el-form-item label="部职别" style="float:right">
          <el-input v-model="searchForm.department" placeholder="部职别" style="width:200px;"></el-input>
        </el-form-item>
        <el-form-item label="姓名/身份号" style="float:right">
          <el-input v-model="searchForm.uname" placeholder="姓名或者身份号" style="width:150px;"></el-input>
        </el-form-item>
      </el-form>
    </el-col>
    <el-col :span="24">
      <el-table :data="listData.rows" stripe style="width: 100%;" v-loading="loadingPeople">
        <el-table-column prop="身份号" label="身份号" width="100px" align="center"></el-table-column>
        <el-table-column prop="姓名" label="姓名" width="100px" align="center"></el-table-column>
        <el-table-column prop="部别" label="部别" align="center"></el-table-column>
        <el-table-column label="操作" width="150px" align="center">
          <template slot-scope="scope">
            <el-button
              @click="handleSetUser(scope.row);"
              :disabled="scope.row.自定项A == 1"
              size="small"
              type="primary"
            >设为工作人员</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-pagination
        style="text-align:center"
        layout="prev, pager, next"
        :total="pagination_total"
        :page-size="rows"
        @current-change="pageindexchange"
      ></el-pagination>
    </el-col>
  </el-row>
</template>
<script>
import { uuid } from "../../assets/js/public.js";
export default {
  name: "PeopleList",
  data() {
    return {
      //搜索表单
      searchForm: {
        uname: "",
        department: "",
        type: "simpleSearch",
        rootDepartment: ""
      },
      //列表数据
      listData: [],
      //分页参数
      rows: 5,
      pagination_total: 100,
      //加载中
      loadingPeople: false,
      //添加用户表单数据
      setUserData: {}
    };
  },
  methods: {
    //初始加载的列表数据
    loadListData(page) {
      this.loadingPeople = true;
      var form = this.searchForm;
      form.page = page;
      form.rows = this.rows;
      this.$Axios.post("/handlePeople", this.searchForm).then(res => {
        this.listData = res.data;
        this.pagination_total = res.data.total;
        this.loadingPeople = false;
      });
    },
    // 搜索操作
    search() {
      if (this.searchForm.uname == "" && this.searchForm.department == "") {
        this.$message({
          message: "请输入搜索条件",
          type: "warning"
        });
      } else {
        this.loadingPeople = true;
        this.$Axios.post("/handlePeople", this.searchForm).then(res => {
          this.listData = res.data;
          this.pagination_total = res.data.total;
          this.loadingPeople = false;
        });
      }
    },
    //分页操作
    pageindexchange(currentPage) {
      this.loadListData(currentPage);
    },
    //设为工作人员操作
    handleSetUser(row) {
      this.setUserData.sfh = row.身份号;
      this.setUserData.createUserId = "0"; //谁创建的，应该从cookie中获得登陆用户的身份号
      this.setUserData.createUserTime = new Date().valueOf();
      this.setUserData.pwd = this.$md5("123456"); //初始密码设为123456
      this.setUserData.rootDepartmentId = this.$store.state.rootDepartment.机构名称码;
      this.setUserData.type = "addUser";
      this.setUserData.id = uuid();
      this.$Axios.post("/handlePeople", this.setUserData).then(res => {
        if (res) {
          this.$message({
            message: "添加用户成功！",
            type: "success"
          });
          //禁用当前按钮，防止反复添加
          row.自定项A = 1;
        }
      });
    }
  },
  computed: {},
  watch: {
    //监听根节点变化
    "$store.state.rootDepartment": function() {
      this.searchForm.rootDepartment = this.$store.state.rootDepartment.机构名称码;
      //清除搜索时遗留下的痕迹
      this.searchForm.uname = "";
      this.searchForm.department = "";
      this.loadListData(1);
    }
  },
  mounted() {
    this.searchForm.rootDepartment = this.$store.state.rootDepartment.机构名称码;
    this.loadListData(1);
  }
};
</script>
<style scoped>
</style>


