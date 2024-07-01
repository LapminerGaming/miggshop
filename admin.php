<?php
session_start();
$data = json_decode(file_get_contents('data.json'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nickname = $_POST['nickname'];
    $ip = $_POST['ip'];
    $os = $_POST['os'];
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';

    if (!isset($data['users'][$username])) {
        echo "User not found!";
    } else {
        $vps = [
            'nickname' => $nickname,
            'ip' => $ip,
            'os' => $os,
            'name' => $name,
            'password' => $password,
            'expiry_date' => $expiry_date
        ];
        $data['users'][$username]['vps'][] = $vps;
        file_put_contents('data.json', json_encode($data));
        echo "VPS added successfully!";
    }
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
            <li><a href="#">Trang chủ</a></li>
            <li><a href="#">Trang quản lý</a></li>
            <li><a href="#">Liên hệ qua Discord</a></li>
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


    <div class="container">
    <h1>Add/Edit VPS</h1>
    <form method="post" action="admin.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="nickname">Nickname:</label>
        <input type="text" id="nickname" name="nickname" required><br>
        <label for="ip">IP:</label>
        <input type="text" id="ip" name="ip" required><br>
        <label for="os">OS:</label>
        <input type="text" id="os" name="os" required><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br>
        <label for="password">Password:</label>
        <input type="text" id="password" name="password"><br>
        <label for="expiry_date">Expiry Date:</label>
        <input type="text" id="expiry_date" name="expiry_date"><br>
        <button type="submit">Submit</button>
    </form>
    </div>

    <footer>
      <div class="footer-container">
        <div class="container">
          <a href="#">
            <img src="images/logo.png" alt="logo" class="logo" />
          </a>
          <ul class="footer-links">
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Policy</a></li>
          </ul>
        </div>
      </div>
    </footer>

    <script>
      var menu = document.getElementById("menu");
      var nav = document.getElementById("nav");
      var exit = document.getElementById("exit");

      menu.addEventListener("click", function(e) {
        nav.classList.toggle("hide-mobile");
        e.preventDefault();
      });

      exit.addEventListener("click", function(e) {
        nav.classList.add("hide-mobile");
        e.preventDefault();
      });
    </script>
  </body>
</html>
