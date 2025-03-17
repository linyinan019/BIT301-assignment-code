<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "fyp"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php"); 
    exit();
}

$userId = $_SESSION["user_id"];

$sql = "SELECT userName FROM user_signup WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $currentUserName = $row["userName"];
} else {
    $currentUserName = "User"; 
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    $newUserName = trim($_POST["userName"]);
    
    if (!empty($newUserName)) {
        
        $updateSql = "UPDATE user_signup SET userName = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $newUserName, $userId);
        
        if ($updateStmt->execute()) {
            $_SESSION["userName"] = $newUserName; 
            $currentUserName = $newUserName; 
            echo "<script>alert('Name updated successfully!');</script>";
        } else {
            echo "<script>alert('Failed to update name. Please try again.');</script>";
        }
        $updateStmt->close();
    } else {
        echo "<script>alert('Please enter a valid name.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .profile-container {
            background-color: #ff7043;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        input {
            width: 80%;
            padding: 8px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        button {
            width: 85%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #save-name {
            background-color: #4CAF50;
            color: white;
        }

        #back {
            background-color: #d32f2f;
            color: white;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="profile.png" alt="User Avatar" class="profile-img">
        <h2>Hello, <?php echo htmlspecialchars($currentUserName); ?>! Edit your name</h2>
        <form action="editprofile.php" method="post">
            <input type="text" id="name-input" name="userName" placeholder="Enter your name" value="<?php echo htmlspecialchars($currentUserName); ?>">
            <button id="save-name" name="save">Save</button>
        </form>
        <button id="back">Back</button>
    </div>

    <script>
        const backButton = document.getElementById("back");
        backButton.addEventListener("click", function() {
            window.location.href = "editprofile.php";
        });
    </script>
</body>
</html>