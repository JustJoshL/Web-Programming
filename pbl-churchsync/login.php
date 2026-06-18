<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ChurchSync</title>
    <style>
        .brand {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .brand img {
            width: 110px;
            height: auto;
        }

        .brand-text h1 {
            font-family: Georgia, serif;
            font-size: 72px;
            font-weight: bold;
            color: #ffc107;
            margin: 0;
            line-height: 1;
        }

        .brand-text p {
            margin-top: 10px;
            margin-left: 10px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 12px;
            text-transform: uppercase;
        }

        :root {
            --primary-blue: #1e3264;
            --primary-yellow: #ffc107;
            --text-dark: #333;
            --text-gray: #666;
            --bg-light: #f4f7f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
        }

        .left-panel {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box h1 {
            color: var(--primary-blue);
            font-size: 36px;
            margin-bottom: 10px;
        }

        .login-box p {
            color: var(--text-gray);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-options a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #152449;
        }

        .right-panel {
            width: 50%;
            background-image:
                linear-gradient(rgba(30, 50, 100, 0.75),
                    rgba(30, 50, 100, 0.75)),
                url('uploads/Dalam Gereja.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            font-family: Georgia, serif;
            font-size: 52px;
            font-weight: 800;
            color: var(--primary-yellow);
            letter-spacing: 3px;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="left-panel">
        <div class="login-box">
            <h1>Login</h1>
            <p>Login to your account.</p>
            <?php if (isset($_GET['error'])) : ?>
                <div class="error-message">
                    Email atau password salah.
                </div>
            <?php endif; ?>
            <form action="proses_login.php" method="POST">
                <div class="form-group">
                    <label for="email">E-mail Address</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                </div>

                <!-- Belum Dijalankan -->
                <!-- <div class="form-options">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Reset Password?</a>
                </div> -->

                <button type="submit" class="btn-login">Sign In</button>
            </form>
        </div>
    </div>

    <div class="right-panel">
        <div class="brand-wrapper">

            <div class="brand">
                <img src="uploads/churchsync-logo.png" alt="Logo ChurchSync">

                <div class="brand-text">
                    <h1>ChurchSync</h1>
                    <p>ALL ABOUT OUR CHURCH</p>
                </div>
            </div>

        </div>
    </div>
</body>

</html>