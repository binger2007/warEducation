<template>
  <div>
    <div v-for="(item,index) in data" :key="index">
      <el-submenu :index="item.id" v-if="item.children && item.children.length > 0">
        <template slot="title">
          <!-- <i class="el-icon-menu"></i> -->
          <span>{{item.label}}</span>
        </template>
        <el-menu-item-group>
          <Accordion :data="item.children"></Accordion>
        </el-menu-item-group>
      </el-submenu>
      <el-menu-item :index="item.id" v-else>
        <!-- <i class="el-icon-menu"></i> -->
        <span slot="title">{{item.label}}</span>
      </el-menu-item>
    </div>
  </div>
</template>
<script>
export default {
  name: "Accordion",
  props: ["data"]
};
</script>
 
<style scoped>
</style>
