<!-- Is page ka Work Bilkul Done or Clear hai -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sidebar Menu | CodingNepal</title>
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css" />
    <!-- Linking Google fonts for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>
<body>

    <!-- Navbar Start -->
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

    <nav class="site-nav">
        <button class="sidebar-toggle">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </nav>

    <div class="container">
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

        <div class="main-content">
            <h1 class="page-title">Workout Plans</h1>
            <p class="card">Choose from our targeted workout plans for specific muscle groups. Each plan includes exercises with sets and reps to help you achieve your fitness goals.</p>

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

    <script>
        const sidebarToggleBtns = document.querySelectorAll(".sidebar-toggle");
        const sidebar = document.querySelector(".sidebar");
        const searchForm = document.querySelector(".search-form");
        const themeToggleBtn = document.querySelector(".theme-toggle");
        const themeIcon = themeToggleBtn.querySelector(".theme-icon");
        const menuLinks = document.querySelectorAll(".menu-link");
        // Updates the theme icon based on current theme and sidebar state
        const updateThemeIcon = () => {
            const isDark = document.body.classList.contains("dark-theme");
            themeIcon.textContent = sidebar.classList.contains("collapsed") ? (isDark ? "light_mode" : "dark_mode") : "dark_mode";
        };
        // Apply dark theme if saved or system prefers, then update icon
        const savedTheme = localStorage.getItem("theme");
        const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        const shouldUseDarkTheme = savedTheme === "dark" || (!savedTheme && systemPrefersDark);
        document.body.classList.toggle("dark-theme", shouldUseDarkTheme);
        updateThemeIcon();
        // Toggle between themes on theme button click
        themeToggleBtn.addEventListener("click", () => {
            const isDark = document.body.classList.toggle("dark-theme");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            updateThemeIcon();
        });
        // Toggle sidebar collapsed state on buttons click
        sidebarToggleBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                sidebar.classList.toggle("collapsed");
                updateThemeIcon();
            });
        });
        // Expand the sidebar when the search form is clicked
        searchForm.addEventListener("click", () => {
            if (sidebar.classList.contains("collapsed")) {
                sidebar.classList.remove("collapsed");
                searchForm.querySelector("input").focus();
            }
        });
        // Expand sidebar by default on large screens
        if (window.innerWidth > 768) sidebar.classList.remove("collapsed");
    </script>
    <!-- Navbar End -->


    <!-- Detial Page Start -->
    <?php
    include './config/db.php';
    $exercise_id = 1;

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
    if (!$result) die("Query Failed: " . mysqli_error($conn));

    $data = mysqli_fetch_assoc($result);
    ?>

    <div id="exerciseModal" class="exercise-modal">
        <div class="modal-content">
            <div class="modal-header">

                <div class="carousel-container">
                    <div class="carousel-slide active">
                        <?php if (!empty($data['muscle_image'])): ?>
                            <img src="./uploads/<?php echo $data['muscle_image']; ?>" alt="Muscle Image">
                        <?php endif; ?>
                    </div>
                </div>

                <button class="modal-close" onclick="closeExerciseModal()">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="exercise-title-section">
                    <h2 class="exercise-main-title">
                        <?php echo htmlspecialchars($data['exercise_name']); ?>
                    </h2>

                    <div class="exercise-subtitle">
                        <span class="exercise-tag">
                            <span class="material-symbols-rounded" style="font-size:20px;">fitness_center</span>
                            <?php echo htmlspecialchars($data['equipment']); ?>
                        </span>

                        <span class="exercise-tag">
                            <span class="material-symbols-rounded" style="font-size:20px;">local_fire_department</span>
                            <?php echo htmlspecialchars($data['muscle_name'] . " – " . $data['head_name']); ?>
                        </span>

                        <span class="exercise-tag">
                            <span class="material-symbols-rounded" style="font-size:20px;">signal_cellular_alt</span>
                            <?php echo htmlspecialchars($data['difficulty']); ?>
                        </span>
                    </div>

                    <p class="exercise-description">
                        <?php echo nl2br(htmlspecialchars($data['head_description'])); ?>
                    </p>
                </div>

                <div class="exercise-section">
                    <h3 class="section-title">
                        <span class="material-symbols-rounded section-icon">format_list_numbered</span>
                        Step-by-Step Instructions
                    </h3>
                    <ul class="instruction-list">
                        <li class="instruction-item">
                            <span class="instruction-number">1</span>
                            <span class="instruction-text">Example instruction 1</span>
                        </li>
                        <li class="instruction-item">
                            <span class="instruction-number">2</span>
                            <span class="instruction-text">Example instruction 2</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script>
        function openExerciseModal() {
            document.getElementById('exerciseModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeExerciseModal() {
            document.getElementById('exerciseModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeExerciseModal();
        });

        document.getElementById('exerciseModal').addEventListener('click', e => {
            if (e.target.id === 'exerciseModal') closeExerciseModal();
        });
    </script>
    <!-- Detial Page Start -->

</body>
</html>