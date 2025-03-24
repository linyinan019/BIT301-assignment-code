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

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- 侧边导航栏 -->
    <div class="sidebar">
        <ul>
            <li><i class="fas fa-home"></i> Home</li>
            <li class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</li>
            <li><i class="fas fa-book"></i> Course</li>
            <li><i class="fas fa-file-alt"></i> Report</li>
            <li><i class="fas fa-cog"></i> Settings</li>
            <li id="sidebar-logout" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</li>

        </ul>
    </div>

    <!-- 主内容 -->
    <div class="main-content">
        <div class="header">
            <h1><?php echo htmlspecialchars($currentUserName); ?>'s Dashboard</h1>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <img src="profile.png" alt="User Avatar" class="profile-img" id="profile-btn">
                <div class="dropdown-menu hidden" id="dropdown-menu">
                    <p id="edit-profile"><a href="editprofile.php">Edit Profile</a></p>
                    <p id="dropdown-logout" class="logout">Logout</p>
                    

                </div>
            </div>
        </div>

        <!-- 统计数据 -->
        <div class="stats">
            <div class="stat-box">
                <i class="fas fa-eye"></i>
                <p>5</p>
                <span>Days ago visit</span>
            </div>
            <div class="stat-box">
                <i class="fas fa-question-circle"></i>
                <p>4</p>
                <span>Quiz take</span>
            </div>
            <div class="stat-box">
                <i class="fas fa-graduation-cap"></i>
                <p>6</p>
                <span>Course Completed</span>
            </div>
        </div>

        <!-- 课程进度 -->
        <h2 class="progress-title">Progress</h2>
        <div class="courses">
            <div class="course">
                <h3>Introduction to Cybersecurity</h3>
                <ul>
                    <li>3 lessons left</li>
                    <li>1 quiz left</li>
                </ul>
                <div class="progress-bar"><div class="progress" style="width: 80%;"></div></div>
                <p class="progress-text">80%</p>
            </div>

            <div class="course">
                <h3>Network Fundamental</h3>
                <ul>
                    <li>5 lessons left</li>
                    <li>2 quizzes left</li>
                </ul>
                <div class="progress-bar"><div class="progress" style="width: 50%;"></div></div>
                <p class="progress-text">50%</p>
            </div>

            <div class="course">
                <h3>NIST Cybersecurity</h3>
                <ul>
                    <li>6 lessons left</li>
                    <li>3 quizzes left</li>
                </ul>
                <div class="progress-bar"><div class="progress" style="width: 35%;"></div></div>
                <p class="progress-text">35%</p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>