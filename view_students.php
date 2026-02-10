<?php
// Include DB connection and table setup.
require_once 'db.php';

// Fetch all students ordered by newest first.
$students = [];
$query = "SELECT id, student_name, roll_number, email, weight, created_at FROM students ORDER BY id DESC";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $result->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar container">
            <a href="index.html" class="logo">StudentMS</a>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="add_student.php">Add Student</a></li>
                <li><a href="view_students.php" class="active">View Students</a></li>
                <li><a href="index.html#about">About</a></li>
            </ul>
        </nav>
    </header>

    <main class="container page-section">
        <section class="card">
            <h1>Registered Students</h1>

            <?php if (empty($students)): ?>
                <p>No students found. <a href="add_student.php">Add the first student</a>.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                <th>Email</th>
                                <th>Weight (kg)</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo (int) $student['id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['roll_number']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format((float) $student['weight'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <span id="year"></span> StudentMS. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
