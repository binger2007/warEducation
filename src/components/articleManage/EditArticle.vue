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
      <el-form
        :model="ruleForm"
        :rules="rules"
        ref="ruleForm"
        label-width="100px"
        class="demo-ruleForm"
      >
        <el-form-item label="标题" prop="title">
          <el-input v-model="ruleForm.title"></el-input>
        </el-form-item>
        <el-form-item label="选择类别" prop="category">
          <el-cascader
            :options="classData"
            :props="{ checkStrictly: true }"
            @change="handleChangeClass"
          ></el-cascader>
          <span style="color:#F56C6C;margin-left:10px;">注：不更改类别的话留空即可</span>
        </el-form-item>

        <el-form-item label="选择单位" prop="department">
          <el-cascader
            :options="departmentData"
            :props="{ checkStrictly: true }"
            @change="handleChangeDepartment"
          ></el-cascader>
          <span style="color:#F56C6C;margin-left:10px;">注：不更改单位的话留空即可</span>
        </el-form-item>

        <el-form-item label="添加时间">
          <el-date-picker
            value-format="timestamp"
            type="date"
            placeholder="选择日期"
            v-model="ruleForm.date"
          ></el-date-picker>
        </el-form-item>
        <el-form-item label="权重">
          <el-input-number v-model="ruleForm.weight" :min="1" :max="10" label="描述文字"></el-input-number>
        </el-form-item>
        <el-form-item label="简介">
          <el-input type="textarea" v-model="ruleForm.desc"></el-input>
        </el-form-item>
        <el-form-item label="内容" prop="content">
          <Editor></Editor>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('ruleForm')">保存</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </el-main>
</template>

<script>
import Editor from "../articleManage/Editor";
import { generateOptions } from "../../assets/js/public";
import { getCookie } from "../../assets/js/cookie";
export default {
  name: "EditArticle",
  data() {
    return {
      // 级联分类数据
      classData: [],
      departmentData: [],
      ruleForm: {
        title: "",
        category: "",
        department: "",
        date: "",
        desc: "",
        content: "",
        userName: getCookie("userName"),
        weight: 1,
        type: "editArticle",
        id: this.$route.query.id
      },
      rules: {
        title: [{ required: true, message: "请输入标题", trigger: "blur" }],
        content: [{ required: true, message: "请填写内容", trigger: "blur" }]
      }
    };
  },
  watch: {
    "$store.state.content": function() {
      this.ruleForm.content = this.$store.state.content;
    }
  },
  components: {
    Editor: Editor
  },
  mounted() {
    //加载文章信息
    this.loadArticle();
    this.loadClass();
    this.loadDepartment();
  },
  methods: {
    //加载文章信息
    loadArticle() {
      var id = this.$route.query.id;
      var str = "/handleArticle.php?id=" + id + "&type=loadArticle";
      this.$Axios.get(str).then(res => {
        var obj = res.data[0];
        this.ruleForm.title = obj.title;
        this.ruleForm.category = obj.category_id;
        this.ruleForm.department = obj.department_id;
        this.ruleForm.desc = obj.description;
        this.ruleForm.weight = obj.weight;
        this.ruleForm.date = obj.create_date;
        this.ruleForm.content = obj.content;
        this.ruleForm.title = obj.title;
        this.$store.state.content = obj.content;
      });
    },
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
      });
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (!this.ruleForm.date) {
            this.ruleForm.date = new Date().valueOf();
          }
          this.$Axios
            .post("/handleArticle.php", this.$qs.stringify(this.ruleForm))
            .then(res => {
              if (res.data == "1") {
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
              } else {
                this.$message.error("不成功，请重新输入！");
              }
            });
        } else {
          return false;
        }
      });
    },
    handleChangeClass(value) {
      this.ruleForm.category = value[value.length - 1];
    },
    handleChangeDepartment(value) {
      this.ruleForm.department = value[value.length - 1];
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
</style>
