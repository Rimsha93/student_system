<?php
session_start();

/* DATABASE CONNECTION */
$conn = mysqli_connect("localhost", "root", "", "student_system");

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

/*  AUTH CHECK */
if(!isset($_SESSION['user'])){
    header("Location: auth.php");
    exit();
}

/* ADD STUDENT */
if(isset($_POST['add'])){
    $reg_no = $_POST['reg_no'];
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $class  = $_POST['class'];
    $course = $_POST['course'];

    $stmt = mysqli_prepare($conn,
        "INSERT INTO students (reg_no, name, email, class, course) VALUES (?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "sssss", $reg_no, $name, $email, $class, $course);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php?msg=add_success");
    exit();
}

/* DELETE STUDENT */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php?msg=delete_success");
    exit();
}

/* UPDATE STUDENT */
if(isset($_POST['update'])){
    $id     = $_POST['id'];
    $reg_no = $_POST['reg_no'];
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $class  = $_POST['class'];
    $course = $_POST['course'];

    $stmt = mysqli_prepare($conn,
        "UPDATE students SET reg_no=?, name=?, email=?, class=?, course=? WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "sssssi", $reg_no, $name, $email, $class, $course, $id);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php?msg=update_success");
    exit();
}

/* FETCH DATA */
$result = mysqli_query($conn, "SELECT * FROM students");

$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $editData = mysqli_fetch_assoc($res);
}

/* SUCCESS MESSAGE */
$msg = "";
if(isset($_GET['msg'])){
    $friendly = [
        "login_success" => "Login Successful!",
        "add_success"   => "Student Added Successfully!",
        "update_success"=> "Student Updated Successfully!",
        "delete_success"=> "Student Deleted Successfully!"
    ];
    $msg = $friendly[$_GET['msg']] ?? "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:'Poppins', sans-serif;
    }
    body{
        background:#f1f5f9;
    }

    .sidebar{
    position:fixed;
    width:240px;
    height:100%;
    background:linear-gradient(180deg,#0f172a,#1e293b);
    padding:30px 20px;
    color:white;
    }
    .sidebar h2{
        font-size:22px;
        margin-bottom:40px;
        font-weight:600;
    }
    .sidebar a{
        display:block;
        padding:12px 15px;
        margin-bottom:10px;
        color:#cbd5e1;
        text-decoration:none;
        border-radius:8px;
        transition:0.3s;
        font-size:14px;
    }
    .sidebar a:hover{
        background:#334155;
        color:#fff;
    }

    .main{
        margin-left:260px;
        padding:40px;
    }

    .header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:30px;
    }
    .header h1{
        font-size:24px;
        color:#0f172a;
        font-weight:600;
    }

    .msg{
        padding:12px;
        border-radius:8px;
        margin-bottom:20px;
        background:#dcfce7;
        color:#065f46;
        font-weight:500;
        text-align:center;
    }

    .cards{
        display:flex;
        gap:20px;
        margin-bottom:30px;
    }
    .card{
        flex:1;
        background:white;
        padding:25px;
        border-radius:12px;
        box-shadow:0 10px 25px rgba(0,0,0,0.05);transition:0.3s;
    }
    .card:hover{
        transform:translateY(-5px);
    }
    .card h3{
        font-size:14px;color:#64748b;
        margin-bottom:10px;
    }
    .card p{
        font-size:26px;
        font-weight:600;
        color:#0f172a;
    }

    .toggle-btn{
        background:#2563eb;
        color:white;
        padding:10px 18px;
        border:none;
        border-radius:8px;
        cursor:pointer;
        font-weight:500;
        transition:0.3s;
    }
    .toggle-btn:hover{
        background:#1d4ed8;
    }

    .form-container{
        background:white;
        padding:30px;
        border-radius:12px;
        box-shadow:0 10px 25px rgba(0,0,0,0.05);
        margin-bottom:30px;
    }
    .form-container h3{
        margin-bottom:20px;
        color:#0f172a;
    }

    input{
        width:100%;
        padding:12px;
        margin-bottom:15px;
        border:1px solid #e2e8f0;
        border-radius:8px;
        font-size:14px;
    }
    input:focus{
        border-color:#2563eb;
        outline:none;
    }

    .submit-btn{
        background:#16a34a;
        color:white;
        padding:12px 20px;
        border:none;
        border-radius:8px;
        cursor:pointer;
        font-weight:500;
    }
    .update-btn{
        background:#f59e0b;
        color:white;
        padding:12px 20px;
        border:none;
        border-radius:8px;
        cursor:pointer;
        font-weight:500;
    }

    table{
        width:100%;
        border-collapse:collapse;
        background:white;
        border-radius:12px;
        overflow:hidden;
        box-shadow:0 10px 25px rgba(0,0,0,0.05);
    }
    th{
        background:#0f172a;
        color:white;
        padding:14px;
        font-size:13px;
    }
    td{
        padding:14px;
        text-align:center;
        border-bottom:1px solid #f1f5f9;
    }
    tr:hover{
        background:#f8fafc;
    }

    .edit{
        background:#3b82f6;
        color:white;padding:6px 12px;
        border-radius:6px;
        text-decoration:none;
        font-size:12px;
    }
    .delete{
        background:#ef4444;
        color:white;
        padding:6px 12px;
        border-radius:6px;
        text-decoration:none;
        font-size:12px;
        }
</style>

<script>
function toggleForm(){
    var form = document.getElementById('addForm');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
}
</script>

</head>
<body>

<div class="sidebar">
    <h2>🎓 Student Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="auth.php?logout=true">Logout</a>
</div>

<div class="main">

<div class="header">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
</div>

<?php if($msg){ echo "<div class='msg'>$msg</div>"; } ?>

    <div class="cards">
        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo mysqli_num_rows($result); ?></p>
        </div>

    <div class="card">
    <h3>Add New Student</h3>
    <button class="toggle-btn" onclick="toggleForm()">+ Add Student</button>
    </div>
    </div>

    <?php if(!$editData){ ?>
    <div class="form-container" id="addForm" style="display:none;">
    <h3>Add Student</h3>
        <form method="POST">
            <input type="text" name="reg_no" placeholder="Registration No" required>
            <input type="text" name="name" placeholder="Student Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="class" placeholder="Class" required>
            <input type="text" name="course" placeholder="Course" required>
            <button type="submit" name="add" class="submit-btn">Add Student</button>
        </form>
    </div>
    <?php } else { ?>
    <div class="form-container">
        <h3>Update Student</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
            <input type="text" name="reg_no" value="<?php echo $editData['reg_no']; ?>" required>
            <input type="text" name="name" value="<?php echo $editData['name']; ?>" required>
            <input type="email" name="email" value="<?php echo $editData['email']; ?>" required>
            <input type="text" name="class" value="<?php echo $editData['class']; ?>" required>
            <input type="text" name="course" value="<?php echo $editData['course']; ?>" required>
            <button type="submit" name="update" class="update-btn">Update Student</button>
        </form>
    </div>
    <?php } ?>

    <h3 style="margin-bottom:15px;color:#0f172a;">Student Records</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Reg No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Class</th>
            <th>Course</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['class']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
            <td>
            <a class="edit" href="dashboard.php?edit=<?php echo $row['id']; ?>">Edit</a>
            <a class="delete" onclick="return confirm('Delete ID <?php echo $row['id']; ?>?')" href="dashboard.php?delete=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
        </table>

        </div>
    </body>
</html>