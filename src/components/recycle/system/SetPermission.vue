<template>
  <el-dialog
    :title="who"
    :visible.sync="$store.state.system.setPermissionDialogVisible"
    width="70%"
  >
    <el-row style="margin-bottom:30px;">
      <el-col :span="8">
        <el-select v-model="value" placeholder="选择模板快速设置" style="width:90%;">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-col>
      <el-col :span="8" style=" text-align:center;">
        <el-button type="primary" style="width:90%;">将当前选项保存为模板</el-button>
      </el-col>
      <el-col :span="8" style=" text-align:right;">
        <el-button type="primary" style="width:90%;" @click="saveUserPermission">保存用户权限</el-button>
      </el-col>
    </el-row>

    <el-table
      :data="permissionTableData"
      style="width: 100%;margin-bottom: 20px;"
      row-key="UUID"
      border
      default-expand-all
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55" align="center"></el-table-column>
      <el-table-column prop="TITLE" label="名称" width="240"></el-table-column>
      <el-table-column prop="DESCRIPTION" label="描述"></el-table-column>
    </el-table>
  </el-dialog>
</template>

<script>
import { uuid } from "../../assets/js/public";
export default {
  name: "SetPermission",
  data() {
    return {
      options: [
        {
          value: "选项1",
          label: "黄金糕"
        },
        {
          value: "选项2",
          label: "双皮奶"
        },
        {
          value: "选项3",
          label: "蚵仔煎"
        },
        {
          value: "选项4",
          label: "龙须面"
        },
        {
          value: "选项5",
          label: "北京烤鸭"
        }
      ],
      value: "",
      //权限列表数据
      permissionTableData: [],
      checkedPermission: []
    };
  },
  computed: {
    who() {
      return "配置用户权限——" + this.$store.state.system.setWho.姓名;
    }
  },
  mounted() {
    this.loadPermission();
  },
  methods: {
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
    handleSelectionChange(val) {
      this.checkedPermission = val;
    },
    saveUserPermission() {
      var sql = "INSERT ALL ";
      var permissions = this.checkedPermission;
      var userId = this.$store.state.system.setWho.UUID;
      // 给每个权限生成id
      permissions.forEach(ele => {
        var id = uuid();
        sql +=
          "INTO POOL.P_用户权限表  VALUES('" +
          id +
          "','" +
          userId +
          "','" +
          ele.UUID +
          "') ";
      });

      sql += "SELECT * FROM DUAL;";

      var form = {
        type: "saveUserPermission",
        // 用户id
        userId: userId,
        //需要插入的权限
        sql: sql
      };
      this.$Axios.post("/handlePermission", form).then(res => {
        console.log(res);
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
