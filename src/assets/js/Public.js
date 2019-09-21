export {
  uuid,
  timestampToTime,
  generateOptions
}

// 生成uuid
function uuid() {
  var s = [];
  var hexDigits = "0123456789abcdef";
  for (var i = 0; i < 36; i++) {
    s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
  }
  s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
  s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
  s[8] = s[13] = s[18] = s[23] = "-";

  var uuid = s.join("");
  return uuid;
}


//时间戳转换成日期时间格式
function timestampToTime(timestamp, showHours) {
  var timestamp = timestamp;
  if (timestamp.length == 10) {
    timestamp *= 1000;
  }
  timestamp = parseInt(timestamp);
  var date = new Date(timestamp); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
  var Y = date.getFullYear() + '-';
  var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
  var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
  var h = (date.getHours() < 10 ? '0' + (date.getHours()) : date.getHours()) + ':';
  var m = (date.getMinutes() < 10 ? '0' + (date.getMinutes()) : date.getMinutes()) + ':';
  var s = (date.getSeconds() < 10 ? '0' + (date.getSeconds()) : date.getSeconds());
  if (showHours) {
    return Y + M + D + h + m + s;
  } else {
    return Y + M + D;
  }
}

//递归生成树形结构

function generateOptions(params) { //生成Cascader级联数据  
  var result = [];
  for (let param of params) {
    param.value = param.id;
    if (param.p_id == 0) { //判断是否为顶层节点      
      param.children = getchilds(param.id, params); //获取子节点
      result.push(param);
    }
  }
  return result;
}

function getchilds(id, array) {
  let childs = new Array();
  for (let arr of array) { //循环获取子节点
    if (arr.p_id == id) {
      childs.push(arr);
    }
  }
  for (let child of childs) { //获取子节点的子节点
    let childscopy = getchilds(child.id, array); //递归获取子节点
    if (childscopy.length > 0) {
      child.children = childscopy;
    }
  }
  return childs;
}
