<template>
  <!-- 弹框 -->
  <el-dialog
    :title="'添加'+addPoolDialogTitle+'人才池'"
    :visible.sync="$store.state.poolManage.addPoolDialogVisible"
    width="50%"
  >
    <el-form
      :model="ruleAddPoolForm"
      :rules="addPoolrules"
      ref="ruleAddPoolForm"
      label-width="100px"
    >
      <el-form-item label="名称" prop="title">
        <el-input v-model="ruleAddPoolForm.title"></el-input>
      </el-form-item>
      <el-form-item label="入池标准" prop="biaozhun">
        <el-input type="textarea" v-model="ruleAddPoolForm.biaozhun"></el-input>
      </el-form-item>
      <el-form-item label="选择主题" prop="color">
        <el-color-picker v-model="ruleAddPoolForm.color" show-alpha :predefine="predefineColors"></el-color-picker>
      </el-form-item>
      <el-form-item label="图标" prop="icon">
        <el-upload
          class="avatar-uploader"
          :action="baseUrl+'/receiveFile?type=uploadPic'"
          :show-file-list="false"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <img v-if="imageUrl" :src="imageUrl" class="avatar" />
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="resetForm('ruleAddPoolForm')">取 消</el-button>
      <el-button type="primary" @click="submitForm('ruleAddPoolForm')">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { baseUrl } from "../assets/js/public";
import { getCookie } from "../assets/js/cookie.js";
export default {
  name: "addPool",
  data() {
    return {
      addPoolDialogVisible: false,
      // addPoolDialogTitle: "",
      baseUrl: baseUrl,
      imageUrl: "",
      ruleAddPoolForm: {
        title: "",
        icon: "",
        biaozhun: "",
        color: "#128075",
        user: getCookie("userName"), //获取cookie中的username,后面需要改为uid
        type: "",
        creatTime: new Date().valueOf(),
        fid: "",
        postType: "savePool"
      },
      //预定义颜色
      predefineColors: [
        "#128075",
        "#ff4500",
        "#ff8c00",
        "#ffd700",
        "#90ee90",
        "#00ced1",
        "#1e90ff",
        "#c71585",
        "rgba(255, 69, 0, 0.68)",
        "rgb(255, 120, 0)",
        "hsv(51, 100, 98)",
        "hsva(120, 40, 94, 0.5)",
        "hsl(181, 100%, 37%)",
        "hsla(209, 100%, 56%, 0.73)"
      ],
      addPoolrules: {
        title: [
          {
            id: 1,
            required: true,
            message: "请输入人才池名称",
            trigger: "blur"
          }
        ],
        biaozhun: [
          { required: true, message: "请输入人才池的入池标准", trigger: "blur" }
        ]
      }
    };
  },
  computed: {
    addPoolDialogTitle() {
      if (this.$store.state.poolManage.addPoolArgs.type == "standard") {
        return "标准";
      } else if (this.$store.state.poolManage.addPoolArgs.type == "diy") {
        return "自定义";
      } else if (this.$store.state.poolManage.addPoolArgs.type == "son") {
        return "子";
      }
    }
  },
  watch: {
    //监听根节点变化
    "$store.state.poolManage.addPoolArgs": function() {
      this.ruleAddPoolForm.fid = this.$store.state.poolManage.addPoolArgs.id;
      this.ruleAddPoolForm.type = this.$store.state.poolManage.addPoolArgs.type;
      //初始化表单输入框
      this.ruleAddPoolForm.title = "";
      this.ruleAddPoolForm.biaozhun = "";
      this.ruleAddPoolForm.icon = "";
      this.ruleAddPoolForm.color = "#128075";
      this.ruleAddPoolForm.user = getCookie("userName");
      this.ruleAddPoolForm.creatTime = new Date().valueOf();
    }
  },
  mounted() {},
  beforeDestroy() {},
  methods: {
    //处理上传图片
    handleAvatarSuccess(res, file) {
      this.imageUrl = URL.createObjectURL(file.raw);
      this.ruleAddPoolForm.icon = this.baseUrl + file.response.RelativeUrl;
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;
      if (!isJPG) {
        this.$message.error("上传头像图片只能是 JPG 或 PNG 格式!");
      }
      if (!isLt2M) {
        this.$message.error("上传头像图片大小不能超过 2MB!");
      }
      return isJPG && isLt2M;
    },

    submitForm(formName) {
      var that = this;
      this.$refs[formName].validate(valid => {
        // debugger;
        if (valid) {
          console.log(this.ruleAddPoolForm);
          // this.$jsonp(
          //   "http://cmi.gangwaninfo.com:9999/index_crud",
          //   that.ruleAddPoolForm
          // ).then(res => {});
        } else {
          console.log("error submit!!");
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
</style>
