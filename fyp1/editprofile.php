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

$sql = "SELECT userName, email FROM user_signup WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $currentUserName = $row["userName"];
    $currentEmail = $row["email"];
} else {
    $currentUserName = "User";
    $currentEmail = "email";
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
        /* 全局样式 */
        body {
            font-family: Arial, sans-serif;
            background-color: #002B36;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* 个人资料卡片 */
        .profile-container {
            background-color: #F2F2F2;
            width: 90%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* 返回按钮 */
        .back-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        /* 个人资料头部 */
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 2px solid black;
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .profile-info {
            flex-grow: 1;
            text-align: center;
        }

        /* 按钮样式 */
        .logout-btn, .edit-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn {
            background-color: #D32F2F;
            color: white;
        }

        .edit-btn {
            background-color: #E0E0E0;
        }

        /* 历史记录部分 */
        .history-section {
            margin-top: 20px;
        }

        .history-item {
            background-color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }

        .status {
            background-color: #CCCCCC;
            padding: 3px 8px;
            border-radius: 5px;
        }

        /* 报告部分 */
        .report-section {
            margin-top: 20px;
        }

        .report-box {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <button class="back-btn" onclick="goBack()">←</button>

        <div class="profile-header">
            <img src="profile.png" alt="User Avatar" class="profile-img">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($currentUserName); ?></h2>
                <p><?php echo htmlspecialchars($currentEmail); ?></p>
            </div>
            <button class="logout-btn" id="logout-btn">Logout</button>
            <button class="edit-btn" id="edit-profile-btn">Edit</button>
        </div>

        <div class="history-section">
            <h3>History</h3>
            <div class="history-item">
                <strong>Course:</strong> Introduction to Cybersecurity <span class="status">UnFinished</span>
            </div>
            <div class="history-item">
                <strong>Course:</strong> Network Fundamental <span class="status">UnFinished</span>
            </div>
            <div class="history-item">
                <strong>Quiz:</strong> NIST CyberSecurity <span class="status">UnFinished</span>
            </div>
        </div>

        <div class="report-section">
            <h3>Report</h3>
            <div class="report-box">
                <input type="text" placeholder="Now, you are medium risk…">
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = "userdashboard.php"; 
        }

        document.getElementById("logout-btn").addEventListener("click", function() {
            alert("Logged out successfully!");
            window.location.href = "index.php"; 
        });

        document.getElementById("edit-profile-btn").addEventListener("click", function() {
            window.location.href = "editname.php"; 
        });
    </script>
</body>
</html>