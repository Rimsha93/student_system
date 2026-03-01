<?php
session_start();
include "config.php";

// REGISTER
if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if($conn->query($sql)){
        echo "<script>alert('Registration Successful!'); window.location='auth.php';</script>";
        exit();
    } else {
        echo "<script>alert('Registration Failed!');</script>";
    }
}

// LOGIN
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user'] = $username;
        header("Location: dashboard.php?msg=login_success");
        exit();
    } else {
        echo "<script>alert('Invalid Credentials');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Authentication</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e293b);
}

/* CARD */
.auth-box{
    width:400px;
    background:white;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,0.2);
    overflow:hidden;
}

/* Tabs */
.tabs{
    display:flex;
}

.tabs button{
    flex:1;
    padding:14px;
    border:none;
    background:#f1f5f9;
    cursor:pointer;
    font-weight:500;
    transition:0.3s;
}

.tabs button.active{
    background:white;
    border-bottom:3px solid #2563eb;
    color:#2563eb;
}

.form-container{
    padding:35px;
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#0f172a;
}

/* Inputs */
input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:1px solid #e2e8f0;
    border-radius:8px;
    transition:0.3s;
}

input:focus{
    border-color:#2563eb;
    outline:none;
}

/* Button */
.submit-btn{
    width:100%;
    padding:12px;
    background:#2563eb;
    border:none;
    border-radius:8px;
    color:white;
    font-weight:500;
    cursor:pointer;
    transition:0.3s;
}

.submit-btn:hover{
    background:#1d4ed8;
}

/* Hide forms */
.hidden{
    display:none;
}

.brand{
    position:absolute;
    top:30px;
    left:40px;
    color:white;
    font-weight:600;
    font-size:20px;
}
</style>

<script>
function showLogin(){
    document.getElementById("loginForm").classList.remove("hidden");
    document.getElementById("registerForm").classList.add("hidden");

    document.getElementById("loginTab").classList.add("active");
    document.getElementById("registerTab").classList.remove("active");
}

function showRegister(){
    document.getElementById("registerForm").classList.remove("hidden");
    document.getElementById("loginForm").classList.add("hidden");

    document.getElementById("registerTab").classList.add("active");
    document.getElementById("loginTab").classList.remove("active");
}
</script>

</head>
<body>

<div class="brand">🎓 Student Panel</div>

<div class="auth-box">

    <div class="tabs">
        <button id="loginTab" class="active" onclick="showLogin()">Login</button>
        <button id="registerTab" onclick="showRegister()">Sign Up</button>
    </div>

    <div class="form-container">

        <!-- LOGIN -->
        <div id="loginForm">
            <h2>Welcome Back</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button class="submit-btn" name="login">Login</button>
            </form>
        </div>

        <!-- REGISTER -->
        <div id="registerForm" class="hidden">
            <h2>Create Account</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password (min 4 characters)" required>
                <button class="submit-btn" name="register">Sign Up</button>
            </form>
        </div>

    </div>

</div>

</body>
</html>