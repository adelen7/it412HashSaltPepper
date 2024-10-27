<?php
session_start();
require_once "con_DB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST['username'];
    $passInput = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password']; 
            $salt = $row['salt']; 
            $pepper = "pepperspray";

            $passInputCombined = $pepper . $passInput . $salt;

            if (password_verify($passInputCombined, $storedPassword)) {
                $_SESSION['messageSuccess'] = "Login successfully";
                $_SESSION['username'] = $row['username'];

                echo '<script>window.location.href = "home.php";</script>';
                exit();
            } else {
                $messageError = "Incorrect password. Please try again.";
            }
        } else {
            $messageError = "User not found. Please Sign up first.";
        }

        $result->free();
    } else {
        $messageError = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="cryptography.png">
    <title>Login</title>
</head>
<body>
<div class="container mt-5">
    <div class="container" style="max-width: 500px;">
        <div class="alert alert-success col" role="alert" <?php echo isset($messageSuccess) ? '' : 'style="display: none;"'; ?>">
            <?php echo $messageSuccess; ?>
        </div>
        <div class="alert alert-danger col" role="alert" <?php echo isset($messageError) ? '' : 'style="display: none;"'; ?>">
            <?php echo $messageError; ?>
        </div>
    </div>
    <div class="card mx-auto" style="max-width: 500px;" >
    <div class="p-3 mb-2 bg-dark text-white"> 
        <div class="row justify-content-center">
            <div class="col mx-4">
                <h1 class="text-center mb-3 mt-3">Log In</h1>
                <br>
                <form action="index.php" method="post">
                    <div class="form-group mb-3">
                        <input placeholder="Username"  type="username" class="form-control" id="username" name="username" required>
                    </div>
                    <br>
                    <div class="form-group mb-4">
                        <input placeholder="Password"  type="password" class="form-control" id="password" name="password" minlength="8" required>
                    </div> 
                    <br>
                    <div class="d-grid gap-2 mx-5">
                        <button type="submit" class="btn btn-primary mb-3">Log In</button>
                        <p class="mb-0 me-2">Don't have an account?</p>
                        <a type="button"  class="btn btn-outline-danger text-white" href="register.php" >Sign Up</a>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
