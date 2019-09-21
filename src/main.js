// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import ElementUI from 'element-ui'
// import 'element-ui/lib/theme-chalk/index.css'
import './assets/style0/theme/index.css'
import App from './App'
import router from './router'
import Axios from 'axios'
import qs from 'qs'
import './assets/icon/eleDepartmentIcon/iconfont.css'
import './assets/icon/eleThirdIcon/iconfont.css'
import echarts from 'echarts'
import md5 from 'js-md5';
import {
  showLoading,
  hideLoading
} from './assets/js/loading';



import store from './store'
Vue.prototype.$echarts = echarts
Vue.prototype.$md5 = md5;

Vue.config.productionTip = false

Vue.prototype.$Axios = Axios
Vue.prototype.$qs = qs
Axios.defaults.baseURL = '/api'
Axios.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded';
/* 请求拦截器（请求之前的操作） */
Axios.interceptors.request.use((req) => {
  showLoading();
  return req;
}, err => Promise.reject(err));

/* 请求之后的操作 */
Axios.interceptors.response.use((res) => {
  hideLoading();

  return res;

  // return Promise.reject(res);
}, (err) => {
  hideLoading();
  alert("请求超时！");
  return Promise.reject(err);
});
Vue.use(ElementUI)
new Vue({
  el: '#app',
  store,
  router,
  components: {
    App
  },
  template: '<App/>'
})
