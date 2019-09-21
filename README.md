# pool

> A Vue.js project

## Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# build for production and view the bundle analyzer report
npm run build --report

# run unit tests
npm run unit

# run all tests
npm test
```

For a detailed explanation on how things work, check out the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).
# warEducation

最近单位要求做的动员案系统，源码发到网上了。
技术框架：php+vue+element+sqlite数据库
基本功能的功能：
1、可以自由的添加动员案的分类和单位分类，通过拖拽可以更改分类的顺序和父子结构；
2、基本的权限控制。分为超级管理员和普通管理员2种角色，普通管理员能够浏览所有的动员案，但是不能修改其他用户创建的动员案，只能修改和删除自己创建的动员案。
3、通过单位、动员案分类和关键字快速进行检索
4、自由超级管理员和普通管理员，创建用户的默认密码为123456
demo地址：
http://www.aikongzhiqi.com/war.html
用户名：admin 密码：admin
github地址：
https://github.com/binger2007/warEducation.git
dist包下载地址：
http://www.aikongzhiqi.com/dist.rar
部署方法：
部署方法特别简单，只需要将dist文件夹里的文件拷贝到php服务器的根目录即可。
注意：php的版本号是5.4.45，其他版本号没有进行测试，需要打开php服务器对sqlite3的支持，我用的是phpstudy集成包，默认已经打开。
之所以用sqlite数据库，主要考虑到方便程序向单机部署，领导的要求是每个基层主官的电脑都要布置一份单机版程序，方便携带，不用联网即可查看动员案。
下部打算：
此版本为1.0版本，仅实现了基本功能，后期根据需要，继续开发以下功能。
1、封装成app,可以部署到平板或者手持设备上，方便各级主管使用；
2、实现不同用户的数据导入、导出、备份功能。
3、领导要求的其他功能。
本程序基本实现了后台管理的基本功能，包括内容的增删改查、用户管理、权限管理，在此基础上进一步开发各种功能。
