<?php
/* DATABASE CONNECTION */
include "./config/db.php";

/* ================= ADD MUSCLE ================= */
if (isset($_POST['add_muscle'])) {
    // It's highly recommended to use prepared statements to prevent SQL injection
    $muscle_name = mysqli_real_escape_string($conn, $_POST['muscle_name']);
    $description = mysqli_real_escape_string($conn, $_POST['muscle_desc']);

    // Basic file validation and sanitization is also recommended
    $image = $_FILES['muscle_image']['name'];
    $tmp  = $_FILES['muscle_image']['tmp_name'];

    // Create a unique filename to prevent overwrites
    $unique_image_name = time() . '_' . basename($image);
    $upload_path = "uploads/" . $unique_image_name;

    if (move_uploaded_file($tmp, $upload_path)) {
        mysqli_query(
            $conn,
            "INSERT INTO muscles (muscle_name,image_url,description)
             VALUES ('$muscle_name','$unique_image_name','$description')"
        );
        // Optional: Redirect to the same page to prevent form resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

/* ================= ADD MUSCLE HEAD ================= */
if (isset($_POST['add_head'])) {
    $muscle_id = mysqli_real_escape_string($conn, $_POST['muscle_id']);
    $head_name = mysqli_real_escape_string($conn, $_POST['head_name']);
    $description = mysqli_real_escape_string($conn, $_POST['head_desc']);


    $image = $_FILES['head_image']['name'];
    $tmp  = $_FILES['head_image']['tmp_name'];

    $unique_image_name = time() . '_' . basename($image);
    $upload_path = "uploads/" . $unique_image_name;

    if (move_uploaded_file($tmp, $upload_path)) {
        mysqli_query(
            $conn,
            "INSERT INTO muscle_heads (muscle_id,head_name,image_url,description)
             VALUES ('$muscle_id','$head_name','$unique_image_name','$description')"
        );
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

/* ================= ADD EXERCISE ================= */
if (isset($_POST['add_exercise'])) {

    $head_id     = mysqli_real_escape_string($conn, $_POST['head_id']);
    $name        = mysqli_real_escape_string($conn, $_POST['exercise_name']);
    $equipment   = mysqli_real_escape_string($conn, $_POST['equipment']);
    $sets_reps   = mysqli_real_escape_string($conn, $_POST['sets_reps']);
    $difficulty  = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $description = mysqli_real_escape_string($conn, $_POST['exercise_desc']);

    $sql = "
        INSERT INTO exercises
        (head_id, exercise_name, equipment, sets_reps, difficulty, description)
        VALUES
        ('$head_id', '$name', '$equipment', '$sets_reps', '$difficulty', '$description')
    ";

    if (!mysqli_query($conn, $sql)) {
        die("Exercise Insert Error: " . mysqli_error($conn));
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


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
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- General Setup & Theming --- */
        :root {
            --primary-bg: #0f0f1e;
            --secondary-bg: #1a1a2e;
            --card-bg: #16213e;
            --accent-color: #e94560;
            --accent-hover: #ff5e78;
            --accent-light: #f8b500;
            --text-color: #e0e0e0;
            --heading-color: #ffffff;
            --border-color: #3a3a5c;
            --font-heading: 'Bebas Neue', cursive;
            --font-body: 'Roboto', sans-serif;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--primary-bg);
            color: var(--text-color);
            line-height: 1.6;
            background-image: url('https://www.transparenttextures.com/patterns/dark-denim-3.png');
            overflow-x: hidden;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-family: var(--font-heading);
            font-size: 4rem;
            color: var(--heading-color);
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            position: relative;
            padding-bottom: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--accent-light));
            border-radius: 2px;
        }

        /* --- Navigation Tabs --- */
        .nav-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .nav-tab {
            padding: 15px 25px;
            background: transparent;
            border: none;
            color: var(--text-color);
            font-family: var(--font-heading);
            font-size: 1.5rem;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-tab:hover {
            color: var(--accent-color);
        }

        .nav-tab.active {
            color: var(--accent-color);
        }

        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent-color);
        }

        /* --- Form Card Styling --- */
        .form-container {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .form-container.active {
            display: block;
        }

        .form-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-color);
            overflow: hidden;
            position: relative;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--accent-color), var(--accent-light));
        }

        .form-card h2 {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .form-card h2 i {
            margin-right: 15px;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--heading-color);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            background-color: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-color);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 8px rgba(233, 69, 96, 0.3);
            background-color: rgba(26, 26, 46, 0.8);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 12px 15px;
            background-color: var(--secondary-bg);
            border: 1px dashed var(--accent-color);
            border-radius: 8px;
            color: var(--accent-color);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background-color: rgba(233, 69, 96, 0.1);
        }

        .file-input-label i {
            margin-right: 10px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(45deg, var(--accent-color), var(--accent-hover));
            color: #fff;
            font-family: var(--font-heading);
            font-size: 1.2rem;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            width: 100%;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(233, 69, 96, 0.4);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .form-col {
            flex: 1;
            min-width: 250px;
            padding: 0 10px;
        }

        .success-message {
            background-color: rgba(76, 175, 80, 0.2);
            border-left: 4px solid var(--success-color);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
            display: none;
        }

        .success-message.show {
            display: block;
            animation: slideDown 0.5s ease;
        }

        /* --- Animations --- */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
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
            h1 {
                font-size: 3rem;
            }

            .form-card h2 {
                font-size: 2rem;
            }

            .nav-tab {
                font-size: 1.2rem;
                padding: 10px 15px;
            }

            .form-row {
                flex-direction: column;
            }

            .form-col {
                min-width: 100%;
            }
        }

        /* --- Loading Animation --- */
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="loading" id="loading">
        <div class="loading-spinner"></div>
    </div>

    <div class="admin-container">
        <h1><i class="fas fa-dumbbell"></i> GYM ADMIN</h1>

        <div class="nav-tabs">
            <button class="nav-tab active" data-tab="muscle-form">
                <i class="fas fa-plus-circle"></i> Add Muscle
            </button>
            <button class="nav-tab" data-tab="head-form">
                <i class="fas fa-plus-circle"></i> Add Muscle Head
            </button>
            <button class="nav-tab" data-tab="exercise-form">
                <i class="fas fa-plus-circle"></i> Add Exercise
            </button>
        </div>

        <!-- Success Message -->
        <div class="success-message" id="success-message">
            <i class="fas fa-check-circle"></i> Item added successfully!
        </div>

        <!-- ================= MUSCLE FORM ================= -->
        <div class="form-container active" id="muscle-form">
            <div class="form-card">
                <h2><i class="fas fa-user"></i> Add Muscle</h2>
                <form method="POST" enctype="multipart/form-data" id="muscle-form-element">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="muscle_name">Muscle Name</label>
                                <input type="text" id="muscle_name" name="muscle_name" placeholder="e.g., Biceps Brachii" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="muscle_image">Muscle Image</label>
                                <div class="file-input-wrapper">
                                    <label for="muscle_image" class="file-input-label">
                                        <i class="fas fa-cloud-upload-alt"></i> Choose File...
                                    </label>
                                    <input type="file" id="muscle_image" name="muscle_image" required onchange="this.previousElementSibling.innerHTML = '<i class=\" fas fa-file-image\"></i> ' + this.files[0].name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="muscle_desc">Description</label>
                        <textarea id="muscle_desc" name="muscle_desc" placeholder="A brief description of the muscle group..."></textarea>
                    </div>
                    <button type="submit" name="add_muscle" class="btn">
                        <i class="fas fa-save"></i> Add Muscle
                    </button>
                </form>
            </div>
        </div>

        <!-- ================= MUSCLE HEAD FORM ================= -->
        <div class="form-container" id="head-form">
            <div class="form-card">
                <h2><i class="fas fa-brain"></i> Add Muscle Head</h2>
                <form method="POST" enctype="multipart/form-data" id="head-form-element">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="muscle_id">Select Muscle</label>
                                <select id="muscle_id" name="muscle_id" required>
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
                                <label for="head_name">Head Name</label>
                                <input type="text" id="head_name" name="head_name" placeholder="e.g., Short Head" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="head_image">Head Image</label>
                        <div class="file-input-wrapper">
                            <label for="head_image" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt"></i> Choose File...
                            </label>
                            <input type="file" id="head_image" name="head_image" required onchange="this.previousElementSibling.innerHTML = '<i class=\" fas fa-file-image\"></i> ' + this.files[0].name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="head_desc">Description</label>
                        <textarea id="head_desc" name="head_desc" placeholder="A brief description of the muscle head..."></textarea>
                    </div>
                    <button type="submit" name="add_head" class="btn">
                        <i class="fas fa-save"></i> Add Head
                    </button>
                </form>
            </div>
        </div>

        <!-- ================= EXERCISE FORM ================= -->
        <div class="form-container" id="exercise-form">
            <div class="form-card">
                <h2><i class="fas fa-running"></i> Add Exercise</h2>
                <form method="POST" id="exercise-form-element">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="head_id">Select Muscle Head</label>
                                <select id="head_id" name="head_id" required>
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
                                <label for="exercise_name">Exercise Name</label>
                                <input type="text" id="exercise_name" name="exercise_name" placeholder="e.g., Barbell Curl" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="equipment">Equipment</label>
                                <input type="text" id="equipment" name="equipment" placeholder="e.g., Barbell, Dumbbells">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="difficulty">Difficulty</label>
                                <select id="difficulty" name="difficulty">
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
                                <label for="sets_reps">Sets x Reps</label>
                                <input type="text" id="sets_reps" name="sets_reps" placeholder="e.g., 3 x 12">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exercise_desc">Exercise Description</label>
                        <textarea id="exercise_desc" name="exercise_desc" placeholder="Describe how to perform the exercise..."></textarea>
                    </div>
                    <button type="submit" name="add_exercise" class="btn">
                        <i class="fas fa-save"></i> Add Exercise
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-tab');
            const forms = document.querySelectorAll('.form-container');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and forms
                    tabs.forEach(t => t.classList.remove('active'));
                    forms.forEach(f => f.classList.remove('active'));

                    // Add active class to clicked tab and corresponding form
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Form submission handling
            const formsElements = document.querySelectorAll('form');
            formsElements.forEach(form => {
                form.addEventListener('submit', function() {
                    // Show loading spinner
                    document.getElementById('loading').style.display = 'flex';

                    // Simulate form submission (in a real app, this would be handled by the server)
                    setTimeout(() => {
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('success-message').classList.add('show');

                        // Hide success message after 3 seconds
                        setTimeout(() => {
                            document.getElementById('success-message').classList.remove('show');
                        }, 3000);
                    }, 1000);
                });
            });
        });
    </script>
</body>

</html>