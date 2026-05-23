<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[2473018]-Joshua Lewi</title>
</head>
<body>
    <?php
    $user = $_POST['username'];
    $pass = $_POST['password'];
    if ($user == "admin" && $pass == "admin") {
        echo "<h1>Login berhasil!</h1>";
        echo "<h1>Selamat datang, <span style='color: blue; font-size: 48px'>admin</span>.</h1>";
        echo "<a href='login.php' style='color: purple;'>kembali ke halaman login</a>";
    } else {
        echo "<h2 style='color: red;'>Username : <span style='color: black;'>$user</span> Tidak Terdaftar!</h2>";
        echo "<a href='login.php' style='color: purple;'>kembali ke halaman login</a>";
    }
    ?>
</body>
</html>