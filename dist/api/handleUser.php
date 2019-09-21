<?php
//引入数据库连接字段
require "dbConfig.php";
$type =  $_REQUEST['type'];

switch($type){
    //添加用户
    case 'addUser':
        $pwd = md5("123456");
        $uname = $_REQUEST["uname"];
        $utype = $_REQUEST["utype"];
        $uuid = uuid();      
        //首先判断用户名是否已经存在
        $sql = "INSERT INTO 
                users (
                    id,
                    uname, 
                    pwd, 
                    utype
                ) VALUES (
                    '$uuid',
                    '$uname', 
                    '$pwd', 
                    '$utype'
                    )";
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //加载用户列表
    case 'loadUserList':
        $sql = "SELECT * FROM users";
        loadListData($db,$sql);        
        break;

        //删除用户
    case 'delUser':
        $id=$_REQUEST['id'];
        $sql = "DELETE  FROM users WHERE id = '$id'";
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //重置密码
    case 'resetPwd':
        $id=$_REQUEST['id'];
        $pwd = md5("123456");
        $sql = "UPDATE  users SET pwd = '$pwd'  WHERE id = '$id'";
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //登陆验证
    case 'checkLogin':
        $userName=$_REQUEST['userName'];
        $pwd=md5($_REQUEST['pass']);
        $time = time();   
        $ip = getRemoteIp();      
        // 首先判断用户和密码是否正确
        $sql = "SELECT * FROM users WHERE uname = '$userName' AND pwd = '$pwd'";
        $ret = $db->query($sql);
        $results=array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $results[]=$row;
        }
        $num = count($results);
        if($num){
            // 更新用户登陆时间
            $sql = "UPDATE  users SET last_login_time = '$time' , last_login_ip = '$ip'  WHERE uname = '$userName'";
            $ret = $db->exec($sql);
            if(!$ret){
                echo false;
            } else {
                echo json_encode($results);
            }
        }else{
            echo false;
        }        
        $db->close();
        break;

        //更改密码
    case 'changePass':
        $uname=$_REQUEST['uname'];
        $pwd = $_REQUEST['pass'];
        $sql = "UPDATE  users SET pwd = '$pwd'  WHERE uname = '$uname'";
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //更改用户类型
    case 'changeType':
        $uname=$_REQUEST['uname'];
        $utype = $_REQUEST['utype'];
        $sql = "UPDATE  users SET utype = '$utype'  WHERE uname = '$uname'";        
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;
}


?>