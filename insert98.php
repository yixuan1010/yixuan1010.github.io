
<?php
session_start();
$curr_permission=$_SESSION['permission'];
if ($curr_permission!='A') {
    echo '<script>alert("'.$curr_permission.'你沒有權限");window.location.href="login.php";</script>';
}
include("action.php");
$site = $_GET['site'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取註冊表單提交的資料
    $bar_input = $_POST['bar_input'];
    $add_input = $_POST['add_input'];
    $menu_input = $_POST['menu_input'];
    $site = $_POST['site'];
    $map = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3616.048123782825!2d121.56791657510752!3d24.998480677839325!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3442aa68b2adc2ef%3A0x994c821502530140!2z5rij55S3IFRhaXdhbiBCaXN0cm8g5pyo5p-15LqM5rij!5e0!3m2!1szh-TW!2stw!4v1684827016302!5m2!1szh-TW!2stw" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        // 验证数据是否完整（可以根据需要进一步验证数据的格式）
    // 驗證資料是否完整（可根據需要進一步驗證資料的格式）
    if (!empty($bar_input) && !empty($add_input) && !empty($menu_input)) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "bar";

        // 建立與資料庫的連接
        $conn = new mysqli($servername, $username, $password, $database);


        // 檢查連接是否成功
        if ($conn->connect_error) {
            die("連接失敗: " . $conn->connect_error);
        }

        // 檢查是否已存在具有相同電話的記錄
        $check_account_sql = "SELECT * FROM `bar` WHERE `bar_name` = '$bar_input'";
        $result = $conn->query($check_account_sql);

        if ($result->num_rows > 0) {
            echo "該bar已存在。";
        } else {


            $query = "SELECT `site_id` FROM `site` WHERE `site_name` = '$site'";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $siteID = $row['site_id'];
            } else {
                // 如果 BAR 不存在，则进行相应操作，例如给出错误提示
                echo "site不存在";
                exit();
            }
            $query = "SELECT `bar_id` FROM `bar` ORDER BY `bar_id` desc";
            $result = $conn->query($query);
            if ($result) {
                $row = $result->fetch_assoc();
                $barID = $row['bar_id']+1;
            } else {
                // 如果 BAR 不存在，则进行相应操作，例如给出错误提示
                echo '<script>alert("BAR不存在");window.location.href="opinion.php";</script>';
                exit();
            }

            // 執行註冊的SQL語句
            $sql = "INSERT INTO `bar` (`bar_id`,`bar_name`, `bar_address`, `menu_image`, `site_id`, `map`) VALUES ('$barID','$bar_input', '$add_input', '$menu_input', '$siteID', '$map' )";
            
            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("註冊成功！")</script>';
            } else {
                echo "註冊失敗：" . $conn->error;
            }
        }

        // 關閉資料庫連接
    } else {
        echo '<script>alert("請填寫完整資料")</script>';
    }
}
?>

<!DOCTYPE html>
<html>
  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAR HOPPER Insertbar</title>
    <meta http-equiv="Content-Type" content="text/html">
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <style>
        body {
            background-color: rgb(53, 51, 51);
            color: white;
            margin: 0;
            padding: 0;
        }
        *{
            padding: 0;
            margin: 0;
        }
        header{
            background-color: black;
        }
        li{
            list-style: none;
        }
        a{
            color: rgb(255, 255, 255);
            text-decoration: none;
        }
        .container{
            max-width: 1224px;
            width: 90%;
            margin: 0 auto;
        }
        .navbar{
            min-height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-branding{
            font-size: 2rem;
        }
        .nav-menu{
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 60px;
        }
        .nav-link{
            transition: 0.3s ease-out;
        }
        .nav-link:hover{
            color: dodgerblue;
        }
        .hamburger{
            display: none;
            cursor: pointer;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .form-container table {
            width: 100%;
        }
        tr {
            height: 100px;
        }   
    </style>
</head>
<body>
    <header>
        <div class = "container">
            <nav class = "navbar">
                <h3 class = "nav-branding">Bar Hopper</h3>
                <ul class = "nav-menu">
                    <li class = "nav-item">
                        <a onclick="javascript:logout();" class = "nav-link" href="login.php">登出</a>
                    </li>
                    <li class = "nav-item">
                        <a href= "Bar_search1.php" class = "nav-link">找 Bar</a>
                    </li>
                    <li class = "nav-item">
                        <a href= "Alc_search1.php" class = "nav-link">找 酒</a>
                    </li>
                    <li class = "nav-item">
                        <a href= "change.php" class = "nav-link">更改資料</a>
                    </li>
                </ul>
                <div class = "hamburger">
                    <span class = "bar"></span>
                    <span class = "bar"></span>
                    <span class = "bar"></span>
                </div>
            </nav>
        </div>    
    </header>
    <?php
		$query = "SELECT * FROM site";
		$result = mysqli_query($con, $query);
	?>
    <div class="form-container">
        <form action="#" method="post">
            <table border="0px" width="500px" height="400px" align="center" cellpadding="25px" cellspacing="0px">
                <!-- 表格內容 -->
                <tr height="40px">
                    <td colspan="5">
                        <font size="8"><b>新增bar</b></font>
                    </td>
                </tr>
                <tr>
                    <td align="center"><b style="font-size: 30px;">酒吧名</b></td>
                    <td><input id="user_name1" type="text" name="bar_input" required placeholder="輸入酒吧名" style="font-size: 20px;"></td>
                </tr>
                <tr>
                    <td align="center"><b style="font-size: 30px; ">區域</b></td>
                    <td>
                    <form id="siteForm" method="POST" action="opinion.php">
                        <select name="site" style="font-size: 20px;" onchange="submitForm(this)">
                            <option value="">全部</option>
                            <?php while($row = mysqli_fetch_array($result)): ?>
                                <option value="<?php echo $row['site_name'] ?>"  
                                    <?php if($site == $row['site_name']){echo "selected";} ?> 
                                >
                                    <?php echo $row['site_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                    </td>
                </tr>                    
                <tr>
                    <td align="center"><b style="font-size: 30px;">地址</b></td>
                    <td><input id="user_name" type="text" name="add_input" required placeholder="地址" style="font-size: 20px;"></td>
                </tr>
                <tr>
                    <td align="center"><b style="font-size: 30px;">menu網址</b></td>
                    <td><input id="password" type="text" name="menu_input" required placeholder="menu網址" style="font-size: 20px;"></td>
                </tr>
          
                <tr>
                    <td colspan="2" align="center" style="font-size: 20px;">
                        <button id='register_button' type='submit' style="font-size: 20px;">確認</button>
                    </td>
                </tr>
            </table>
         </form>
    </div>


    </script>
    <script type="text/javascript">
        function logout() {
            sessionStorage.clear();
            $_SESSION['permission'] = 'C';
        };
    </script>

</body>
</html>
