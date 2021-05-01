<?php
### Session 尚未啟動的話
if (session_id() == '') {
  # 將它啟動
  session_start();
}

/*
  Login 類別
  宣告的時候，可輸入兩個參數帳號及密碼
  亦可另外設置帳號及密碼
  使用方式：
  1. 宣告　$obj = new Login($username, $password)
  2. 驗證　$obj->login(); 
  3. 錯誤訊息 $obj->getError();
*/

class Login
{
  private $username, // 帳號
    $password, // 密碼
    $isLogin = false, // 驗證結果
    $errors = []; // 錯誤訊息

  ### 模擬資料庫資料
  const DB = [
    ['username' => 'admin', 'password' => 'admin'],
    ['username' => 'amy', 'password' => 'beautiful'],
    ['username' => 'bob', 'password' => 'handsome'],
    ['username' => 'user', 'password' => 'user']
  ];

  /* 建構子　參數選擇性輸入*/
  function __construct(string $username = NULL, string $password = NULL)
  {
    // 清除 Session
    // $this->cleanSession();

    if (!$this->sessionIsLogin()) { // 判斷是否登入，若未登入進行資料輸入
      # 如果有輸入 username ，進行  username 設置
      if (isset($username)) {
        $this->setAccount($username);
      }

      # 如果有輸入 password ，進行  password 設置
      if (isset($password)) {
        $this->setPassword($password);
      }
    }
  }

  /********* 公有成員方法 *******/

  /* 設置帳號到物件內 */
  public function setAccount(string $username): void
  {
    // 將輸入帳號內容過濾
    $this->username = $this->filterInput($username);
  }

  /* 設置密碼到物件內 */
  public function setPassword(string $password): void
  {
    $this->password = $password;
  }

  /* 
  驗證 帳號及密碼
  若有問題就顯示錯誤
  都沒問題則登入並設置 Session
  */
  public function login(): bool
  {
    # 如果已經登入直接回傳 true
    if ($this->isLogin == true) {
      return true;
    }

    # 帳號或密碼其一未輸入
    if (empty($this->username) || empty($this->password)) {
      array_push($this->errors, "請完整輸入帳號及密碼");
    } else {
      ### 驗證帳號
      $validResult = $this->validUsername();
      if ($validResult['result'] == false) { // 帳號驗證失敗
        # 取得帳號驗證錯誤訊息
        $this->errors = array_merge($this->errors, $validResult['msg']);
      } else { ### 驗證密碼
        $correctPassword = false; // 驗證結果(預設 false 沒通過)
        foreach (self::DB as $row) { // 迴圈密碼比對
          if ($this->username == $row['username'] && $this->password == $row['password']) {
            $correctPassword = true; // 找到密碼，設置旗標
            break; // 跳出
          }
        }
        # 檢查旗標
        if ($correctPassword == false) {
          array_push($this->errors, "密碼不正確");
        }
      }
    }

    # 回傳驗證結果
    if (empty($this->errors) && $correctPassword) { // 登入成功
      // 更新帳號狀態為 登入
      $this->isLogin = true;
      // 設置 Session
      $this->setSession();
    } else { // 登入失敗
      // 更新帳號狀態 未登入
      $this->isLogin = false;
      // 清除 Session
      $this->cleanSession();
    }
    return $this->isLogin;
  }

  /* 登出函式 */
  public function logout(): bool
  {
    // 如果已經登入，進行 Session 清空
    if ($this->isLogin) {
      $this->cleanSession();
      return true;
    } else { // 無登入的狀態
      // 會有這種情況大部分是使用者未依照程序(自行輸入網址)
      return false;
    }
  }

  /* 回傳登入狀態 */
  public function isLogin()
  {
    return $this->isLogin;
  }

  /* 取得驗證錯誤訊息 */
  public function getError()
  {
    return $this->errors;
  }

  /********* 私有成員方法 *******/

  /* 驗證帳號，回傳內容為陣列，內涵結果　result 及訊息 msg*/
  private function validUsername(): array
  {
    # 錯誤訊息
    $msg = [];

    # 資料是否空白
    if (strlen($this->username) < 1) {
      array_push($msg, "帳號空白");
    } else {
      # 檢查是否有此帳號
      $usernameInUse = false;

      # 用迴圈跑此帳號是否存在
      for ($i = 0; $i < count(self::DB); $i++) {
        if (self::DB[$i]['username'] == $this->username) {
          $usernameInUse = true;
          break;
        }
      }
      # 無此帳號
      if ($usernameInUse == false) {
        array_push($msg, "無此帳號");
      }
    }

    # 上述以驗證結束，檢查是否有錯誤訊息
    if ($msg) {
      return ['result' => false, 'msg' => $msg]; // 驗證失敗，回傳 result 及 msg
    } else {
      return ['result' => true]; // 驗證成功，回傳 result => true 
    }
  }

  /* 過濾函式，之後如果連接資料庫 PDO，可以在這邊設定過濾內容 */
  private function filterInput(string $variable): string
  {
    return trim($variable);
  }

  /************ Session 管理*************/

  /* Session 設置 */
  private function setSession(): void
  {
    // 將 username 放進 session 內
    $_SESSION['username'] = $this->username;
  }

  /* 清除 Session */
  private function cleanSession(): void
  {
    # 以下兩著都要
    session_unset(); // 清除 Session 內容
    session_destroy(); // 情除 Session ID
  }

  /* 檢查 Session 判別是否登入 */
  private function sessionIsLogin(): bool
  {

    // 如果 Session 的 username 有值，代表已登入
    if (isset($_SESSION['username']) && strlen($_SESSION['username']) > 0) {
      // 設定以登入狀態
      $this->isLogin = true;
      //回傳 true
      return true;
    } else { // 未登入
      return false;
    }
  }
}
