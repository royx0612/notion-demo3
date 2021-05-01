<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>帳號登入表單 - 處理器</title>
</head>

<body>
  <?php
  date_default_timezone_set('Asia/Taipei');

  // 載入 Login 類別
  require("Login.class.php");

  // 宣導 $auth 物件，並初始化物件內容（帳號、密碼）
  $auth = new Login($_POST['username'], $_POST['password']);


// 驗證帳號
if ($auth->login()) {
  header("Location:member.php");
} else { // 驗證失敗列印錯誤訊息
  printf("<h2>錯誤: %s </h2>", implode('<br>', $auth->getError()));
}

  ?>
</body>

</html>