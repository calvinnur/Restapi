
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?<?php echo time()?>">
    <title>Login</title>
</head>
<body>
<form method="POST" action="verifikasi.php">
    <div class="form-login">
    <h3>Login</h3>
    <label>Email</label>
    <br>
    <input type="text" name="email" placeholder="email">
    <br>
    <label>Password</label>
    <br>
    <input type="password" name="password" placeholder="password">
    <br>
    <button type="submit">Login</button>
    </div>
</form>
</body>
</html>