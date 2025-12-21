<?php
include "./config/db.php";


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
    $instructions = $_POST['instructions'] ?? []; // Array of steps

    // Insert exercise
    $sql = "INSERT INTO exercises (head_id, exercise_name, equipment, sets_reps, difficulty) 
            VALUES ('$head_id', '$name', '$equipment', '$sets_reps', '$difficulty')";
    if (!mysqli_query($conn, $sql)) {
        die("Exercise Insert Error: " . mysqli_error($conn));
    }

    // Get the last inserted exercise_id
    $exercise_id = mysqli_insert_id($conn);

    // Insert instructions
    foreach ($instructions as $index => $text) {
        $step_number = $index + 1;
        $instruction_text = mysqli_real_escape_string($conn, $text);
        $sql_ins = "INSERT INTO exercise_instructions (exercise_id, step_number, instruction_text) 
                    VALUES ('$exercise_id', '$step_number', '$instruction_text')";
        mysqli_query($conn, $sql_ins);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}



// --- AJAX LOGIC FOR MODAL ---
if (isset($_GET['get_exercise_details'])) {
    $exercise_id = intval($_GET['exercise_id']);

    // Exercise details
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

    // Fetch instructions
    $instructions_query = "
        SELECT step_number, instruction_text
        FROM exercise_instructions
        WHERE exercise_id = $exercise_id
        ORDER BY step_number ASC
    ";
    $instructions_result = mysqli_query($conn, $instructions_query);
    $instructions = [];
    while ($row = mysqli_fetch_assoc($instructions_result)) {
        $instructions[] = $row['instruction_text'];
    }

    $data['instructions'] = $instructions;

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

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>


<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">Admin Panel</h1>
        <p class="page-subtitle">Manage your gym database</p>
    </div>

    <div class="success-message" id="success-message"><i class="fas fa-check-circle"></i> Item
        added successfully!</div>

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

            <div class="form-row">
                <div class="form-col">
                    <label class="form-label">Step-by-Step Instructions</label>
                    <div id="instruction-list">
                        <div class="instruction-item">
                            <input type="text" name="instructions[]" class="form-input" placeholder="Step 1" required>
                        </div>
                    </div>
                    <button type="button" id="add-step" class="btn btn-block">
                        <i class="fas fa-list-ol"></i> Step-by-Step Instructions
                    </button>
                </div>
            </div>

            <script>
                const addStepBtn = document.getElementById('add-step');
                const instructionList = document.getElementById('instruction-list');
                addStepBtn.addEventListener('click', () => {
                    const stepCount = instructionList.querySelectorAll('input').length + 1;
                    const div = document.createElement('div');
                    div.className = 'instruction-item';
                    div.innerHTML = `<input type="text" name="instructions[]" class="form-input" placeholder="Step ${stepCount}" required>`;
                    instructionList.appendChild(div);
                });
            </script>

            <button type="submit" name="add_exercise" class="btn btn-block">
                <i class="fas fa-save"></i>
                Add Exercise
            </button>
        </form>
    </div>
</main>

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

<?php include "./include/footer.php" ?>