<template>
  <el-dialog title="更改用户类别" :visible.sync="dialogVisible">
    <el-form :model="ruleForm" status-icon ref="ruleForm" label-width="100px" class="demo-ruleForm">
      <el-form-item label="选择用户类型" prop="pass">
        <el-select style="width:80%" v-model="ruleForm.utype" placeholder="请选择">
          <el-option label="管理员" value="0"></el-option>
          <el-option label="普通用户" value="1"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm('ruleForm')">提交</el-button>
      </el-form-item>
    </el-form>
  </el-dialog>
</template>

<script>
export default {
  name: "EditUser",
  data() {
    return {
      dialogVisible: false,
      ruleForm: {
        utype: "",
        type: "changeType",
        uname: ""
      }
    };
  },
  methods: {
    submitForm(formName) {
      this.$Axios
        .post("/handleUser.php", this.$qs.stringify(this.ruleForm))
        .then(res => {
          if (res.data == "1") {
            this.$message({
              message: "修改用户类型成功",
              type: "success"
            });
            this.dialogVisible = false;
            this.$emit("successEdit");
          } else {
            this.$message.error("不成功，请重新修改！");
          }
        });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
