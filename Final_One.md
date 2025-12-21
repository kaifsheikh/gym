```php
<?php
include "./config/db.php";

// --- PHP LOGIC FOR FORM SUBMISSIONS ---

/* ================= ADD MUSCLE ================= */
if (isset($_POST['add_muscle'])) {
    $muscle_name = mysqli_real_escape_string($conn, $_POST['muscle_name']);

    // Handle file upload
    $image = $_FILES['muscle_image']['name'];
    $tmp  = $_FILES['muscle_image']['tmp_name'];
    $unique_image_name = time() . '_' . basename($image);
    $upload_path = "uploads/" . $unique_image_name;

    if (move_uploaded_file($tmp, $upload_path)) {
        mysqli_query(
            $conn,
            "INSERT INTO muscles (muscle_name, image_url) VALUES ('$muscle_name','$unique_image_name')"
        );
    } else {
        // Handle file upload error if needed
        die("Error uploading muscle image.");
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ================= ADD MUSCLE HEAD ================= */
if (isset($_POST['add_head'])) {
    $muscle_id  = mysqli_real_escape_string($conn, $_POST['muscle_id']);
    $head_name  = mysqli_real_escape_string($conn, $_POST['head_name']);
    $description = mysqli_real_escape_string($conn, $_POST['head_desc']);

    mysqli_query(
        $conn,
        "INSERT INTO muscle_heads (muscle_id, head_name, description) VALUES ('$muscle_id', '$head_name', '$description')"
    );

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ================= ADD EXERCISE ================= */
if (isset($_POST['add_exercise'])) {
    $head_id     = mysqli_real_escape_string($conn, $_POST['head_id']);
    $name        = mysqli_real_escape_string($conn, $_POST['exercise_name']);
    $equipment   = mysqli_real_escape_string($conn, $_POST['equipment']);
    $sets_reps   = mysqli_real_escape_string($conn, $_POST['sets_reps']);
    $difficulty  = mysqli_real_escape_string($conn, $_POST['difficulty']);

    $sql = "INSERT INTO exercises (head_id, exercise_name, equipment, sets_reps, difficulty) VALUES ('$head_id', '$name', '$equipment', '$sets_reps', '$difficulty')";

    if (!mysqli_query($conn, $sql)) {
        die("Exercise Insert Error: " . mysqli_error($conn));
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// --- AJAX LOGIC FOR MODAL ---
if (isset($_GET['get_exercise_details'])) {
    $exercise_id = intval($_GET['exercise_id']);

    $query = "
        SELECT 
            e.exercise_name,
            e.equipment,
            e.sets_reps,
            e.difficulty,
            mh.head_name,
            mh.description AS head_description,
            m.muscle_name,
            m.image_url AS muscle_image
        FROM exercises e
        INNER JOIN muscle_heads mh ON mh.head_id = e.head_id
        INNER JOIN muscles m ON m.muscle_id = mh.muscle_id
        WHERE e.exercise_id = $exercise_id
    ";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo json_encode(['error' => 'Query Failed: ' . mysqli_error($conn)]);
        exit();
    }

    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
    exit();
}


// --- FETCH DATA FOR DISPLAY ---
$muscles = mysqli_query($conn, "SELECT * FROM muscles");
$heads = mysqli_query($conn, "SELECT mh.head_id, mh.head_name, m.muscle_name FROM muscle_heads mh INNER JOIN muscles m ON mh.muscle_id = m.muscle_id");

// Fetch workout data for cards
$query = "
    SELECT 
        m.muscle_name,
        mh.head_id,
        mh.head_name,
        e.exercise_id,
        e.exercise_name,
        e.equipment,
        e.sets_reps,
        e.difficulty
    FROM muscle_heads mh
    INNER JOIN muscles m ON m.muscle_id = mh.muscle_id
    INNER JOIN exercises e ON e.head_id = mh.head_id
    ORDER BY m.muscle_name, mh.head_name
";
$result = mysqli_query($conn, $query);
if (!$result) die("Database Query Failed: " . mysqli_error($conn));

$cards = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cardKey = $row['muscle_name'] . ' - ' . $row['head_name'];
    if (!isset($cards[$cardKey])) {
        $cards[$cardKey] = ['muscle' => $row['muscle_name'], 'head' => $row['head_name'], 'difficulty' => $row['difficulty'], 'exercises' => []];
    }
    $cards[$cardKey]['exercises'][] = ['exercise_id' => $row['exercise_id'], 'exercise_name' => $row['exercise_name'], 'equipment' => $row['equipment'], 'sets_reps' => $row['sets_reps']];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gym Admin Panel</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        /* --- Theme Variables --- */
        :root {
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
            --color-sidebar-text: #D1D5DB;
            --color-sidebar-active: #374151;
        }

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
            cursor: pointer;
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

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-col {
            flex: 1;
        }

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

        .workout-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .workout-card {
            background-color: var(--color-card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 12px var(--color-shadow);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .workout-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px var(--color-shadow);
        }

        .workout-header {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--color-border-hr);
        }

        .workout-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: var(--color-bg-secondary);
            border-radius: 12px;
            margin-right: 16px;
            color: var(--color-btn-primary);
        }

        .workout-icon .material-symbols-rounded {
            font-size: 28px;
        }

        .workout-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 8px;
        }

        .difficulty-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .difficulty-beginner {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .difficulty-intermediate {
            background-color: rgba(251, 146, 60, 0.1);
            color: #fb923c;
        }

        .difficulty-advanced {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .exercise-list {
            padding: 8px 0;
        }

        .exercise-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .exercise-item:hover {
            background-color: var(--color-hover-secondary);
        }

        .exercise-icon {
            color: var(--color-text-placeholder);
            margin-right: 12px;
            transition: color 0.2s ease;
        }

        .exercise-item:hover .exercise-icon {
            color: var(--color-btn-primary);
        }

        .exercise-info {
            flex: 1;
        }

        .exercise-name {
            font-weight: 500;
            color: var(--color-text-primary);
            margin-bottom: 4px;
        }

        .exercise-details {
            font-size: 0.875rem;
            color: var(--color-text-placeholder);
        }

        .exercise-sets {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-primary);
            background-color: var(--color-bg-secondary);
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* --- Modal Styles --- */
        .exercise-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: var(--color-card-bg);
            border-radius: 12px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalFadeIn 0.3s ease;
        }

        .modal-header {
            position: relative;
            height: 300px;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
        }

        .carousel-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-bg-secondary);
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--color-card-bg);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--color-text-primary);
            box-shadow: var(--color-shadow);
            transition: background-color 0.2s;
        }

        .modal-close:hover {
            background-color: var(--color-btn-secondary);
        }

        .modal-body {
            padding: 30px;
        }

        .exercise-main-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 15px;
        }

        .exercise-subtitle {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .exercise-tag {
            display: flex;
            align-items: center;
            background-color: var(--color-bg-secondary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            color: var(--color-text-primary);
        }

        .exercise-tag .material-symbols-rounded {
            margin-right: 6px;
        }

        .exercise-description {
            color: var(--color-text-placeholder);
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .exercise-section {
            margin-bottom: 30px;
        }

        .section-title {
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 15px;
        }

        .section-icon {
            margin-right: 10px;
            color: var(--color-btn-primary);
        }

        .instruction-list {
            list-style: none;
        }

        .instruction-item {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .instruction-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background-color: var(--color-btn-primary);
            color: white;
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
            font-weight: 600;
        }

        .instruction-text {
            flex: 1;
            padding-top: 2px;
            color: var(--color-text-primary);
        }

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

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

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

            .workout-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .modal-body {
                padding: 20px;
            }

            .exercise-subtitle {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo"><i class="fas fa-dumbbell"></i><span>GYM ADMIN</span></div>
            <ul class="nav-menu">
                <li class="nav-item"><a class="nav-link active" data-tab="workout-view"><i class="fas fa-th-large"></i><span>Workout Plans</span></a></li>
                <li class="nav-item"><a class="nav-link" data-tab="muscle-form"><i class="fas fa-plus-circle"></i><span>Add Muscle</span></a></li>
                <li class="nav-item"><a class="nav-link" data-tab="head-form"><i class="fas fa-plus-circle"></i><span>Add Muscle Head</span></a></li>
                <li class="nav-item"><a class="nav-link" data-tab="exercise-form"><i class="fas fa-plus-circle"></i><span>Add Exercise</span></a></li>
            </ul>
            <div class="theme-toggle" id="theme-toggle">
                <div class="theme-toggle-label"><i class="fas fa-moon" id="theme-icon"></i><span>Dark Mode</span></div>
                <div class="toggle-switch"></div>
            </div>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Admin Panel</h1>
                <p class="page-subtitle">Manage your gym database</p>
            </div>
            <div class="success-message" id="success-message"><i class="fas fa-check-circle"></i> Item added successfully!</div>
            <div class="tabs">
                <div class="tab active" data-tab="workout-view">Workout Plans</div>
                <div class="tab" data-tab="muscle-form">Muscle</div>
                <div class="tab" data-tab="head-form">Muscle Head</div>
                <div class="tab" data-tab="exercise-form">Exercise</div>
            </div>

            <!-- WORKOUT VIEW -->
            <div class="form-card active" id="workout-view">
                <div class="form-header">
                    <div class="form-icon"><i class="fas fa-th-large"></i></div>
                    <h2 class="form-title">Workout Plans</h2>
                </div>
                <p class="page-subtitle">Choose from our targeted workout plans for specific muscle groups...</p>
                <div class="workout-grid">
                    <?php foreach ($cards as $card) { ?>
                        <div class="workout-card">
                            <div class="workout-header">
                                <div class="workout-icon"><span class="material-symbols-rounded">fitness_center</span></div>
                                <div>
                                    <h3 class="workout-title"><?php echo htmlspecialchars($card['muscle']); ?> – <?php echo htmlspecialchars($card['head']); ?></h3>
                                    <span class="difficulty-badge difficulty-<?php echo strtolower($card['difficulty']); ?>"><?php echo htmlspecialchars($card['difficulty']); ?></span>
                                </div>
                            </div>
                            <div class="exercise-list">
                                <?php foreach ($card['exercises'] as $ex) { ?>
                                    <div class="exercise-item" onclick="openExerciseModal(<?php echo $ex['exercise_id']; ?>)">
                                        <span class="material-symbols-rounded exercise-icon">arrow_right</span>
                                        <div class="exercise-info">
                                            <div class="exercise-name"><?php echo htmlspecialchars($ex['exercise_name']); ?></div>
                                            <div class="exercise-details"><?php echo htmlspecialchars($ex['equipment']); ?></div>
                                        </div>
                                        <span class="exercise-sets"><?php echo htmlspecialchars($ex['sets_reps']); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- MUSCLE FORM -->
            <div class="form-card" id="muscle-form">
                <div class="form-header">
                    <div class="form-icon"><i class="fas fa-user"></i></div>
                    <h2 class="form-title">Add Muscle</h2>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="muscle_name">Muscle Name</label><input type="text" class="form-input" id="muscle_name" name="muscle_name" placeholder="e.g., Biceps Brachii" required></div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="muscle_image">Muscle Image</label>
                                <div class="file-upload"><input type="file" id="muscle_image" name="muscle_image" required><i class="fas fa-cloud-upload-alt file-upload-icon"></i><span class="file-upload-text">Choose file or drag here</span></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_muscle" class="btn btn-block"><i class="fas fa-save"></i> Add Muscle</button>
                </form>
            </div>

            <!-- MUSCLE HEAD FORM -->
            <div class="form-card" id="head-form">
                <div class="form-header">
                    <div class="form-icon"><i class="fas fa-brain"></i></div>
                    <h2 class="form-title">Add Muscle Head</h2>
                </div>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="muscle_id">Select Muscle</label>
                                <select class="form-select" id="muscle_id" name="muscle_id" required>
                                    <option value="" disabled selected>Select a Muscle...</option>
                                    <?php while ($m = mysqli_fetch_assoc($muscles)) { ?>
                                        <option value="<?php echo $m['muscle_id']; ?>"><?php echo htmlspecialchars($m['muscle_name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="head_name">Head Name</label><input type="text" class="form-input" id="head_name" name="head_name" placeholder="e.g., Short Head" required></div>
                        </div>
                    </div>
                    <div class="form-group"><label class="form-label" for="head_desc">Description</label><textarea class="form-textarea" id="head_desc" name="head_desc" placeholder="A brief description of the muscle head..."></textarea></div>
                    <button type="submit" name="add_head" class="btn btn-block"><i class="fas fa-save"></i> Add Head</button>
                </form>
            </div>

            <!-- EXERCISE FORM -->
            <div class="form-card" id="exercise-form">
                <div class="form-header">
                    <div class="form-icon"><i class="fas fa-running"></i></div>
                    <h2 class="form-title">Add Exercise</h2>
                </div>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="head_id">Select Muscle Head</label>
                                <select class="form-select" id="head_id" name="head_id" required>
                                    <option value="" disabled selected>Select a Muscle Head...</option>
                                    <?php mysqli_data_seek($heads, 0);
                                    while ($h = mysqli_fetch_assoc($heads)) { ?>
                                        <option value="<?php echo $h['head_id']; ?>"><?php echo htmlspecialchars($h['muscle_name']); ?> → <?php echo htmlspecialchars($h['head_name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="exercise_name">Exercise Name</label><input type="text" class="form-input" id="exercise_name" name="exercise_name" placeholder="e.g., Barbell Curl" required></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="equipment">Equipment</label><input type="text" class="form-input" id="equipment" name="equipment" placeholder="e.g., Barbell, Dumbbells"></div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"><label class="form-label" for="difficulty">Difficulty</label>
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
                            <div class="form-group"><label class="form-label" for="sets_reps">Sets x Reps</label><input type="text" class="form-input" id="sets_reps" name="sets_reps" placeholder="e.g., 3 x 12"></div>
                        </div>
                    </div>
                    <button type="submit" name="add_exercise" class="btn btn-block"><i class="fas fa-save"></i> Add Exercise</button>
                </form>
            </div>
        </main>
    </div>

    <!-- Exercise Detail Modal -->
    <div id="exerciseModal" class="exercise-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="carousel-container">
                    <div class="carousel-slide active">
                        <img id="modal-muscle-image" src="" alt="Muscle Image">
                    </div>
                </div>
                <button class="modal-close" onclick="closeExerciseModal()"><span class="material-symbols-rounded">close</span></button>
            </div>
            <div class="modal-body">
                <div class="exercise-title-section">
                    <h2 class="exercise-main-title" id="modal-exercise-name"></h2>
                    <div class="exercise-subtitle">
                        <span class="exercise-tag"><span class="material-symbols-rounded">fitness_center</span><span id="modal-equipment"></span></span>
                        <span class="exercise-tag"><span class="material-symbols-rounded">local_fire_department</span><span id="modal-muscle-head"></span></span>
                        <span class="exercise-tag"><span class="material-symbols-rounded">signal_cellular_alt</span><span id="modal-difficulty"></span></span>
                    </div>
                    <p class="exercise-description" id="modal-description"></p>
                </div>
                <div class="exercise-section">
                    <h3 class="section-title"><span class="material-symbols-rounded section-icon">format_list_numbered</span>Step-by-Step Instructions</h3>
                    <ul class="instruction-list" id="modal-instructions">
                        <!-- Instructions will be populated by JS -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab'),
                navLinks = document.querySelectorAll('.nav-link'),
                forms = document.querySelectorAll('.form-card');

            function switchTab(tabId) {
                tabs.forEach(t => t.classList.remove('active'));
                navLinks.forEach(l => l.classList.remove('active'));
                forms.forEach(f => f.classList.remove('active'));
                document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
                document.querySelector(`.nav-link[data-tab="${tabId}"]`).classList.add('active');
                document.getElementById(tabId).classList.add('active');
            }
            tabs.forEach(tab => tab.addEventListener('click', () => switchTab(tab.getAttribute('data-tab'))));
            navLinks.forEach(link => link.addEventListener('click', (e) => {
                e.preventDefault();
                switchTab(link.getAttribute('data-tab'));
            }));

            // Theme Toggle
            const themeToggle = document.getElementById('theme-toggle'),
                themeIcon = document.getElementById('theme-icon'),
                body = document.body;
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                body.classList.add('dark-theme');
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }
            themeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-theme');
                if (body.classList.contains('dark-theme')) {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    localStorage.setItem('theme', 'light');
                }
            });

            // Modal functionality
            window.openExerciseModal = function(exerciseId) {
                const modal = document.getElementById('exerciseModal');
                fetch(`?get_exercise_details&exercise_id=${exerciseId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        document.getElementById('modal-exercise-name').innerText = data.exercise_name || 'N/A';
                        document.getElementById('modal-equipment').innerText = data.equipment || 'N/A';
                        document.getElementById('modal-muscle-head').innerText = `${data.muscle_name || 'N/A'} – ${data.head_name || 'N/A'}`;
                        document.getElementById('modal-difficulty').innerText = data.difficulty || 'N/A';
                        document.getElementById('modal-description').innerText = data.head_description || 'No description available.';
                        const imgElement = document.getElementById('modal-muscle-image');
                        if (data.muscle_image) {
                            imgElement.src = `./uploads/${data.muscle_image}`;
                        } else {
                            imgElement.style.display = 'none';
                        }
                        const instructionsList = document.getElementById('modal-instructions');
                        instructionsList.innerHTML = '';
                        const exampleInstructions = ["Step 1: Prepare your equipment.", "Step 2: Get into the starting position.", "Step 3: Perform the main movement.", "Step 4: Return to the starting position."];
                        exampleInstructions.forEach((text, index) => {
                            const li = document.createElement('li');
                            li.className = 'instruction-item';
                            li.innerHTML = `<span class="instruction-number">${index + 1}</span><span class="instruction-text">${text}</span>`;
                            instructionsList.appendChild(li);
                        });
                        modal.style.display = 'flex';
                    })
                    .catch(error => console.error('Error fetching exercise details:', error));
            };
            window.closeExerciseModal = function() {
                document.getElementById('exerciseModal').style.display = 'none';
            };
            window.onclick = function(event) {
                const modal = document.getElementById('exerciseModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        });
    </script>
</body>

</html>
```

## MYSQL Tables:

```sql
CREATE TABLE muscles (
    muscle_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_name VARCHAR(50) NOT NULL,
    image_url VARCHAR(255)      -- Muscle main image
);


CREATE TABLE muscle_heads (
    head_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_id INT NOT NULL,
    head_name VARCHAR(50) NOT NULL,   -- Specific head, e.g., "Upper Chest"
    description TEXT,
    FOREIGN KEY (muscle_id) REFERENCES muscles(muscle_id) ON DELETE CASCADE
);


CREATE TABLE exercises (
    exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    head_id INT NOT NULL,                 -- Belongs to a specific muscle head
    exercise_name VARCHAR(100) NOT NULL,
    equipment VARCHAR(50),
    sets_reps VARCHAR(20),
    difficulty ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
    FOREIGN KEY (head_id) REFERENCES muscle_heads(head_id) ON DELETE CASCADE
);

```