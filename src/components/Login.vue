<template>
  <div class="wrapper">
    <div class="title"></div>
    <div class="login_box">
      <span class="welcome">欢迎登陆</span>
      <div class="form_box">
        <el-form
          :model="loginRuleForm"
          status-icon
          :rules="loginRules"
          ref="loginRuleForm"
          label-width="100px"
        >
          <el-form-item prop="userName">
            <el-input v-model="loginRuleForm.userName" placeholder="请输入用户名"></el-input>
          </el-form-item>
          <el-form-item prop="pass">
            <el-input type="password" v-model="loginRuleForm.pass" placeholder="请输入密码"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('loginRuleForm')">登陆</el-button>
            <el-button @click="resetForm('loginRuleForm')">重置</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <!-- <el-row :gutter="20">
      <el-col :span="12" :offset="6">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>用户登陆</span>
          </div>
          <el-form
            :model="loginRuleForm"
            status-icon
            :rules="loginRules"
            ref="loginRuleForm"
            label-width="100px"
          >
            <el-form-item label="用户名" prop="userName">
              <el-input v-model="loginRuleForm.userName"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="pass">
              <el-input type="password" v-model="loginRuleForm.pass"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="submitForm('loginRuleForm')">登陆</el-button>
              <el-button @click="resetForm('loginRuleForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>
    </el-row>-->
  </div>
</template>
<script>
import { setCookie, getCookie } from "../assets/js/cookie.js";
export default {
  name: "login",
  data() {
    return {
      loginRuleForm: {
        userName: "",
        pass: "",
        type: "checkLogin"
      },
      loginRules: {
        userName: [
          { required: true, message: "请输入用户名", trigger: "blur" }
        ],
        pass: [{ required: true, message: "请输入密码", trigger: "blur" }]
      }
    };
  },
  mounted() {},
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.$Axios
            .post("/handleUser.php", this.$qs.stringify(this.loginRuleForm))
            .then(res => {
              if (!res.data) {
                this.$message({
                  message: "登陆失败，用户或密码错误！",
                  type: "warning"
                });
              } else {
                setCookie("userName", this.loginRuleForm.userName, 1000 * 60);
                setCookie("utype", res.data[0].utype, 1000 * 60);
                this.$router.push("/admin");
              }
            });
        } else {
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.wrapper {
  background: url("../assets/img/login-bg.05b6748.png") no-repeat;
  width: 100vw;
  height: 100vh;
  overflow: hidden;

  background-size: cover;
  /* margin: 0px auto;
  margin-top: 30vh; */
}
.login_box {
  position: relative;
  width: 491px;
  height: 372px;
  background: url("../assets/img/login_bg.png") no-repeat;
  margin-left: calc(50vw - 245px);
  margin-top: 30px;
}
.title {
  width: 489px;
  height: 69px;
  background: url("../assets/img/new_logo.png") no-repeat;
  margin-left: calc(50vw - 245px);
  margin-top: calc(20vh);
}
.form_box {
  width: 94%;
  position: absolute;
  left: -35px;
  top: 126px;
}
span.welcome {
  position: absolute;
  left: 205px;
  top: 30px;

  font-size: 21px;
  font-family: MicrosoftYaHei;
  font-weight: 400;
  color: #fff;

  background: linear-gradient(0deg, rgba(77, 157, 255, 0.95), #e5f0ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>
