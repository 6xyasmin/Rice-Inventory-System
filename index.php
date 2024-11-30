<?php
session_start();
require('db.php');

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}
if (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) {
    if ($_SESSION["role"] === 'admin') {
        header("Location: home.php");
    } else {
        header("Location: user.php");
    }
    exit();
}

if (isset($_COOKIE["token"])) {
    $token = $_COOKIE["token"];
    $stmt = $conn->prepare("SELECT u.username, u.role FROM tokens t JOIN users u ON t.user_id = u.id WHERE t.token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["auth"] = true;
        $_SESSION["username"] = $row["username"];
        $_SESSION["role"] = $row["role"];

        $updateLastLoginStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE username = ?");
        $updateLastLoginStmt->bind_param('s', $row["username"]);
        $updateLastLoginStmt->execute();
        $updateLastLoginStmt->close();

        if ($row['role'] === 'admin') {
            header("Location: home.php");
        } else {
            header("Location: user.php");
        }
        exit();
    } else {
        setcookie("token", "", time() - 3600, "/");
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '6Lc0ndIpAAAAAIQ1rfB2K_N2hUrMw3yJSdZJaBXU';
    $recaptchaValidationUrl = 'https://www.google.com/recaptcha/api/siteverify';
    
    $recaptchaData = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $recaptchaValidationUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($recaptchaData));
    $recaptchaResult = curl_exec($ch);
    curl_close($ch);
    
    $recaptchaResult = json_decode($recaptchaResult, true);
    
    if (!$recaptchaResult['success']) {
        echo "<script>alert('reCAPTCHA validation failed.'); window.location.href = 'index.php';</script>";
        exit();
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('Invalid CSRF token.'); window.location.href = 'index.php';</script>";
        exit();
    }

    $username = htmlentities($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    
        if (password_verify($password, $row['password'])) {
            
            $token = bin2hex(random_bytes(32));

            $user_id = $row['id'];
            $insertTokenStmt = $conn->prepare("INSERT INTO tokens (user_id, token) VALUES (?, ?)");
            $insertTokenStmt->bind_param('is', $user_id, $token);
            if ($insertTokenStmt->execute()) {
               
                setcookie("token", $token, time() + (86400 * 30), "/");

                $_SESSION['auth'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];
                $_SESSION['user_id'] = $user_id; 

                session_regenerate_id(true);
                $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $updateStmt->bind_param('i', $user_id);
                $updateStmt->execute();
                $updateStmt->close();

                if ($row['role'] === 'admin') {
                    header("Location: home.php");
                } else {
                    header("Location: user.php");
                }
                exit();
            } else {
                echo "<script>alert('Failed to set token.'); window.location.href = 'index.php';</script>";
                exit();
            }
        }
    }
    echo "<script>alert('Invalid username or password.'); window.location.href = 'index.php';</script>";
    exit();
}



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('Invalid CSRF token.'); window.location.href = 'index.php';</script>";
        exit();
    }

    $username = htmlentities($_POST['username']);
    $email =htmlentities($_POST['email']);
    
    $password = htmlentities($_POST['password']);
    
    $allowed_characters = '/^[a-zA-Z0-9]+$/';
    if (!preg_match($allowed_characters, $username)) {
        echo "<script>alert('Invalid username. Username should only contain alphanumeric characters.'); window.location.href = 'index.php';</script>";
        exit();
    }
    if (!preg_match($allowed_characters, $password)) {
        echo "<script>alert('Invalid password. Password should only contain alphanumeric characters.'); window.location.href = 'index.php';</script>";
        exit();
    }

    $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param('ss', $username, $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or email already in use.'); window.location.href = 'index.php';</script>";
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $insertStmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $insertStmt->bind_param('sss', $username, $email, $hashedPassword);
    $insertStmt->execute();

    if ($insertStmt->affected_rows > 0) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.location.href = 'index.php';</script>";
    }

    $checkStmt->close();
    $insertStmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/rice/css/logins.css">
</head>
<body>
    <div class="wrapper">
        <img src="/rice/img/ricelog.png" alt="">
        <h2 class="text-right">Rice</h2>
        <div class="form-wrapper login">
            <form action="index.php" method="POST">
                <h2>Login</h2>
                <div class="input-box">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="g-recaptcha" data-sitekey="6Lc0ndIpAAAAABM03fU9QYjD0hOEvYp5LXcAnNbO"></div>
        <br>
                <button type="submit" name="login">Login</button>
                <div class="sign-link-login">
                    <p>Don't have an account? <a href="#" onclick="registerActive()">Register</a></p>
                </div>
            </form>
        </div>
        <div class="form-wrapper register">
            <form action="index.php" method="POST">
                <h2>Registration</h2>
                <div class="input-box">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="captcha">
                <div class="g-recaptcha" data-sitekey="6Lc0ndIpAAAAABM03fU9QYjD0hOEvYp5LXcAnNbO"></div>
</div>
                <br>
                <button type="submit" name="register">Register</button>
                <div class="sign-link-register">
                    <p>Already have an account? <a href="#" onclick="loginActive()">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://www.recaptcha.net/recaptcha/api.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="./js/login.js"></script>
</body>
</html>
