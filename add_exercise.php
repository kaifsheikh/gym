<?php
include "./config/db.php";

/* ================= FETCH DATA ================= */
$muscles = mysqli_query($conn, "SELECT * FROM muscles");

$heads = mysqli_query($conn, "
    SELECT 
        muscle_heads.head_id,
        muscle_heads.head_name,
        muscles.muscle_name
    FROM muscle_heads
    INNER JOIN muscles 
        ON muscle_heads.muscle_id = muscles.muscle_id
");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- Theme Variables --- */
        :root {
            /* Light theme colors */
            --color-text-primary: #1F2936;
            --color-text-placeholder: #798EAE;
            --color-bg-primary: #f9fafb;
            --color-bg-secondary: #ECECFD;
            --color-bg-sidebar: #FFFFFF;
            --color-border-hr: #E2E8F0;
            --color-hover-primary: #695CFE;
            --color-hover-secondary: #e2e2fb;
            --color-shadow: rgba(0, 0, 0, 0.05);
            --color-card-bg: #FFFFFF;
            --color-input-bg: #FFFFFF;
            --color-btn-primary: #695CFE;
            --color-btn-primary-hover: #5142E4;
            --color-btn-secondary: #F3F4F6;
            --color-sidebar-text: #374151;
            --color-sidebar-active: #F9FAFB;
            --font-family: 'Inter', sans-serif;
        }

        body.dark-theme {
            /* Dark theme colors */
            --color-text-primary: #F1F5F9;
            --color-text-placeholder: #A6B7D2;
            --color-bg-primary: #111827;
            --color-bg-secondary: #3D4859;
            --color-bg-sidebar: #1f2937;
            --color-border-hr: #3B475C;
            --color-hover-secondary: #48566a;
            --color-shadow: rgba(0, 0, 0, 0.3);
            --color-card-bg: #1F2937;
            --color-input-bg: #374151;
            --color-btn-primary: #695CFE;
            --color-btn-primary-hover: #5142E4;
            --color-btn-secondary: #374151;
            --color-sidebar-text: #D1D5DB;
            --color-sidebar-active: #374151;
        }

        /* --- Global Styles --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--color-bg-primary);
            color: var(--color-text-primary);
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 250px;
            background-color: var(--color-bg-sidebar);
            padding: 30px 20px;
            border-right: 1px solid var(--color-border-hr);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            color: var(--color-text-primary);
            font-weight: 700;
            font-size: 1.5rem;
        }

        .logo i {
            margin-right: 12px;
            color: var(--color-btn-primary);
            font-size: 1.8rem;
        }

        .nav-menu {
            list-style: none;
            margin-bottom: auto;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background-color: var(--color-hover-secondary);
            color: var(--color-hover-primary);
        }

        .nav-link.active {
            background-color: var(--color-sidebar-active);
            color: var(--color-hover-primary);
            font-weight: 600;
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        /* --- Theme Toggle --- */
        .theme-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background-color: var(--color-btn-secondary);
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }

        .theme-toggle-label {
            display: flex;
            align-items: center;
            color: var(--color-text-primary);
            font-weight: 500;
        }

        .theme-toggle-label i {
            margin-right: 8px;
        }

        .toggle-switch {
            position: relative;
            width: 48px;
            height: 24px;
            background-color: var(--color-border-hr);
            border-radius: 24px;
            transition: all 0.3s ease;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        body.dark-theme .toggle-switch {
            background-color: var(--color-btn-primary);
        }

        body.dark-theme .toggle-switch::after {
            transform: translateX(24px);
        }

        /* --- Main Content --- */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: var(--color-text-placeholder);
            font-size: 1rem;
        }

        /* --- Tabs --- */
        .tabs {
            display: flex;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--color-border-hr);
        }

        .tab {
            padding: 12px 20px;
            font-weight: 500;
            color: var(--color-text-placeholder);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .tab:hover {
            color: var(--color-text-primary);
        }

        .tab.active {
            color: var(--color-btn-primary);
            border-bottom-color: var(--color-btn-primary);
        }

        /* --- Form Card --- */
        .form-card {
            background-color: var(--color-card-bg);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px var(--color-shadow);
            margin-bottom: 24px;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .form-card.active {
            display: block;
        }

        .form-header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .form-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background-color: var(--color-bg-secondary);
            border-radius: 12px;
            margin-right: 16px;
            color: var(--color-btn-primary);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        /* --- Form Elements --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--color-text-primary);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--color-input-bg);
            border: 1px solid var(--color-border-hr);
            border-radius: 8px;
            color: var(--color-text-primary);
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--color-btn-primary);
            box-shadow: 0 0 0 3px rgba(105, 92, 254, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* --- File Upload --- */
        .file-upload {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-color: var(--color-input-bg);
            border: 1px dashed var(--color-border-hr);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload:hover {
            border-color: var(--color-btn-primary);
        }

        .file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--color-btn-primary);
            margin-bottom: 8px;
        }

        .file-upload-text {
            color: var(--color-text-placeholder);
            font-size: 0.9rem;
        }

        /* --- Form Layout --- */
        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-col {
            flex: 1;
        }

        /* --- Buttons --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            background-color: var(--color-btn-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background-color: var(--color-btn-primary-hover);
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-block {
            width: 100%;
        }

        /* --- Success Message --- */
        .success-message {
            display: none;
            padding: 16px;
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981;
            border-radius: 4px;
            margin-bottom: 24px;
            color: #047857;
            animation: slideDown 0.3s ease;
        }

        .success-message.show {
            display: block;
        }

        /* --- Animations --- */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }

            to {
                opacity: 1;
                max-height: 100px;
            }
        }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo span,
            .nav-link span,
            .theme-toggle-label span {
                display: none;
            }

            .logo {
                justify-content: center;
                margin-bottom: 30px;
            }

            .nav-link {
                justify-content: center;
                padding: 12px;
            }

            .nav-link i {
                margin: 0;
            }

            .theme-toggle {
                justify-content: center;
                padding: 12px;
            }

            .theme-toggle-label {
                display: none;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- ================= SIDE BAR Start ================= -->
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-dumbbell"></i>
                <span>GYM ADMIN</span>
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active" data-tab="muscle-form">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Muscle</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="head-form">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Muscle Head</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="exercise-form">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Exercise</span>
                    </a>
                </li>
            </ul>

            <div class="theme-toggle" id="theme-toggle">
                <div class="theme-toggle-label">
                    <i class="fas fa-moon" id="theme-icon"></i>
                    <span>Dark Mode</span>
                </div>
                <div class="toggle-switch"></div>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Admin Panel</h1>
                <p class="page-subtitle">Manage your gym database</p>
            </div>

            <div class="success-message" id="success-message">
                <i class="fas fa-check-circle"></i> Item added successfully!
            </div>

            <div class="tabs">
                <div class="tab active" data-tab="muscle-form">Muscle</div>
                <div class="tab" data-tab="head-form">Muscle Head</div>
                <div class="tab" data-tab="exercise-form">Exercise</div>
            </div>
            <!-- ================= SIDE BAR End ================= -->

            <!-- ================= MUSCLE FORM ================= -->
            <?php

            if (isset($_POST['add_muscle'])) {
                // It's highly recommended to use prepared statements to prevent SQL injection
                $muscle_name = mysqli_real_escape_string($conn, $_POST['muscle_name']);

                // Basic file validation and sanitization is also recommended
                $image = $_FILES['muscle_image']['name'];
                $tmp  = $_FILES['muscle_image']['tmp_name'];

                // Create a unique filename to prevent overwrites
                $unique_image_name = time() . '_' . basename($image);
                $upload_path = "uploads/" . $unique_image_name;

                if (move_uploaded_file($tmp, $upload_path)) {
                    mysqli_query(
                        $conn,
                        "INSERT INTO muscles (muscle_name,image_url)
             VALUES ('$muscle_name','$unique_image_name')"
                    );
                    // Optional: Redirect to the same page to prevent form resubmission on refresh
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
            }


            ?>
            <div class="form-card active" id="muscle-form">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="form-title">Add Muscle</h2>
                </div>
                <form method="POST" enctype="multipart/form-data" id="muscle-form-element">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="muscle_name">Muscle Name</label>
                                <input type="text" class="form-input" id="muscle_name" name="muscle_name" placeholder="e.g., Biceps Brachii" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="muscle_image">Muscle Image</label>
                                <div class="file-upload">
                                    <input type="file" id="muscle_image" name="muscle_image" required>
                                    <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                    <span class="file-upload-text">Choose file or drag here</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="add_muscle" class="btn btn-block">
                        <i class="fas fa-save"></i> Add Muscle
                    </button>
                </form>
            </div>

            <!-- ================= MUSCLE FORM End ================= -->



            <!-- ================= MUSCLE HEAD FORM ================= -->
            <?php

            /* ================= ADD MUSCLE HEAD ================= */
            if (isset($_POST['add_head'])) {
                $muscle_id  = mysqli_real_escape_string($conn, $_POST['muscle_id']);
                $head_name  = mysqli_real_escape_string($conn, $_POST['head_name']);
                $description = mysqli_real_escape_string($conn, $_POST['head_desc']);

                mysqli_query(
                    $conn,
                    "INSERT INTO muscle_heads (muscle_id, head_name, description)
                                VALUES ('$muscle_id', '$head_name', '$description')"
                );

                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            ?>

            <div class="form-card" id="head-form">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h2 class="form-title">Add Muscle Head</h2>
                </div>
                <form method="POST" id="head-form-element">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="muscle_id">Select Muscle</label>
                                <select class="form-select" id="muscle_id" name="muscle_id" required>
                                    <option value="" disabled selected>Select a Muscle...</option>
                                    <?php
                                    // Reset the pointer for the muscles result set to the beginning
                                    mysqli_data_seek($muscles, 0);
                                    while ($m = mysqli_fetch_assoc($muscles)) { ?>
                                        <option value="<?php echo $m['muscle_id']; ?>">
                                            <?php echo htmlspecialchars($m['muscle_name']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="head_name">Head Name</label>
                                <input type="text" class="form-input" id="head_name" name="head_name" placeholder="e.g., Short Head" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="head_desc">Description</label>
                        <textarea class="form-textarea" id="head_desc" name="head_desc" placeholder="A brief description of the muscle head..."></textarea>
                    </div>
                    <button type="submit" name="add_head" class="btn btn-block">
                        <i class="fas fa-save"></i> Add Head
                    </button>
                </form>
            </div>
            <!-- ================= MUSCLE HEAD FORM End ================= -->




            <!-- ================= EXERCISE FORM ================= -->
            <?php

            if (isset($_POST['add_exercise'])) {

                $head_id     = mysqli_real_escape_string($conn, $_POST['head_id']);
                $name        = mysqli_real_escape_string($conn, $_POST['exercise_name']);
                $equipment   = mysqli_real_escape_string($conn, $_POST['equipment']);
                $sets_reps   = mysqli_real_escape_string($conn, $_POST['sets_reps']);
                $difficulty  = mysqli_real_escape_string($conn, $_POST['difficulty']);

                $sql = "
                INSERT INTO exercises
                (head_id, exercise_name, equipment, sets_reps, difficulty)
                VALUES
                ('$head_id', '$name', '$equipment', '$sets_reps', '$difficulty')
            ";

                if (!mysqli_query($conn, $sql)) {
                    die("Exercise Insert Error: " . mysqli_error($conn));
                }

                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            ?>

            <div class="form-card" id="exercise-form">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fas fa-running"></i>
                    </div>
                    <h2 class="form-title">Add Exercise</h2>
                </div>
                <form method="POST" id="exercise-form-element">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="head_id">Select Muscle Head</label>
                                <select class="form-select" id="head_id" name="head_id" required>
                                    <option value="" disabled selected>Select a Muscle Head...</option>
                                    <?php
                                    // Reset the pointer for the heads result set to the beginning
                                    mysqli_data_seek($heads, 0);
                                    while ($h = mysqli_fetch_assoc($heads)) { ?>
                                        <option value="<?php echo $h['head_id']; ?>">
                                            <?php echo htmlspecialchars($h['muscle_name']); ?> → <?php echo htmlspecialchars($h['head_name']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="exercise_name">Exercise Name</label>
                                <input type="text" class="form-input" id="exercise_name" name="exercise_name" placeholder="e.g., Barbell Curl" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="equipment">Equipment</label>
                                <input type="text" class="form-input" id="equipment" name="equipment" placeholder="e.g., Barbell, Dumbbells">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="difficulty">Difficulty</label>
                                <select class="form-select" id="difficulty" name="difficulty">
                                    <option value="Beginner">Beginner</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="sets_reps">Sets x Reps</label>
                                <input type="text" class="form-input" id="sets_reps" name="sets_reps" placeholder="e.g., 3 x 12">
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="add_exercise" class="btn btn-block">
                        <i class="fas fa-save"></i> Add Exercise
                    </button>
                </form>
            </div>
            <!-- ================= EXERCISE FORM ================= -->



        </main>
    </div>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const navLinks = document.querySelectorAll('.nav-link');
            const forms = document.querySelectorAll('.form-card');

            function switchTab(tabId) {
                // Remove active class from all tabs, nav links and forms
                tabs.forEach(tab => tab.classList.remove('active'));
                navLinks.forEach(link => link.classList.remove('active'));
                forms.forEach(form => form.classList.remove('active'));

                // Add active class to selected tab, nav link and form
                document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
                document.querySelector(`.nav-link[data-tab="${tabId}"]`).classList.add('active');
                document.getElementById(tabId).classList.add('active');
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });

            // Theme toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const body = document.body;

            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                body.classList.add('dark-theme');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }

            themeToggle.addEventListener('click', function() {
                body.classList.toggle('dark-theme');

                if (body.classList.contains('dark-theme')) {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                    localStorage.setItem('theme', 'light');
                }
            });

            // File upload label update
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0]?.name || 'Choose file or drag here';
                    const label = this.nextElementSibling.nextElementSibling;
                    if (label && label.classList.contains('file-upload-text')) {
                        label.textContent = fileName;
                    }
                });
            });

            // Form submission handling
            const formsElements = document.querySelectorAll('form');
            formsElements.forEach(form => {
                form.addEventListener('submit', function() {
                    // Show success message (in a real app, this would be handled after successful server response)
                    setTimeout(() => {
                        document.getElementById('success-message').classList.add('show');

                        // Hide success message after 3 seconds
                        setTimeout(() => {
                            document.getElementById('success-message').classList.remove('show');
                        }, 3000);
                    }, 500);
                });
            });
        });
    </script>

</body>

</html>