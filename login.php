<?php

session_start();

if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] ==true){
    header('Location:index.php');
    exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email =$_POST['email'];
    $password =$_POST['password'];

    if(empty($email) || empty($password)){
        echo"All fields are required.";
        exit;
    }

    $host = 'localhost';
    $dbname = 'authentication';
    $user = 'root';
    $password = '';

    $db = new PDO("mysql:host=$host; dbname=$dbname", $user, $password);
    $db -> $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statement = $db -> prepare('SELECT * FROM users WHERE email= :email');
    $statement -> execute([':email'=>$email]);
    $user =$statement ->fetch(PDO::FETCH_OBJ);

    if($user &&password_verify($password, $user ->password)){
        $_SESSION['authenticated']=true;
        $_SESSION['email']= $user ->email;
        header('Location:index.php');
        exit;
    }else{
        echo"Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h2>Login</h2>

    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    
</body>
</html>