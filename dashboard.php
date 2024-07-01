<?php
session_start();
$data = json_decode(file_get_contents('data.json'), true);
$user = $_SESSION['username'] ?? null;
$discord_user = $_SESSION['discord_user'] ?? null; // Assuming you store Discord user data in session

if (!$user) {
    header("Location: login.php");
    exit;
}

$vpsList = $data['users'][$user]['vps'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineMigg SHOP - Customer Website</title>
    <link rel="stylesheet" href="css/main.css" />
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.png" alt="26andfour logo" class="logo" />
    
            <nav>
                <a href="#" class="hide-desktop">
                    <img src="images/ham.svg" alt="toggle menu" class="menu" id="menu" />
                </a>
                <ul class="show-desktop hide-mobile" id="nav">
                    <li id="exit" class="exit-btn hide-desktop">
                        <img src="images/exit.svg" alt="exit menu" />
                    </li>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Contact via Discord</a></li>
                </ul>
            </nav>
        </header>
    
        <section>
            <img src="images/server.svg" alt="server graphic" class="server" />
    
            <h1>MineMigg Shop</h1>
            <p class="subhead">The exclusive store of MineMigg</p>
    
            <img src="images/scroll.svg" alt="scroll down" class="scroll hide-mobile show-desktop" />
        </section>
    </div>
    <br /><br />
    
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user); ?>!</h1>
        <?php if ($discord_user): ?>
            <div class="discord-info">
                <p>Discord ID: <?php echo htmlspecialchars($discord_user['id']); ?></p>
                <p>Discord Username: <?php echo htmlspecialchars($discord_user['username']); ?>#<?php echo htmlspecialchars($discord_user['discriminator']); ?></p>
            </div>
        <?php else: ?>
            <a href="discord_oauth.php">Link Discord Account</a>
        <?php endif; ?>
    
        <h2>Your VPS List</h2>
        <?php if (!empty($data['users'][$user]['vps'])): ?>
            <ul>
                <?php foreach ($data['users'][$user]['vps'] as $index => $vps): ?>
                    <li>
                        <a href="panel.php?vps=<?php echo $index; ?>">
                            <?php echo htmlspecialchars($vps['name'] ?? 'Unnamed VPS'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No VPS found.</p>
        <?php endif; ?>
    </div>
    <br /><br /><br /><br /><br /><br />
    
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