<?php
//引入数据库连接字段
require "dbConfig.php";
$type =  $_REQUEST['type'];

switch($type){
   
    //加载用户列表
    case 'loadClass':
        $sql = "SELECT * FROM department ORDER BY sortNum ASC";
        loadListData($db,$sql);        
        break;

    //添加分类
    case 'addClass':       
        $cname = $_REQUEST["cname"];
        $pid = $_REQUEST["pid"];
        $pids = $_REQUEST["pids"];
        $time = time();  
        $uuid=uuid(); 
        //首先判断用户名是否已经存在
        $sql = "INSERT INTO 
                department (
                    id,
                    p_id, 
                    p_ids, 
                    label,
                    createTime)
                VALUES (
                    '$uuid',
                    '$pid', 
                    '$pids', 
                    '$cname',
                    '$time'
                    )";
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

    //删除分类
    case 'delClass':
        $id=$_REQUEST['id'];
        $pids = $_REQUEST['pids'].",".$id;
        $sql = "DELETE  FROM department WHERE id = '$id' OR p_ids LIKE '$pids%'";       
        $ret = $db->exec($sql);
        //后期还得删除栏目下面的文章
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //处理排序
    case 'handleSort':
        $sql=$_REQUEST['sql'];         
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