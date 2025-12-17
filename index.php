<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sidebar Menu | CodingNepal</title>
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css" />
    
    <!-- JS -->
    <script src="./js/script.js" defer></script>

    <!-- Linking Google fonts for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>
<body>
    <!-- Navbar -->
    <nav class="site-nav">
        <button class="sidebar-toggle">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </nav>

    <!-- Container -->
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar collapsed">
            <!-- Sidebar header -->
            <div class="sidebar-header">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTEDJIBa9cOKUUV31h-vF2UOx0DxP4H9Dfs4g&s" alt="CodingNepal" class="header-logo" />
                <button class="sidebar-toggle">
                    <span class="material-symbols-rounded">chevron_left</span>
                </button>
            </div>
            <div class="sidebar-content">
                <!-- Search Form -->
                <form action="#" class="search-form">
                    <span class="material-symbols-rounded">search</span>
                    <input type="search" placeholder="Search..." required />
                </form>
                <!-- Sidebar Menu -->
                <ul class="menu-list">
                    <li class="menu-item">
                        <a href="#" class="menu-link active">
                            <span class="material-symbols-rounded">dashboard</span>
                            <span class="menu-label">Dashboard</span>
                        </a>
                    </li>


                    <li class="menu-item">
                        <a href="add_exercise.php" class="menu-link">
                            <span class="material-symbols-rounded">exercise</span>
                            <span class="menu-label">Add Exercise</span>
                        </a>
                    </li>


                </ul>
            </div>
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <button class="theme-toggle">
                    <div class="theme-label">
                        <span class="theme-icon material-symbols-rounded">dark_mode</span>
                        <span class="theme-text">Dark Mode</span>
                    </div>
                    <div class="theme-toggle-track">
                        <div class="theme-toggle-indicator"></div>
                    </div>
                </button>
            </div>
        </aside>


        <!-- Site main content -->
        <div class="main-content">
            <h1 class="page-title">Workout Plans</h1>
            <p class="card">Choose from our targeted workout plans for specific muscle groups. Each plan includes exercises with sets and reps to help you achieve your fitness goals.</p>


<?php
include "./config/db.php";

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

// 🔴 Query failure handling
if (!$result) {
    die("Database Query Failed: " . mysqli_error($conn));
}

$cards = [];

while ($row = mysqli_fetch_assoc($result)) {

    $cardKey = $row['muscle_name'] . ' - ' . $row['head_name'];

    if (!isset($cards[$cardKey])) {
        $cards[$cardKey] = [
            'muscle'     => $row['muscle_name'],
            'head'       => $row['head_name'],
            'difficulty' => $row['difficulty'],
            'exercises'  => []
        ];
    }

    $cards[$cardKey]['exercises'][] = [
        'exercise_id'   => $row['exercise_id'],
        'exercise_name' => $row['exercise_name'],
        'equipment'     => $row['equipment'],
        'sets_reps'     => $row['sets_reps'],
        'difficulty'    => $row['difficulty']
    ];
}
?>


            <div class="workout-grid">
                <?php foreach ($cards as $card) { ?>
                    <div class="workout-card">

                        <div class="workout-header">
                            <div class="workout-icon">
                                <span class="material-symbols-rounded">fitness_center</span>
                            </div>
                            <div>
                                <h3 class="workout-title">
                                    <?php echo $card['muscle']; ?> – <?php echo $card['head']; ?>
                                </h3>
                                <span class="difficulty-badge difficulty-<?php echo strtolower($card['difficulty']); ?>">
                                    <?php echo $card['difficulty']; ?>
                                </span>
                            </div>
                        </div>

                        <div class="exercise-list">
                            <?php foreach ($card['exercises'] as $ex) { ?>
                                <div class="exercise-item" onclick="openExerciseModal('<?php echo $ex['exercise_id']; ?>')">
                                    <span class="material-symbols-rounded exercise-icon">arrow_right</span>
                                    <div class="exercise-info">
                                        <div class="exercise-name"><?php echo $ex['exercise_name']; ?></div>
                                        <div class="exercise-details">
                                            <?php echo $ex['equipment']; ?>
                                        </div>
                                    </div>
                                    <span class="exercise-sets"><?php echo $ex['sets_reps']; ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>

    <!-- Modal Box / Detial Page -->
    <div id="exerciseModal" class="exercise-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="carousel-container">
                    <div class="carousel-slide active">
                        <img src="" id="modalHeadImage" alt="Exercise Step 1">
                    </div>
                </div>
                <button class="modal-close" onclick="closeExerciseModal()">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="exercise-title-section">
                    <h2 class="exercise-main-title" id="exerciseTitle">
                        <!-- muscle -->
                    </h2>
                    <div class="exercise-subtitle">

                        <span class="exercise-tag" id="tagEquipment">
                            <span class="material-symbols-rounded" style="font-size: 20px;">fitness_center</span>
                            <!-- Barbell -->
                        </span>
                       
                        <span class="exercise-tag" id="tagMuscle">
                            <span class="material-symbols-rounded" style="font-size: 20px;">local_fire_department</span>
                            <!-- Chest -->
                        </span>
                        
                        <span class="exercise-tag" id="tagLevel">
                            <span class="material-symbols-rounded" style="font-size: 20px;">signal_cellular_alt</span>
                            <!-- Intermediate -->
                        </span>

                    </div>

                <p class="exercise-description" id="modalDescription"> 
                    <!-- description -->
                </p>
                </div>


                <div class="exercise-section">
                    <h3 class="section-title">
                        <span class="material-symbols-rounded section-icon">format_list_numbered</span>
                        Step-by-Step Instructions
                    </h3>
                    <ul class="instruction-list">
                        <li class="instruction-item">
                            <span class="instruction-number">2</span>
                            <span class="instruction-text">Unrack the weight with straight arms, holding it above your chest with arms locked. Keep your shoulder blades retracted and depressed throughout the movement.</span>
                        </li>
                        <li class="instruction-item">
                            <span class="instruction-number">3</span>
                            <span class="instruction-text">Lower the bar slowly to your mid-chest while inhaling, keeping elbows at about 45 degrees to your body. Control the descent for 2-3 seconds.</span>
                        </li>
                    
                    </ul>
                </div>

            </div>
        </div>
    </div>
</body>
</html>