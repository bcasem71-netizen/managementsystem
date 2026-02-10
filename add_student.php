<?php
// Include DB connection and table setup.
require_once 'db.php';

// Initialize form state variables.
$errors = [];
$successMessage = '';
$studentName = '';
$rollNumber = '';
$email = '';
$weight = '';

// Handle form submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture and sanitize input values.
    $studentName = trim($_POST['student_name'] ?? '');
    $rollNumber = trim($_POST['roll_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $weight = trim($_POST['weight'] ?? '');

    // Basic server-side validation.
    if ($studentName === '') {
        $errors[] = 'Student name is required.';
    }

    if ($rollNumber === '') {
        $errors[] = 'Roll number is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if ($weight === '') {
        $errors[] = 'Weight is required.';
    } elseif (!is_numeric($weight)) {
        $errors[] = 'Weight must be numeric.';
    } elseif ((float) $weight <= 0) {
        $errors[] = 'Weight must be greater than zero.';
    }

    // Insert the student record if no validation errors were found.
    if (empty($errors)) {
        $insertSql = "INSERT INTO students (student_name, roll_number, email, weight) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);

        if ($stmt) {
            $weightValue = (float) $weight;
            $stmt->bind_param('sssd', $studentName, $rollNumber, $email, $weightValue);

            if ($stmt->execute()) {
                $successMessage = 'Student registered successfully!';
                // Clear fields after successful submission.
                $studentName = $rollNumber = $email = $weight = '';
            } else {
                // Handle duplicate email/roll number and general DB errors.
                if ($conn->errno === 1062) {
                    $errors[] = 'Roll number or email already exists.';
                } else {
                    $errors[] = 'Error saving student: ' . $conn->error;
                }
            }

            $stmt->close();
        } else {
            $errors[] = 'Failed to prepare database statement.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar container">
            <a href="index.html" class="logo">StudentMS</a>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="add_student.php" class="active">Add Student</a></li>
                <li><a href="view_students.php">View Students</a></li>
                <li><a href="index.html#about">About</a></li>
            </ul>
        </nav>
    </header>

    <main class="container page-section">
        <section class="card">
            <h1>Add Student</h1>
            <p>Fill in the form below to register a student.</p>

            <!-- Success and error feedback area -->
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Student registration form -->
            <form id="studentForm" action="add_student.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($studentName); ?>" required>
                </div>

                <div class="form-group">
                    <label for="roll_number">Roll Number</label>
                    <input type="text" id="roll_number" name="roll_number" value="<?php echo htmlspecialchars($rollNumber); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($weight); ?>" step="0.01" min="0.01" required>
                </div>

                <button type="submit" class="btn">Save Student</button>
                <p id="formMessage" class="form-message" aria-live="polite"></p>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <span id="year"></span> StudentMS. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
