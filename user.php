 <?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "bar";

// 建立与数据库的连接
$conn = new mysqli($servername, $username, $password, $database);

// 检查连接是否成功
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 检查是否提交了区域选择
if (isset($_POST['user'])) {
    $selectedRegion = $_POST['user'];

    // 查询符合区域的 BAR 名称
    $sql = "SELECT DISTINCT `bar_name` FROM `bar` WHERE `bar_address` LIKE '%" . $selectedRegion . "%'";

    $result = $conn->query($sql);

    $options = '<option value="">bar name</option>'; // 默认选项

    if ($result->num_rows > 0) {
        // 将匹配的 BAR 名称添加到选项中
        while ($row = $result->fetch_assoc()) {
            $barName = $row['bar_name'];
            $options .= "<option value=\"$barName\">$barName</option>";
        }
    } else {
        $options .= "<option value=\"\">未找到符合的 BAR</option>";
    }

    // 将选项发送回客户端

    if (isset($_POST['submit_rating'])) {
        $selectedBarName = $_POST['bar_name'];
        $selectedRating = $_POST['star'];
    
        // 构建更新数据库的SQL查询语句
        $updateSql = "UPDATE `bar` SET `bar_score` = '$selectedRating' WHERE `bar_name` = '$selectedBarName'";
    
        // 执行更新操作
        if ($conn->query($updateSql) === TRUE) {
            echo "評分更新成功！";
        } else {
            echo "評分更新失败：" . $conn->error;
        }
        //選擇現在這家店目前的平均分數
        $sql="SELECT avg(record.bar_score) as avgScore from bar,record WHERE record.bar_id=bar.bar_id and bar.bar_name='$selectedBarName'";
        while($row = ($conn->query($sql))->fetch_assoc()) {
            $new_score=$row['avgScore'];
            break;
        }
        $updateSql = "UPDATE `bar` SET `bar_score`=$new_score WHERE bar_name ='$selectedBarName'";

        //更新新的分數
        if($conn->query($updateSql)=== TRUE) {
            echo "店家更新成功";
        } else {
            echo "更新失敗" .$conn -> error;
        }
    }
}



// 关闭数据库连接
$conn->close();

?> 


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAR HOPPER User</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            background-color: black;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .navbar-brand {
            padding: 0 10px;
            font-size: 50px;
        }

    </style>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-light navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand mb-0 h1"><b>BAR HOPPER</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">BAR HOPPER</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="#">用戶姓名</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">找BAR</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">我的紀錄</a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li>
                          <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                      </ul>
                    </li>
                </ul>
              <form class="d-flex mt-3" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
              </form>
            </div>
          </div>
        </div>
      </nav>

      <div class="container">
        <form action="#" method="post">
            <table border="0px" width="600px" height="400px" align="center" cellpadding="0px" cellspacing="0px">
                <!-- 表格内容 -->
                <tr height="40px">
                    <td colspan="2">
                        <font size="4"><b>BAR HOPPER 評分專區</b></font>
                    </td>
                </tr>
                <tr>
                    <td align="center"><b>區域</b></td>
                    <td>
                        <form method="POST" action="your_php_file.php">
                            <select name="user">
                                <option value="">全部</option>
                                <option value="大安區">大安區</option>
                                <option value="中正區">中正區</option>
                                <option value="士林區">士林區</option>
                                <option value="萬華區">萬華區</option>
                                <option value="松山區">松山區</option>
                                <option value="文山區">文山區</option>
                                <option value="大同區">大同區</option>
                                <option value="內湖區">內湖區</option>
                                <option value="信義區">信義區</option>
                                <option value="中山區">中山區</option>
                            </select>
                            <input type="submit" value="提交">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center"><b>BAR NAME</b></td>
                    <td>
                        <select name="bar_name">
                        <?php echo $options; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="center"><b>星等評分</b></td>
                    <td>
                        <input type="radio" name="star" value="1">☆
                        <input type="radio" name="star" value="2">☆☆
                        <input type="radio" name="star" value="3">☆☆☆
                        <input type="radio" name="star" value="4">☆☆☆☆
                        <input type="radio" name="star" value="5">☆☆☆☆☆
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="送出評分" name="submit_rating">
                    </td>
                </tr>
            </table>
        </form>
    </div>
      

      
    
</body>
</html>


