<?php
header("content-type:text/html;charset=utf-8");  
date_default_timezone_set("PRC");

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('db.db');
    }
}
$db = new MyDB();
if(!$db){
    echo $db->lastErrorMsg();
} 

function loadListData($db,$sql)
{
    $ret = $db->query($sql);
    $results=array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $results[]=$row;
    }
    echo json_encode($results);
    $db->close();
}

function getRemoteIp() {
    //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    return $res;
  } 

  function uuid(){
    if (function_exists('com_create_guid')){
        $uuid = com_create_guid();
         return substr($uuid,1,strlen($uuid)-2); 
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
            
            return substr($uuid,1,strlen($uuid)-1); 
        }
    }
?>