<template>
  <el-row :gutter="20" style="width:99%;margin:0px auto;margin-top:10px;margin-bottom:20px;">
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart" :style="{width: '30vw', height: '300px'}"></div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart2" :style="{width: '30vw', height: '300px'}">
          <h1>职级分布图</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart3" :style="{width: '30vw', height: '300px'}">
          <h1>学历分布图</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart4" :style="{width: '30vw', height: '300px'}">
          <h1>地域分布图</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart5" :style="{width: '30vw', height: '300px'}">
          <h1>年轻干部比例</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart6" :style="{width: '30vw', height: '300px'}">
          <h1>培训情况</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart7" :style="{width: '30vw', height: '300px'}">
          <h1>任职情况</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart8" :style="{width: '30vw', height: '300px'}">
          <h1>cz情况</h1>
        </div>
      </el-card>
    </el-col>
    <el-col :span="8">
      <el-card class="box-card">
        <div id="myChart9" :style="{width: '30vw', height: '300px'}">
          <h1>专业分布</h1>
        </div>
      </el-card>
    </el-col>
  </el-row>
  <!-- <div id="myChart" :style="{width: '300px', height: '300px'}"></div> -->
</template>

<script>
export default {
  name: "charts",
  data() {
    return {
      msg: "Welcome to Your Vue.js App"
    };
  },
  mounted() {
    this.drawLine();
  },
  methods: {
    drawLine() {
      // 基于准备好的dom，初始化echarts实例
      let myChart = this.$echarts.init(document.getElementById("myChart"));
      // 绘制图表
      myChart.setOption({
        title: { text: "年龄结构分布图" },
        tooltip: {},
        xAxis: {
          data: ["衬衫", "羊毛衫", "雪纺衫", "裤子", "高跟鞋", "袜子"]
        },
        yAxis: {},
        series: [
          {
            name: "销量",
            type: "bar",
            data: [5, 20, 36, 10, 10, 20]
          }
        ]
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.box-card {
  margin: 10px 0px;
}
</style>
