<?php 
### Session 尚未啟動的話
if(session_id() == ''){
  # 將它啟動
  session_start();
}

require('Login.class.php');

$auth = new Login();

// 如果未登入，就導向到 index.php 登入頁面
if(!$auth->isLogin()){
  header("index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
</head>
<body>
    <h2><?php print $_SESSION['username']?> 您好</h2>
    <p>現在是 <?php print date('Y年m月d日H點i分')?></p>
    <p><a href='logout.php'>這邊可以進行登出</a></p>
    <p><b><i>以下客製化 Page ...</i></b></p>
</body>
</html>