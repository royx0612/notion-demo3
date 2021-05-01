<?php
session_start();

// 載入 Login 類別
require("Login.class.php");

// 宣告登入物件
$auth = new Login();

// 進行登入動作，正常情況下，應該都能夠登出成功
// 失敗這種情況大部分是使用者未依照程序(自行輸入網址)造成的
if ($auth->logout()) {
  // 登出成功
  header("Refresh:3;url=index.php", true, 303);
} else { // 登出失敗
  // 顯示找不到網頁
  header("HTTP/1.0 404 Not Found", false, 404); 
  exit(); // 終止程式
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout</title>
</head>

<body>
  <h3>您以登出，將在 3 秒後 回首頁 ...</h3>
</body>

</html>