<?php 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($email) || empty($password) || empty($confirmPassword)) {

    
        echo "<p>All fields are required</p>";
        exit;

    } else if ($password !== $confirmPassword) {

        
        echo "<p> Passwords do not match</p>";
        exit;

    }
    $host = 'localhost';
    $dbname = 'authentication';
    $user = 'root';
    $password = '';

    $db = new PDO("mysql:host=$host; dbname=$dbname", $user, $password);
    $db -> $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $check = $db -> prepare("SELECT * FROM users WHERE email = :email");
    $check ->execute([':email' => $email]);

    if($check -> fetch()){
        echo"The email is already registered";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $statement = $db -> prepare("INSERT INTO users(email,password) VALUES(:email, :password)");
    $statement-> execute([
        ':email' => $email,
        ':password' => $hashedPassword,
    ]);

    echo "Successfully registered";

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
    <form method="POST" action="/signup.php">
    <div class="mb-2">
        <label for="email" class="visually-hidden">Email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="email@example.com">
    </div>
    <div class="mb-2">
        <label for="password" class="visually-hidden">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="mb-2">
        <label for="confirm_password" class="visually-hidden">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>


</body>
</html>