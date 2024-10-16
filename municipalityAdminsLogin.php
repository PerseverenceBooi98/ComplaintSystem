<?php
session_start();
$error = '';

$municipality_credentials = [
    // Ngaka Modiri Molema
    'mahikeng' => 'mahikeng123',
    'ramotshere_moiloa' => 'ramotshere123',
    'ditsobotla' => 'ditsobotla123',
    'tswaing' => 'tswaing123',
    'ratlou' => 'ratlou123',
    
    // Bojanala Platinum
    'rustenburg' => 'rustenburg123',
    'madibeng' => 'madibeng123',
    'moses_kotane' => 'moses123',
    'moretele' => 'moretele123',
    'kgetlengriver' => 'kgetleng123',
    
    // Dr Kenneth Kaunda
    'jb_marks' => 'jbmarks123',
    'city_of_matlosana' => 'matlosana123',
    'maquassi_hills' => 'maquassi123',
    
    // Dr Ruth Segomotsi Mompati
    'greater_taung' => 'taung123',
    'kagisano_molopo' => 'kagisano123',
    'naledi' => 'naledi123',
    'mamusa' => 'mamusa123',
    'lekwa_teemane' => 'lekwa123'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (array_key_exists($username, $municipality_credentials) && $municipality_credentials[$username] === $password) {
        $_SESSION['user'] = $username;
        $_SESSION['user_type'] = 'municipality_admin';
        header("Location: municipalityAdminDashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipality Admin Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Same styles as provincialAdminLogin.php */
         .login-container {
            max-width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
        }
        .login-container h2 {
            text-align: center;
            color: #003366;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #002244;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Municipality Admin Login</h2>
        <form class="login-form" method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>