import Vue from 'vue'
import Router from 'vue-router'
import index from '@/components/index'
import login from '@/components/login'
import admin from '@/components/admin'
import help from '@/components/help'
import articleManage from '../components/articleManage/Index.vue'
import EditArticle from '../components/articleManage/EditArticle.vue'
import ShowArticle from '../components/articleManage/ShowArticle.vue'
import classManage from '../components/classManage/Index.vue'
import departmentManage from '../components/departmentManage/Index.vue'
import userManage from '../components/userManage/Index.vue'

Vue.use(Router)

export default new Router({
  routes: [{
      path: '/login',
      component: login
    },
    {
      path: '/admin',
      component: admin,
      children: [{
        path: '',
        component: index
      }, {
        path: 'articleManage',
        component: articleManage
      }, {
        path: 'editArticle',
        component: EditArticle
      }, {
        path: 'showArticle',
        component: ShowArticle
      }, {
        path: 'help',
        component: help
      }, {
        path: 'classManage',
        component: classManage
      }, {
        path: 'departmentManage',
        component: departmentManage
      }, {
        path: 'userManage',
        component: userManage
      }]
    }
  ]
})
