<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['messageError'] = "Please login first to access this page.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="cryptography.png">
    <title>Home</title>
</head>
<body>
    <div class="container mt-5">
        <div class="container" style="max-width: 800px;">
            <div class="alert alert-success col-6 mx-auto" role="alert" <?php echo isset($_SESSION['messageSuccess']) ? '' : 'style="display: none;"'; ?>>
                <?php echo $_SESSION['messageSuccess']; ?>
            </div>
            <div class="alert alert-danger col-6 mx-auto" role="alert" <?php echo isset($_SESSION['messageError']) ? '' : 'style="display: none;"'; ?>>
                <?php echo $_SESSION['messageError']; ?>
            </div>
        </div>
        <div class="col-4 mx-auto mb-5">
            <?php echo "<h1 class='text-center'>Welcome " . $_SESSION['username'] . "</h1>"; ?>
        </div>
        <form action="logout.php" method="post">
            <div class="d-grid col-2 mx-auto">
                <button type="submit" class="btn btn-danger mb-3" id="logoutBtn">LOGOUT</button>
            </div>
        </form> 
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
