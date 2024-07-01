<?php
session_start();
$data = json_decode(file_get_contents('data.json'), true);
$user = $_SESSION['username'] ?? null;

if (!$user) {
    header("Location: login.php");
    exit;
}

$vpsIndex = $_GET['vps'] ?? null;
if ($vpsIndex === null || !isset($data['users'][$user]['vps'][$vpsIndex])) {
    echo "VPS not found!";
    exit;
}

$vps = $data['users'][$user]['vps'][$vpsIndex];
$webhook_url = 'https://discord.com/api/webhooks/1257167186988896256/A6zI9w7DnrmrchnhjdZAuM7Yfor8UD__hgTUDcC1c7TebmUTmEMfkruucpJ2vyrBDjMa'; // Replace with your Discord webhook URL

function send_discord_notification($message) {
    global $webhook_url;
    $data = ["content" => $message];
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    $context  = stream_context_create($options);
    file_get_contents($webhook_url, false, $context);
}

$actionMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $vpsName = $vps['name'] ?? 'Unnamed VPS';
    
    switch ($action) {
        case 'reinstall':
            $os_choice = $_POST['os_choice'] ?? 'unknown OS';
            $message = "User $user đã yêu cầu cài đặt lại VPS: $vpsName (IP: {$vps['ip']}) || OS : $os_choice";
            break;
        case 'cancel':
            $message = "User $user Đã yêu cầu hủy VPS: $vpsName (IP: {$vps['ip']})";
            unset($data['users'][$user]['vps'][$vpsIndex]);
            file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
            header("Location: dashboard.php");
            exit;
        case 'extend':
            $message = "User $user requested to extend VPS: $vpsName (IP: {$vps['ip']})";
            break;
        default:
            $message = "User $user performed an unknown action on VPS: $vpsName (IP: {$vps['ip']})";
            break;
    }
    
    send_discord_notification($message);
    $actionMessage = "Hành động $action đang được thực hiện. Vui lòng chờ. ";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="css/main.css" />

    <title>MineMigg SHOP - Trang Web dành cho khách hàng</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .vps-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .vps-card h2 {
            margin: 0;
        }
        .vps-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .vps-card a:hover {
            background-color: #0056b3;
        }
        .notification {
            padding: 10px;
            margin: 10px 0;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .popup h2 {
            margin-top: 0;
        }
        .popup .close {
            cursor: pointer;
            color: red;
            float: right;
        }
        .popup select {
            margin: 10px 0;
        }
        .popup button {
            margin-top: 10px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 500;
        }
    </style>
  </head>
  <body>
    <div class="container">
      <header>
        <img src="images/logo.png" alt="26andfour logo" class="logo" />

        <nav>
          <a href="#" class="hide-desktop">
            <img
              src="images/ham.svg"
              alt="toggle menu"
              class="menu"
              id="menu"
            />
          </a>
          <ul class="show-desktop hide-mobile" id="nav">
            <li id="exit" class="exit-btn hide-desktop">
              <img src="images/exit.svg" alt="exit menu" />
            </li>
            <li><a href="index.php">Trang chủ</a></li>
            <li><a href="dashboard.php">Trang quản lý</a></li>
            <li><a href="https://discord.com/invite/G5CUsjVBV9">Liên hệ qua Discord</a></li>
          </ul>
        </nav>
      </header>

      <section>
        <img src="images/server.svg" alt="server graphic" class="server" />

        <h1>MineMigg Shop</h1>
        <p class="subhead">Cửa hàng duy nhất của MineMigg</p>

        <img
          src="images/scroll.svg"
          alt="scroll down"
          class="scroll hide-mobile show-desktop"
        />
      </section>
    </div>
</br>
</br>

    <div class="container">
        <h1>VPS Details</h1>
        <?php if ($actionMessage): ?>
            <div class="notification">
                <?php echo htmlspecialchars($actionMessage); ?>
            </div>
        <?php endif; ?>
        <div class="vps-details">
            <p><strong>Chủ sỡ hữu :</strong> <?php echo htmlspecialchars($vps['nickname']); ?></p>
            <p><strong>IP:</strong> <?php echo htmlspecialchars($vps['ip']); ?></p>
            <p><strong>Hệ điều hành :</strong> <?php echo htmlspecialchars($vps['os']); ?></p>
            <p><strong>Tên :</strong> <?php echo htmlspecialchars($vps['name'] ?? ''); ?></p>
            <p><strong>Mật khẩu :</strong> <?php echo htmlspecialchars($vps['password'] ?? ''); ?></p>
            <p><strong>Ngày hết hạn :</strong> <?php echo htmlspecialchars($vps['expiry_date'] ?? ''); ?></p>
            <?php if (empty($vps['name']) || empty($vps['password'])): ?>
                <p><strong>Trạng thái :</strong> Đang cài đặt hệ thống...</p>
            <?php endif; ?>
        </div>
        <?php if (!empty($vps)): ?>
            <div class="actions">
                <form method="post" action="" id="action-form">
                    <input type="hidden" name="action" id="action-input" value="">
                    <input type="hidden" name="os_choice" id="os-input" value="">
                    <button type="button" onclick="showPopup()">Cài đặt lại</button>
                    <button type="submit" onclick="setAction('cancel')">Hủy VPS</button>
                    <button type="submit" onclick="setAction('extend')">Gia hạn</button>
                </form>
            </div>
        <?php endif; ?>
        <a class="back-link" href="dashboard.php">Quay lại trang quản lý</a>
    </div>
</div>

<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <span class="close" onclick="hidePopup()">&times;</span>
    <h2>Chú ý: Việc cài đặt lại sẽ mất dữ liệu!</h2>
    <label for="os-choice">Chọn hệ điều hành:</label>
    <select id="os-choice">
        <option value="Windows Server 2012 R2">Windows Server 2012 R2 (Khuyến nghị)</option>
        <option value="Windows Server 2016">Windows Server 2016</option>
        <option value="Linux CentOS 7 64Bit">Linux CentOS 7 64Bit</option>
        <option value="Windows Server 2019">Windows Server 2019</option>
        <option value="Windows 10 64bit">Windows 10 64bit</option>
        <option value="Linux Ubuntu-20.04">Linux Ubuntu-20.04</option>
        <option value="Linux Ubuntu-22.04">Linux Ubuntu-22.04</option>
        <option value="Desbian 11">Desbian 11</option>
        <option value="AlmaLinux 8">AlmaLinux 8</option>
        <option value="Windows Server 2012r2 MD">Windows Server 2012r2 MD</option>
        <option value="CentOS7-Aiko">CentOS7-Aiko</option>
        <option value="Windows Server 2012 R2 [Có sẵn server Minecraft]">Windows Server 2012 R2 [Có sẵn server Minecraft]</option>
    </select>
    <button onclick="confirmReinstall()">Xác nhận</button>
</div>

<script>
function showPopup() {
    document.getElementById('popup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function hidePopup() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function setAction(action) {
    document.getElementById('action-input').value = action;
}

function confirmReinstall() {
    const osChoice = document.getElementById('os-choice').value;
    document.getElementById('os-input').value = osChoice;
    setAction('reinstall');
    document.getElementById('action-form').submit();
}
</script>

</body>
</html>

