# 🎓 Student Management System
A secure and responsive web-based student management dashboard built with PHP, MySQL, and modern UI styling.
The system allows authenticated users to manage student records with full CRUD functionality through an intuitive admin panel.

# Features
✅ User authentication (login session protected)
✅ Add new students
✅ Edit existing student records
✅ Delete students with confirmation
✅ Professional dashboard UI
✅ Prepared statements (SQL injection protection)
✅ Success notifications for actions
✅ Responsive layout with sidebar navigation

# 🖥️ Tech Stack
--Technology	Purpose
--PHP (Core)	Backend logic
--MySQL	Database
--HTML5	Structure
--CSS3	Styling
--JavaScript	UI interaction
--Google Fonts (Poppins)	Typography

# 📂 Project Structure
student-management-system/
│
├── dashboard.php      # Main admin dashboard
├── auth.php           # Login / logout handling
├── database.sql       # Database structure
└── README.md

# Live Demo
https://drive.google.com/file/d/1PBA_dvhl6NQjJ5kVV-4pYahTitw0F19e/view?usp=drivesdk

# ⚙️ Installation & Setup
1️⃣ Clone Repository
git clone https://github.com/Rimsha93/student_system.git

2️⃣ Move Project to Server
Place folder inside:
htdocs/        (XAMPP)
www/           (WAMP)

3️⃣ Create Database
Open phpMyAdmin → create database:
student_system

4️⃣ Create Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reg_no VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    class VARCHAR(50) NOT NULL,
    course VARCHAR(100) NOT NULL
);

5️⃣ Configure Database Connection
Inside dashboard.php:
$conn = mysqli_connect("localhost", "root", "", "student_system");
Update credentials if needed.

6️⃣ Run Project
Open in browser:
http://localhost/student-management-system/auth.php
Login → Access dashboard → Manage students.

# 🔐 Security Features
-Session-based authentication
-Prepared SQL statements
-Output escaping (XSS protection)
-Access control for dashboard

# 🚀 Future Improvements
-Search & filter students
-Pagination
-Profile image upload
-Role-based admin system
-REST API support
-Laravel version
