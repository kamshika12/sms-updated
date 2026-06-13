<?php
/**
 * Student Management System - Dashboard
 * 
 * STUDENT PRACTICE TODO:
 * Read the instructions card on the page and complete the PHP logic to:
 * 1. Fetch metrics from the database (total students, average GPA, active courses).
 * 2. Fetch student records (implementing search functionality and displaying results).
 */

// Include the database configuration
require_once 'db.php';

// Initialize variables
$students = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$is_database_connected = false;

// Default values for metrics
$total_students = 0;
$average_gpa = 0.00;
$active_courses = 0;

// Handle success/error feedback messages from Redirects (Add/Edit/Delete actions)
$message = '';
$message_type = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Operation completed successfully.';
        $message_type = 'success';
    } elseif ($_GET['status'] === 'error') {
        $message = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'An error occurred. Please try again.';
        $message_type = 'danger';
    }
}

try {
    // Check if the database connection ($pdo) was successfully created in db.php
    if ($pdo !== null) {
        
        // ==========================================
        // TODO: Student Practice - Fetch Metrics
        // Write SQL queries to populate these stats:
        // ==========================================
        
        /* Uncomment and implement:*/
        
        // 1. Get total students
        $stmt_total = $pdo->query("SELECT COUNT(*) FROM students");
        $total_students = $stmt_total->fetchColumn();

        // 2. Get average GPA
        $stmt_gpa = $pdo->query("SELECT AVG(gpa) FROM students");
        $average_gpa = round($stmt_gpa->fetchColumn() ?? 0.0, 2);

        // 3. Get unique courses count
        $stmt_courses = $pdo->query("SELECT COUNT(DISTINCT course) FROM students");
        $active_courses = $stmt_courses->fetchColumn();
        
        

        // ==========================================
        // TODO: Student Practice - Fetch Student Records
        // Write an SQL query to retrieve students.
        // Support search filtering if the user entered a keyword.
        // ==========================================
        
        /* Uncomment and implement:*/
        
        if (!empty($search)) {
            // Search query filtering by student_id, first_name, last_name, email, or course
            $query = "SELECT * FROM students WHERE 
                      student_id LIKE :search OR 
                      first_name LIKE :search OR 
                      last_name LIKE :search OR 
                      email LIKE :search OR 
                      course LIKE :search 
                      ORDER BY created_at DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['search' => "%$search%"]);
        } else {
            // Default select query (latest first)
            $query = "SELECT * FROM students ORDER BY created_at DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        }
        
        $students = $stmt->fetchAll();
        $is_database_connected = true;
        
        
    }
} catch (PDOException $e) {
    // If the table doesn't exist yet, we catch the exception and fall back to mock data
    $message = "Database error: " . $e->getMessage() . ". Showing mock preview data below.";
    $message_type = "danger";
}

// Fallback Mock Data (For demonstration when DB is not yet connected/setup)
if (!$is_database_connected && empty($students)) {
    $students = [
        [
            'id' => 1,
            'student_id' => 'STD202601',
            'first_name' => 'Alex',
            'last_name' => 'Morgan',
            'email' => 'alex.morgan@univ.edu',
            'phone' => '555-0199',
            'course' => 'Computer Science',
            'gpa' => 3.85,
            'created_at' => '2026-06-10 09:00:00'
        ],
        [
            'id' => 2,
            'student_id' => 'STD202602',
            'first_name' => 'Sarah',
            'last_name' => 'Chen',
            'email' => 'sarah.chen@univ.edu',
            'phone' => '555-0182',
            'course' => 'Data Science',
            'gpa' => 3.92,
            'created_at' => '2026-06-11 10:30:00'
        ],
        [
            'id' => 3,
            'student_id' => 'STD202603',
            'first_name' => 'Liam',
            'last_name' => 'O\'Connor',
            'email' => 'liam.oconnor@univ.edu',
            'phone' => '555-0174',
            'course' => 'Software Engineering',
            'gpa' => 3.45,
            'created_at' => '2026-06-12 14:15:00'
        ],
        [
            'id' => 4,
            'student_id' => 'STD202604',
            'first_name' => 'Aisha',
            'last_name' => 'Patel',
            'email' => 'aisha.patel@univ.edu',
            'phone' => '555-0165',
            'course' => 'Information Technology',
            'gpa' => 3.78,
            'created_at' => '2026-06-13 11:22:00'
        ]
    ];
    $total_students = count($students);
    $average_gpa = 3.75;
    $active_courses = 4;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
    <meta name="description" content="Manage your school's student directory, details, GPA metrics, and courses.">
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
                <a href="add.php" class="btn btn-primary" id="add-student-btn">
                    <!-- SVG Add Icon -->
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Add Student
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="container">
        
        <!-- Alerts/Feedback Messages -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                <?php if ($message_type === 'success'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <?php endif; ?>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Student Helper Practice Card -->
        <?php if (!$is_database_connected): ?>
            <div class="instructions-card">
                <h3>🛠️ Student Practice Task: Connect to Database</h3>
                <p>You are currently seeing <strong>mock preview data</strong>. To complete your CRUD application backend:</p>
                <ul>
                    <li>Open <code>db.php</code> and write the code to establish a PDO database connection.</li>
                    <li>Import the schema from <code>database.sql</code> into your local database.</li>
                    <li>Uncomment the database PHP queries marked as <code>TODO: Student Practice</code> inside <code>index.php</code>, <code>add.php</code>, <code>edit.php</code>, and <code>delete.php</code>.</li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Statistics Dashboard -->
        <section class="stats-grid">
            <!-- Total Students -->
            <div class="stat-card">
                <div class="stat-icon primary">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $total_students; ?></div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>

            <!-- Average GPA -->
            <div class="stat-card">
                <div class="stat-icon success">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($average_gpa, 2); ?></div>
                    <div class="stat-label">Average GPA</div>
                </div>
            </div>

            <!-- Active Courses -->
            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $active_courses; ?></div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>
        </section>

        <!-- Directory Table Card -->
        <section class="card">
            <div class="card-header">
                <h2 class="card-title">Student Directory</h2>
                
                <!-- Search Form -->
                <form action="index.php" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <!-- SVG Search Icon -->
                        <span class="search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" name="search" placeholder="Search students..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <button type="submit" class="btn btn-secondary">Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="index.php" class="btn btn-secondary btn-sm" style="display: flex; align-items: center;">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>GPA</th>
                            <th>Date Joined</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📂</div>
                                        <p>No student records found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td>
                                        <span class="badge primary"><?php echo htmlspecialchars($student['student_id']); ?></span>
                                    </td>
                                    <td style="font-weight: 500;">
                                        <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phone'] ? $student['phone'] : '-'); ?></td>
                                    <td><?php echo htmlspecialchars($student['course']); ?></td>
                                    <td>
                                        <?php 
                                            $gpa = (float)$student['gpa'];
                                            $gpa_class = 'gpa-low';
                                            if ($gpa >= 3.5) {
                                                $gpa_class = 'gpa-high';
                                            } elseif ($gpa >= 3.0) {
                                                $gpa_class = 'gpa-mid';
                                            }
                                        ?>
                                        <span class="<?php echo $gpa_class; ?>"><?php echo number_format($gpa, 2); ?></span>
                                    </td>
                                    <td style="font-size: 0.85rem; color: var(--text-muted);">
                                        <?php echo date('M d, Y', strtotime($student['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <!-- Edit Button -->
                                            <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-secondary btn-sm" title="Edit Student">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <!-- Delete Button -->
                                            <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" title="Delete Student" onclick="return confirm('Are you sure you want to delete this student record?');">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>
