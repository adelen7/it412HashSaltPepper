<?php
session_start();
require_once "con_DB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST['username'];
    $userPass = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];


    if ($userPass === $confirmpassword) {

        // Check if the username is already used
        $checkUsernameSql = "SELECT * FROM users WHERE username = '$userInput'";
        $checkUsernameResult = $conn->query($checkUsernameSql);
        
        if ($checkUsernameResult->num_rows > 0) {
            $messageError = "Username is already taken. Please choose another username.";
        } else {
            //Generate a salt value
            function salt($length = 10) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $saltString = '';
                for ($i = 0; $i < $length; $i++) {
                    $saltString .= $characters[random_int(0, $charactersLength - 1)];
                }
                return $saltString;
            }
            //The pepper value
            $pepper = "pepperspray";
            //The Salt valute
            $salt = salt();
            //Combined pepper + password + salt
            $combinedpassword =  $pepper . $confirmpassword . $salt;
            //Hash the combined password using PHP BYCRIPT
            $hashedpassword = password_hash($combinedpassword, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, password, salt, pepper) VALUES ('$userInput', '$hashedpassword', '$salt', '$pepper')";

            if ($conn->query($sql) === TRUE) {
                $messageSuccess = "Account created successfully";
            } else {
                $messageError = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
            $conn->close();
    } else {
            $messageError = "Passwords do not match";
    }
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="cryptography.png">
    <title>Register</title>
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
        <div class="row justify-content-center bg-dark text-white">
            <div class="col mx-4">
                <h1 class="text-center mb-3 mt-3">Sign Up</h1>
                <form action="register.php" method="post" id="registerForm">
                    <br>
                    <div class="form-group mb-3">
                    <input placeholder="Username"  type="username" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group mb-3">
                        <input placeholder="Password (at least 8 characters)" type="password" class="form-control" id="password" name="password" minlength="8" required>
                        <span id='meter' style="font-size: 12px;"></span>
                    </div>
                    <br>
                    <div class="form-group mb-3">
                        <label for="confirmpassword" class="form-label">Repeat Password:</label>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" minlength="8" required>
                        <span id='message' style="font-size: 12px;"></span>
                    </div>
                    <br>
                    <div class="d-grid gap-2 mx-5">
                        <button type="submit" class="btn btn-primary mb-3" id="registerBtn">Sign Up</button>
                        <a type="button"  class="btn btn-outline-danger text-white" href="index.php">Log In</a>
                        <p></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script>
        $('#password, #confirmpassword').on('keyup', function () {
            var password = $('#password').val();
            var confirmPassword = $('#confirmpassword').val();
            var messageElement = $('#message');

            if (password !== '' && confirmPassword !== '') {
                if (password === confirmPassword) {
                    messageElement.html('Password matched').css('color', 'green');
                } else {
                    messageElement.html('Password did not matched').css('color', 'red');
                }
            } else {
                messageElement.html('');
            }

        });

        $('#password').on('keyup', function () {
            var password = $('#password').val();
            var meterElement = $('#meter');
            var minLength = 8;

            if (password === '') {
                meterElement.html('');
                return;
            }

            if (password.length < minLength) {
                meterElement.html('Weak').css('color', 'red');
                return;
            }
            var strength = 0;
            // Check for at least one uppercase letter
            if (/[A-Z]/.test(password)) {
                strength++;
            }
            // Check for at least one lowercase letter
            if (/[a-z]/.test(password)) {
                strength++;
            }
            // Check for at least one digit
            if (/[0-9]/.test(password)) {
                strength++;
            }
            // Check for at least one special character
            if (/[^A-Za-z0-9]/.test(password)) {
                strength++;
            }
            // Determine strength level based on the number of met conditions
            if (strength === 4) {
                meterElement.html('Strong').css('color', 'green');
            } else if (strength >= 2) {
                meterElement.html('Medium').css('color', 'orange');
            } else {
                meterElement.html('Weak').css('color', 'red');
            }
        });
    </script>
</body>
</html>
