<?php 
### Session 尚未啟動的話
if(session_id() == ''){
  # 將它啟動
  session_start();
}

require('Login.class.php');

$auth = new Login();

// 如果已經登入，就導向到 process.php 處理頁面
if($auth->isLogin()){
  header("process.php");
}
?>

<!DOCTYPE html>
<html lang="zh_TW">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>帳號登入表單</title>
  <style>
  td {
    border: 1px solid #000;
    padding: 10px;
  }
  </style>
</head>

<body>
  <form action="process.php" method="post">
    <fieldset>
      <legend>帳號登入</legend>
      <p>
        <label for="username">名稱：
          <input type="text" name="username" id="username">
        </label>
      </p>
      <p>
        <label for="password">密碼：
          <input type="password" name="password" id="password">
        </label>
      </p>
      <p>
        <input type="submit" value="登入">
      </p>
    </fieldset>
  </form>


  <?php
  $datas = [
    ['username' => 'admin', 'password' => 'admin'],
    ['username' => 'amy', 'password' => 'beautiful'],
    ['username' => 'bob', 'password' => 'handsome'],
    ['username' => 'user', 'password' => 'user']
  ];
  ?>
  <br><br>
  <table>
    <thead>
      <tr>
        <td>帳號</td>
        <td>密碼</td>
      </tr>
    </thead>
    <?php foreach ($datas as $data) : ?>
    <tr>
      <td><?php print $data['username'] ?></td>
      <td><?php print $data['password'] ?></td>
    </tr>
    <?php endforeach ?>
  </table>
</body>

</html>