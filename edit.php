<?php
**
 * Student Management System - Edit Student
 * 
 * STUDENT PRACTICE TODO:
 * Complete the backend database logic in this file to:
 * 1. Fetch the student's existing details based on the 'id' parameter in the GET request.
 * 2. Pre-populate the form inputs with the student's current information.
 * 3. Process the form submission (POST request), validate input, and execute an SQL UPDATE query.
 * 4. Redirect back to index.php with success or error notifications.
 *

// Include database connection
require_once 'db.php';

$errors = [];
$student = null;
$is_database_connected = false;

// 1. Fetch Existing Student Details (GET Request)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        if ($pdo !== null) {
             ==========================================
             TODO: Student Practice - Fetch Single Student
             Write an SQL query to retrieve the student where id = :id.
             ==========================================
            
            /* Uncomment and implement: */
            
            $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $student = $stmt->fetch();
            $is_database_connected = true;
            
            if (!$student) {
                // Redirect if student ID is not found in database
                header("Location: index.php?status=error&msg=Student+record+not+found.");
                exit();
            }
    
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }

    // Fallback Mock Data for front-end editing preview
    if (!$is_database_connected && $student === null) {
         Mock databases of students matching index.php
        $mock_students = [
            1 => [
                'id' => 1,
                'student_id' => 'STD202601',
                'first_name' => 'Alex',
                'last_name' => 'Morgan',
                'email' => 'alex.morgan@univ.edu',
                'phone' => '555-0199',
                'course' => 'Computer Science',
                'gpa' => 3.85
            ],
            2 => [
                'id' => 2,
                'student_id' => 'STD202602',
                'first_name' => 'Sarah',
                'last_name' => 'Chen',
                'email' => 'sarah.chen@univ.edu',
                'phone' => '555-0182',
                'course' => 'Data Science',
                'gpa' => 3.92
            ],
            3 => [
                'id' => 3,
                'student_id' => 'STD202603',
                'first_name' => 'Liam',
                'last_name' => 'O\'Connor',
                'email' => 'liam.oconnor@univ.edu',
                'phone' => '555-0174',
                'course' => 'Software Engineering',
                'gpa' => 3.45
            ],
            4 => [
                'id' => 4,
                'student_id' => 'STD202604',
                'first_name' => 'Aisha',
                'last_name' => 'Patel',
                'email' => 'aisha.patel@univ.edu',
                'phone' => '555-0165',
                'course' => 'Information Technology',
                'gpa' => 3.78
            ]
        ];

        if (array_key_exists($id, $mock_students)) {
            $student = $mock_students[$id];
        } else {
            // Fallback default mock
            $student = [
                'id' => $id,
                'student_id' => 'STD2026XX',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@univ.edu',
                'phone' => '555-0000',
                'course' => 'Computer Science',
                'gpa' => 3.50
            ];
        }
    }
} else {
     Redirect back if no valid ID provided
    header("Location: index.php?status=error&msg=Invalid+student+ID.");
    exit();
}

// 2. Process Student Record Update (POST Request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     ==========================================
     TODO: Student Practice - Update Form Processing
     ==========================================
    
    /* Step-by-step implementation guide:*/
    
    // 1. Sanitize incoming inputs
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $course = isset($_POST['course']) ? trim($_POST['course']) : '';
    $gpa = isset($_POST['gpa']) ? trim($_POST['gpa']) : '';

    // 2. Validate fields
    if (empty($student_id)) $errors[] = "Student ID is required.";
    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
    if (empty($course)) $errors[] = "Course selection is required.";
    if ($gpa !== '' && (!is_numeric($gpa) || $gpa < 0 || $gpa > 4.0)) $errors[] = "GPA must be a number between 0.00 and 4.00.";

    // 3. Database operation (Only proceed if there are no validation errors)
    if (empty($errors)) {
        try {
            if ($pdo === null) {
                throw new Exception("Database connection is not configured in db.php!");
            }
            
            // Check if student ID exists on ANOTHER student record (prevent duplicate Student IDs)
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_id = ? AND id != ?");
            $check_stmt->execute([$student_id, $id]);
            if ($check_stmt->fetchColumn() > 0) {
                throw new Exception("Another student is already using the ID '$student_id'.");
            }

            // Write UPDATE query
            $sql = "UPDATE students SET 
                        student_id = :student_id,
                        first_name = :first_name, 
                        last_name = :last_name, 
                        email = :email, 
                        phone = :phone, 
                        course = :course, 
                        gpa = :gpa 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'student_id' => $student_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => empty($phone) ? null : $phone,
                'course' => $course,
                'gpa' => $gpa === '' ? null : $gpa,
                'id' => $id
            ]);

            // Redirect back to dashboard on success
            header("Location: index.php?status=success&msg=Student+record+updated+successfully!");
            exit();

        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
    
    
    // For front-end preview demonstration (students will remove this mock block)
    if (empty($errors)) {
        $mock_name = htmlspecialchars($_POST['first_name'] . ' ' . $_POST['last_name']);
        header("Location: index.php?status=success&msg=Student+(" . urlencode($mock_name) . ")+updated+successfully+(Mock+Preview)!");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="container header-content">
            <div class="logo-section">
                <div class="logo-icon">S</div>
                <div>
                    <h1>EduManager</h1>
                    <p class="subtitle">Student Directory Portal</p>
                </div>
            </div>
            <div>
                <a href="index.php" class="btn btn-secondary">
                    <!-- SVG Back Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Directory
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="container" style="max-width: 800px;">
        
        <!-- Display Validation Errors -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Please correct the following errors:</h4>
                    <ul style="list-style: disc; padding-left: 1.25rem; font-size: 0.875rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Student Helper Practice Card -->
        <?php if (!$is_database_connected): ?>
            <div class="instructions-card">
                <h3>🛠️ Student Practice Task: Edit Record Logic</h3>
                <p>To populate this form with actual database data and make updates work:</p>
                <ul>
                    <li>Complete the database SELECT logic inside <code>edit.php</code> (marked as <code>TODO: Student Practice</code>) to load data by ID.</li>
                    <li>Complete the database UPDATE logic to process the form submission (POST request).</li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card">
            <div class="card-header" style="margin-bottom: 2rem;">
                <h2 class="card-title">Edit Student Details</h2>
                <p class="subtitle" style="width: 100%; margin-top: 0.25rem;">Modify the student credentials and enrollment information below.</p>
            </div>

            <!-- Student Form -->
            <form action="edit.php?id=<?php echo htmlspecialchars($student['id']); ?>" method="POST" id="edit-student-form">
                <div class="form-grid">
                    
                    <!-- Student ID / Roll Number -->
                    <div class="form-group">
                        <label for="student_id">Student ID / Roll Number *</label>
                        <input type="text" id="student_id" name="student_id" required 
                               placeholder="e.g. STD202601" 
                               pattern="[A-Za-z0-9-]{3,20}" 
                               title="3-20 alphanumeric characters or dashes"
                               value="<?php echo htmlspecialchars($student['student_id']); ?>">
                    </div>

                    <!-- Course Selection -->
                    <div class="form-group">
                        <label for="course">Assigned Course *</label>
                        <select id="course" name="course" required>
                            <option value="">-- Select Course --</option>
                            <option value="Computer Science" <?php echo ($student['course'] === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="Data Science" <?php echo ($student['course'] === 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                            <option value="Software Engineering" <?php echo ($student['course'] === 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                            <option value="Information Technology" <?php echo ($student['course'] === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                            <option value="Computer Engineering" <?php echo ($student['course'] === 'Computer Engineering') ? 'selected' : ''; ?>>Computer Engineering</option>
                        </select>
                    </div>

                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required 
                               placeholder="e.g. Jane"
                               value="<?php echo htmlspecialchars($student['first_name']); ?>">
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required 
                               placeholder="e.g. Doe"
                               value="<?php echo htmlspecialchars($student['last_name']); ?>">
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               placeholder="e.g. jane.doe@univ.edu"
                               value="<?php echo htmlspecialchars($student['email']); ?>">
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               placeholder="e.g. 555-0100"
                               value="<?php echo htmlspecialchars($student['phone']); ?>">
                    </div>

                    <!-- GPA -->
                    <div class="form-group">
                        <label for="gpa">Current GPA (0.00 - 4.00)</label>
                        <input type="number" id="gpa" name="gpa" step="0.01" min="0.00" max="4.00" 
                               placeholder="e.g. 3.75"
                               value="<?php echo htmlspecialchars($student['gpa']); ?>">
                    </div>

                </div>

                <!-- Form Submits -->
                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Update Record
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
