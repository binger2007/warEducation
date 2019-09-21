<template>
  <div class="wrap90">
    <el-card class="box-card" shadow="hover">
      <div slot="header" class="clearfix">
        <h3 style="text-align: center;">
          <i class="el-icon-departyingyongguanli"></i>
          入池标准
        </h3>
      </div>
      <div>入池标准为创建该人才池时填写的。可以在人才池配置中修改</div>
    </el-card>
    <el-card class="box-card" shadow="hover" style="margin-top:30px;">
      <div slot="header" class="clearfix">
        <h3 style="text-align: center;">
          <i class="el-icon-departyingyongguanli"></i>
          {{$route.query.title}}概况
        </h3>
      </div>
      <div>目前{{$route.query.title}}池中共有1290人，平均年龄为32.5岁,年轻干部比例为32%。。。。。。。。</div>
    </el-card>
    <el-card class="box-card" shadow="hover" style="margin-top:30px;">
      <div slot="header" class="clearfix">
        <h3 style="text-align: center;">
          <i class="el-icon-departyingyongguanli"></i>
          子池管理
        </h3>
      </div>
      <ul class="list-img">
        <li v-for="item in sonPools">
          <router-link :to="{path:'admin/poolindex',query:{title:item.title,showNavFlag:true}}">
            <img :src="item.img" :alt="item.title" />
            {{ item.title }}
          </router-link>
        </li>
        <li class="plus">
          <a>
            <i class="el-icon-plus" @click="showAddPoolDialog('son',$route.query.poolId)"></i>
            <!-- <i
              class="el-icon-plus"
              @click="$emit('showAddPoolDialog',{type:'son',id:$route.query.poolId})"
            ></i>-->
          </a>
        </li>
      </ul>
    </el-card>
    <AddPool></AddPool>
  </div>
</template>

<script>
import AddPool from "./AddPool";
import { baseUrl } from "../assets/js/public";
export default {
  name: "poolindex",
  data() {
    return {
      //自定义才池
      sonPools: [
        {
          img: baseUrl + "/__kb/kfile/7.png",
          title: "子人才池",
          id: 3
        }
      ]
    };
  },
  components: {
    AddPool: AddPool
  },
  methods: {
    showAddPoolDialog(type, id) {
      this.$store.commit("changeAddPoolDialogVisible", {
        type: type,
        id: id
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.wrap90 {
  margin: 30px auto;
}
</style>
