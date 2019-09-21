<template>
  <el-main>
    <div style="width: 90%;margin: 20px auto;">
      <el-button
        type="primary"
        icon="el-icon-back"
        style="width: 100%;"
        @click="goBack()"
      >返&nbsp;&nbsp;&nbsp;&nbsp;回</el-button>
    </div>
    <el-card class="box-card" shadow="hover">
      <h2>{{info.title}}</h2>
      <div class="info">
        <span>作者：{{info.create_by}}</span>
        <span>日期：{{info.create_date}}</span>
        <span>类别：{{info.cat_str}}</span>
        <span>单位：{{info.department_str}}</span>
      </div>
      <div class="ql-container ql-core">
        <div class="ql-editor">
          <div v-html="info.content"></div>
        </div>
      </div>
    </el-card>
  </el-main>
</template>

<script>
import Editor from "../articleManage/Editor";
import { timestampToTime } from "../../assets/js/public";
import { getCookie } from "../../assets/js/cookie";
export default {
  name: "ShowArticle",
  data() {
    return {
      // 文章信息
      info: ""
    };
  },
  watch: {},
  components: {},
  mounted() {
    //加载文章信息
    this.loadArticle();
  },
  methods: {
    //加载文章信息
    loadArticle() {
      var id = this.$route.query.id;
      var str = "/handleArticle.php?id=" + id + "&type=loadArticle";
      this.$Axios.get(str).then(res => {
        var obj = res.data[0];
        var category = res.data[1];
        var department = res.data[2];
        obj.create_date = timestampToTime(obj.create_date, false);

        // 处理分类
        var catArr = obj.category_id.split(",");
        var cat_str = "";
        catArr.forEach(cat_ele => {
          category.forEach(item => {
            if (item.id == cat_ele) {
              cat_str += item.label + "/";
            }
          });
        });
        obj.cat_str = cat_str.substr(0, cat_str.length - 1);

        // 处理单位
        var departmentArr = obj.department_id.split(",");
        var department_str = "";
        departmentArr.forEach(department_ele => {
          department.forEach(item => {
            if (item.id == department_ele) {
              department_str += item.label + "/";
            }
          });
        });
        obj.department_str = department_str.substr(
          0,
          department_str.length - 1
        );

        this.info = obj;
        // console.log(this.info);
      });
    },

    //返回
    goBack() {
      this.$router.go(-1);
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.box-card {
  width: 90%;
  margin: 20px auto;
}
h2 {
  text-align: center;
  margin-bottom: 20px;
  margin-top: 10px;
}
.info {
  text-align: center;
  margin-bottom: 20px;
}
.info span {
  margin: 0px 10px;
}
.content {
  text-indent: 2em;
  text-align: justify;
  line-height: 30px;
}
.ql-align-center {
  text-align: center;
}
p.ql-align-justify {
  line-height: 30px;
}
p {
  display: block !important;
  margin: 0px auto !important;
}
</style>
