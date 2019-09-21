<template>
  <!-- 后台管理首页 -->
  <el-main>
    <!-- <div class="sidebar"> -->
    <!-- <el-tree
        class="side_tree"
        highlight-current
        :data="treeData"
        node-key="id"
        default-expand-all
        :expand-on-click-node="false"
      >
        <span class="custom-tree-node" slot-scope="{ node}">
          <span @click="handleChangeDepartment(node)">{{ node.label }}</span>
        </span>
    </el-tree>-->
    <el-menu
      default-active="2"
      class="el-menu-vertical-demo sidebar"
      @open="handleOpen"
      @select="handleSelect"
      @close="handleClose"
      background-color="#545c64"
      text-color="#fff"
      unique-opened
      active-text-color="#ffd04b"
    >
      <h3 class="title">选择单位</h3>
      <Accordion :data="this.treeData"></Accordion>
    </el-menu>
    <!-- </div> -->
    <div class="main_wrapper">
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="选择类别">
          <el-cascader
            :options="classData"
            :props="{ checkStrictly: true }"
            @change="handleChangeClass"
          ></el-cascader>
        </el-form-item>
        <!-- <el-form-item label="选择单位">
          <el-cascader
            :options="departmentData"
            :props="{ checkStrictly: true }"
            @change="handleChangeDepartment"
          ></el-cascader>
        </el-form-item>-->
        <el-form-item label="关键字">
          <el-input v-model="formInline.keyword" placeholder="关键字"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button icon="el-icon-search" type="primary" @click="onSubmit">查询</el-button>
        </el-form-item>
        <el-form-item>
          <el-button icon="el-icon-refresh" type="warning" @click="refresh">重置</el-button>
        </el-form-item>
      </el-form>
      <el-table
        :data="listData"
        stripe
        style="width: 100%"
        :default-sort="{prop: 'date', order: 'descending'}"
        @sort-change="handleSort"
      >
        <el-table-column type="index" width="50" align="center"></el-table-column>
        <el-table-column
          prop="title"
          sortable
          label="标题"
          :sort-orders="sortOrders"
          show-overflow-tooltip
        ></el-table-column>
        <el-table-column
          label="类别"
          sortable
          prop="category_id"
          :sort-orders="sortOrders"
          show-overflow-tooltip
        >
          <template slot-scope="scope">{{scope.row.cat_str}}</template>
        </el-table-column>
        <el-table-column
          label="单位"
          sortable
          :sort-orders="sortOrders"
          prop="department_id"
          align="center"
          show-overflow-tooltip
        >
          <template slot-scope="scope">{{scope.row.department_str}}</template>
        </el-table-column>
        <el-table-column
          prop="create_date"
          sortable
          :sort-orders="sortOrders"
          label="日期"
          align="center"
          width="150"
        ></el-table-column>
        <el-table-column
          prop="create_by"
          label="作者"
          sortable
          :sort-orders="sortOrders"
          align="center"
          width="100"
        ></el-table-column>
        <el-table-column label="操作" width="280">
          <template slot-scope="scope">
            <el-button
              @click="showArticle(scope.row)"
              type="primary"
              size="small"
              icon="el-icon-star-off"
            >查看</el-button>
            <el-button
              v-if="userType == '0' || scope.row.create_by == uname"
              @click="editArticle(scope.row)"
              type="warning"
              size="small"
              icon="el-icon-edit"
            >编辑</el-button>
            <el-button
              v-if="userType == '0' || scope.row.create_by == uname"
              @click="delArticle(scope.row)"
              type="danger"
              size="small"
              icon="el-icon-delete"
            >删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        @current-change="handleCurrentChange"
        style="margin-top:30px;"
        :page-size="formInline.pageSize"
      ></el-pagination>
    </div>
    <!-- -->
  </el-main>
</template>

<script>
import { generateOptions, timestampToTime } from "../assets/js/public";
import { getCookie } from "../assets/js/cookie.js";
import Accordion from "../components/Accordion";

export default {
  name: "index",
  inject: ["reload"],
  data() {
    return {
      userType: getCookie("utype"),
      uname: getCookie("userName"),
      // 级联分类数据
      classData: [],
      departmentData: [],
      formInline: {
        category: "",
        department: "",
        keyword: "",
        type: "list",
        pageNo: 1,
        pageSize: 10,
        sortBy: "weight",
        sortOrder: "DESC"
      },
      //列表数据
      listData: [],
      total: 0,
      treeData: [],
      sortOrders: ["ascending", "descending"]
    };
  },
  mounted() {
    this.loadClass();
    this.loadDepartment();
    this.loadList();
  },
  components: {
    Accordion
  },
  methods: {
    //加载分类信息
    loadClass() {
      this.$Axios.get("/handleClass.php?type=loadClass").then(res => {
        this.classData = generateOptions(res.data);
        // console.log(this.classData);
      });
    },
    //加载分类信息
    loadDepartment() {
      this.$Axios.get("/handleDepartment.php?type=loadClass").then(res => {
        this.departmentData = generateOptions(res.data);
        this.treeData = generateOptions(res.data);
        console.log(this.treeData);
      });
    },
    handleChangeClass(value) {
      // console.log(value);
      // var str = "";
      // value.forEach(ele => {
      //   str += ele + ",";
      // });
      // this.formInline.category = str.substr(0, str.length - 1);
      this.formInline.category = value[value.length - 1];
    },
    handleChangeDepartment(value) {
      // console.log(value);
      this.formInline.category = "";
      this.formInline.keyword = "";
      this.formInline.department = value.data.id;
      this.loadList();
      // var str = "";
      // value.forEach(ele => {
      //   str += ele + ",";
      // });
      // console.log(str.substr(0, str.length - 1));
      // this.formInline.department = str.substr(0, str.length - 1);
    },
    loadList() {
      this.$Axios
        .post("/handleArticle.php", this.$qs.stringify(this.formInline))
        .then(res => {
          var list = res.data[1];
          var category = res.data[2];
          var department = res.data[3];
          list.forEach(ele => {
            //处理时间
            ele.create_date = timestampToTime(ele.create_date, false);
            // 处理分类
            var catArr = ele.category_id.split(",");
            var cat_str = "";
            catArr.forEach(cat_ele => {
              category.forEach(item => {
                if (item.id == cat_ele) {
                  cat_str += "【" + item.label + "】/";
                }
              });
            });
            ele.cat_str = cat_str.substr(0, cat_str.length - 1);

            // 处理单位
            var departmentArr = ele.department_id.split(",");
            var department_str = "";
            departmentArr.forEach(department_ele => {
              department.forEach(item => {
                if (item.id == department_ele) {
                  department_str += "【" + item.label + "】/";
                }
              });
            });
            ele.department_str = department_str.substr(
              0,
              department_str.length - 1
            );
          });
          this.listData = list;
          this.total = res.data[0].total;
        });
    },
    onSubmit() {
      this.formInline.pageNo = 1;
      this.formInline.department = "";
      this.loadList();
    },
    //重置
    refresh() {
      this.reload();
    },
    showArticle(row) {
      // console.log(row);
      this.$router.push({
        path: "/admin/showArticle",
        query: { id: row._id }
      });
    },
    editArticle(row) {
      this.$router.push({
        path: "/admin/editArticle",
        query: { id: row._id }
      });
    },
    delArticle(row) {
      this.$Axios
        .post(
          "/handleArticle.php",
          this.$qs.stringify({
            type: "delArticle",
            id: row._id
          })
        )
        .then(res => {
          if (res.data) {
            this.$message({
              message: "删除成功",
              type: "success"
            });
            this.loadList();
          }
        });
    },
    handleCurrentChange(val) {
      this.formInline.pageNo = val;
      this.loadList();
    },

    handleOpen(key, keyPath) {
      this.formInline.department = key;
      this.loadList();
    },
    handleClose(key, keyPath) {
      this.formInline.department = key;
      this.loadList();
    },
    handleSelect(key, keyPath) {
      // console.log(key, keyPath);
      this.formInline.department = key;
      this.loadList();
    },
    //处理排序
    handleSort(val) {
      console.log(val);
      this.formInline.sortBy = val.prop;
      this.formInline.sortOrder = val.order == "ascending" ? "ASC" : "DESC";
      this.loadList();
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
/* .box-card {
  width: 95%;
  margin: 30px auto;
} */
.demo-form-inline {
  text-align: center;
}
.sidebar {
  z-index: 1000;
  background-color: #545c64;
  width: 220px;
  height: calc(100vh - 131px);
  overflow: auto;
  position: fixed;
  left: 0px;
  top: 101px;
  /* background-color: rgb(217, 236, 255); */
}
.sidebar .title {
  text-align: center;
  margin-top: 20px;
  margin-bottom: 10px;
  color: #fff;
}
.main_wrapper {
  /* background-color: aquamarine; */
  margin-left: 220px;
  padding: 30px;
}
.side_tree {
  margin-top: 20px;

  background-color: #545c64;
}
</style>
