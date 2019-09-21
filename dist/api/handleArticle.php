<?php
//引入数据库连接字段
require "dbConfig.php";
$type =  $_REQUEST['type'];

switch($type){
   
    //加载用户列表
    case 'list':    
        $keyword = $_REQUEST["keyword"];
        $category = $_REQUEST["category"];
        $department = $_REQUEST["department"];
        $pageNo = $_REQUEST["pageNo"];
        $pageSize = $_REQUEST["pageSize"];
        $sortBy = $_REQUEST["sortBy"];
        $sortOrder = $_REQUEST["sortOrder"];
        //主查询
        $sql = "SELECT _id,title,category_id,department_id,create_by,create_date,weight FROM article WHERE del_flag = 0";
        //查询总数量
        $sql_count = "SELECT COUNT(_id) AS total FROM article WHERE del_flag = 0";
        if($department != ''){
            $sql.=" AND department_id = '$department' OR department_id IN (SELECT id FROM department WHERE p_ids LIKE '%$department%')";
            $sql_count.=" AND department_id = '$department' OR department_id IN (SELECT id FROM department WHERE p_ids LIKE '%$department%')";
        }
        if($category != ''){
            $sql.=" AND category_id = '$category' OR category_id IN (SELECT id FROM category WHERE p_ids LIKE '%$category%')";
            $sql_count.=" AND category_id = '$category' OR category_id IN (SELECT id FROM category WHERE p_ids LIKE '%$category%')";

        }
        if($keyword != ''){
            $sql.=" AND (title LIKE '%$keyword%' OR description LIKE '%$keyword%' OR content LIKE '%$keyword%')";
            $sql_count.=" AND (title LIKE '%$keyword%' OR description LIKE '%$keyword%' OR content LIKE '%$keyword%')";
        }
         
        $sql.=" ORDER BY  $sortBy $sortOrder  LIMIT ($pageNo - 1) * $pageSize , $pageSize;";
        
        // 获得总条数
        $ret_total = $db->query($sql_count);
        $total = $ret_total->fetchArray(SQLITE3_ASSOC);
        
        //获得列表数据
        $ret = $db->query($sql);
        $list=array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $list[]=$row;
        }

        //查询分类
        $sql_class = "SELECT * FROM category";
        //获得列表数据
        $class = $db->query($sql_class);
        $classList=array();
        while($row = $class->fetchArray(SQLITE3_ASSOC) ){
            $classList[]=$row;
        }
        // 查询单位信息
        $sql_department = "SELECT * FROM department";
        //获得列表数据
        $department = $db->query($sql_department);
        $departmentList=array();
        while($row = $department->fetchArray(SQLITE3_ASSOC) ){
            $departmentList[]=$row;
        }
        $results = array();
        array_push($results,$total,$list,$classList,$departmentList);        
        echo json_encode($results);
        $db->close();
        break;

    //添加文章
    case 'addArticle':  
        $uuid=uuid();     
        $title = $_REQUEST["title"];
        $category = $_REQUEST["category"];
        $department = $_REQUEST["department"];
        $date = $_REQUEST["date"];
        $desc = $_REQUEST["desc"];
        $content = $_REQUEST["content"];
        $userName = $_REQUEST["userName"];
        $weight = $_REQUEST["weight"];       
        //首先判断用户名是否已经存在
        $sql = "INSERT INTO 
        article (
            _id,
            category_id, 
            department_id, 
            title,
            description,
            hits,
            create_by,
            create_date,
            weight,
            del_flag,
            content
            ) VALUES (
                '$uuid',
                '$category', 
                '$department', 
                '$title',
                '$desc',
                0, 
                '$userName', 
                '$date',
                '$weight',
                0,
                '$content')";
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //编辑文章
    case 'editArticle':       
        $title = $_REQUEST["title"];
        $category = $_REQUEST["category"];
        $department = $_REQUEST["department"];
        $date = $_REQUEST["date"];
        $desc = $_REQUEST["desc"];
        $content = $_REQUEST["content"];
        $userName = $_REQUEST["userName"];
        $weight = $_REQUEST["weight"];   
        $id = $_REQUEST["id"];    
        //首先判断用户名是否已经存在
        $sql = "UPDATE 
                    article 
                SET 
                    category_id='$category', 
                    department_id='$department',
                    title='$title',
                    description='$desc',
                    create_by='$userName',
                    create_date='$date',
                    weight='$weight',
                    content='$content'
                WHERE 
                    _id = '$id'";       
        $ret = $db->exec($sql);
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

    // 删除分类
    case 'delArticle':
        $id=$_REQUEST['id'];    //     
        $sql = "DELETE  FROM article WHERE _id = '$id'";       
        
        $ret = $db->exec($sql);
        //后期还得删除栏目下面的文章
        if(!$ret){
            echo false;
        } else {
            echo true;
        }
        $db->close();
        break;

        //查询某篇文章
    case 'loadArticle':
        $id=$_REQUEST['id'];    //     
        $sql = "SELECT *  FROM article WHERE _id = '$id'";    
        //获得列表数据
        $ret = $db->query($sql);
        $list=array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $list=$row;
        }

        //查询分类
        $sql_class = "SELECT * FROM category";
        //获得列表数据
        $class = $db->query($sql_class);
        $classList=array();
        while($row = $class->fetchArray(SQLITE3_ASSOC) ){
            $classList[]=$row;
        }
        // 查询单位信息
        $sql_department = "SELECT * FROM department";
        //获得列表数据
        $department = $db->query($sql_department);
        $departmentList=array();
        while($row = $department->fetchArray(SQLITE3_ASSOC) ){
            $departmentList[]=$row;
        }
        $results = array();
        array_push($results,$list,$classList,$departmentList);        
        echo json_encode($results);
        $db->close();
        break;
      
}


?>