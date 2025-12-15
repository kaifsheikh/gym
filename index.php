<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sidebar Menu | CodingNepal</title>
    <link rel="stylesheet" href="css/style.css" />
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
              <a href="add_category.php" class="menu-link">
                <span class="material-symbols-rounded">exercise</span>
                <span class="menu-label">Add Exercise</span>
              </a>
            </li>


            <li class="menu-item">
              <a href="add_category_subcategory.php" class="menu-link">
                <span class="material-symbols-rounded">kaif</span>
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
        
        <div class="workout-grid">

        <?php
            include './config/db.php';

            $workoutQuery = "
                SELECT w.*, c.name AS category_name
                FROM workouts w
                JOIN workout_categories c ON c.id = w.category_id
                WHERE w.id = 1
            ";

            $workoutResult = mysqli_query($conn , $workoutQuery);
            $workout = mysqli_fetch_assoc($workoutResult);
        ?>

          <!-- Chest Workout Card -->
          <div class="workout-card">
            <div class="workout-header">
              <div class="workout-icon">
                <span class="material-symbols-rounded">fitness_center</span>
              </div>
              <div>
                <h3 class="workout-title"><?php echo $workout['title']; ?></h3>
                <span class="difficulty-badge difficulty-intermediate"><?php echo $workout['difficulty'] ?></span>
              </div>
            </div>
            <div class="workout-meta">
              <div class="meta-item">
                <div class="meta-value"><?php echo $workout['minutes']; ?></div>
                <div class="meta-label">Minutes</div>
              </div>
              <div class="meta-item">
                <div class="meta-value"><?php echo $workout['total_exercises']; ?></div>
                <div class="meta-label">Exercises</div>
              </div>
              <div class="meta-item">
                <div class="meta-value"><?php echo $workout['calories']; ?></div>
                <div class="meta-label">Calories</div>
              </div>
            </div>

        <div class="exercise-list">

            <?php
                $subQuery = "
                    SELECT * FROM workout_subcategories
                    WHERE category_id = {$workout['category_id']}
                ";

                $subResult = mysqli_query($conn, $subQuery);

                while($sub = mysqli_fetch_assoc($subResult)){
            ?>

            <?php
                $exerciseQuery = "
                    SELECT * FROM exercises
                    WHERE workout_id = {$workout['id']}
                    AND subcategory_id = {$sub['id']}
                ";
                $exerciseResult = mysqli_query($conn, $exerciseQuery);
                while ($ex = mysqli_fetch_assoc($exerciseResult)) {
            ?>

              <div class="exercise-item" onclick="openExerciseModal('<?php echo $ex['slug']; ?>')">
                <span class="material-symbols-rounded exercise-icon">arrow_right</span>
                <div class="exercise-info">
                  <div class="exercise-name"> <?php echo $ex['name']; ?></div>
                  <div class="exercise-details"><?php echo $ex['equipment']; ?> • <?php echo $ex['target']; ?></div>
                </div>
                <span class="exercise-sets"> <?php echo $ex['sets']; ?></span>
              </div>

              <?php } ?>
              <?php } ?>

            </div>

            <div class="workout-actions">
                <button class="workout-btn btn-primary" onclick="openExerciseModal('bench-press')">Details</button>
            </div>

        </div>

        </div>
      </div>
    </div>

    <!-- Enhanced Exercise Detail Modal -->
    <div id="exerciseModal" class="exercise-modal">
      <div class="modal-content">
        <div class="modal-header">
          <div class="carousel-container">
            <div class="carousel-slide active">
              <img src="https://picsum.photos/seed/exercise1/1200/350.jpg" alt="Exercise Step 1">
            </div>
            <div class="carousel-slide">
              <img src="https://picsum.photos/seed/exercise2/1200/350.jpg" alt="Exercise Step 2">
            </div>
            <div class="carousel-slide">
              <img src="https://picsum.photos/seed/exercise3/1200/350.jpg" alt="Exercise Step 3">
            </div>
            <div class="carousel-slide">
              <img src="https://picsum.photos/seed/exercise4/1200/350.jpg" alt="Exercise Step 4">
            </div>
            <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">
              <span class="material-symbols-rounded">chevron_left</span>
            </button>
            <button class="carousel-nav carousel-next" onclick="changeSlide(1)">
              <span class="material-symbols-rounded">chevron_right</span>
            </button>
            <div class="carousel-controls">
              <span class="carousel-dot active" onclick="currentSlide(1)"></span>
              <span class="carousel-dot" onclick="currentSlide(2)"></span>
              <span class="carousel-dot" onclick="currentSlide(3)"></span>
              <span class="carousel-dot" onclick="currentSlide(4)"></span>
            </div>
          </div>
          <button class="modal-close" onclick="closeExerciseModal()">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="exercise-title-section">
            <h2 class="exercise-main-title" id="exerciseTitle">Bench Press</h2>
            <div class="exercise-subtitle">
              <span class="exercise-tag">
                <span class="material-symbols-rounded" style="font-size: 20px;">fitness_center</span>
                Barbell
              </span>
              <span class="exercise-tag">
                <span class="material-symbols-rounded" style="font-size: 20px;">local_fire_department</span>
                Chest
              </span>
              <span class="exercise-tag">
                <span class="material-symbols-rounded" style="font-size: 20px;">signal_cellular_alt</span>
                Intermediate
              </span>
              <span class="exercise-tag">
                <span class="material-symbols-rounded" style="font-size: 20px;">timer</span>
                45 mins
              </span>
            </div>
            <p class="exercise-description">
              The bench press is a fundamental compound exercise that primarily targets the chest muscles while also engaging the shoulders and triceps. It's one of the most popular exercises for building upper body strength and muscle mass.
            </p>
          </div>
          
          <div class="stats-grid">
            <div class="stat-box">
              <div class="stat-value">85%</div>
              <div class="stat-label">Chest Activation</div>
            </div>
            <div class="stat-box">
              <div class="stat-value">65%</div>
              <div class="stat-label">Shoulder Activation</div>
            </div>
            <div class="stat-box">
              <div class="stat-value">45%</div>
              <div class="stat-label">Tricep Activation</div>
            </div>
            <div class="stat-box">
              <div class="stat-value">4-6</div>
              <div class="stat-label">Sets</div>
            </div>
            <div class="stat-box">
              <div class="stat-value">8-12</div>
              <div class="stat-label">Reps</div>
            </div>
            <div class="stat-box">
              <div class="stat-value">90s</div>
              <div class="stat-label">Rest</div>
            </div>
          </div>
          
          <div class="content-grid">
            <div class="exercise-section">
              <h3 class="section-title">
                <span class="material-symbols-rounded section-icon">format_list_numbered</span>
                Step-by-Step Instructions
              </h3>
              <ul class="instruction-list">
                <li class="instruction-item">
                  <span class="instruction-number">1</span>
                  <span class="instruction-text">Lie on your back on a flat bench with your feet flat on the floor. Grip the barbell with hands slightly wider than shoulder-width apart, ensuring your wrists are straight.</span>
                </li>
                <li class="instruction-item">
                  <span class="instruction-number">2</span>
                  <span class="instruction-text">Unrack the weight with straight arms, holding it above your chest with arms locked. Keep your shoulder blades retracted and depressed throughout the movement.</span>
                </li>
                <li class="instruction-item">
                  <span class="instruction-number">3</span>
                  <span class="instruction-text">Lower the bar slowly to your mid-chest while inhaling, keeping elbows at about 45 degrees to your body. Control the descent for 2-3 seconds.</span>
                </li>
                <li class="instruction-item">
                  <span class="instruction-number">4</span>
                  <span class="instruction-text">Push the bar back up to the starting position while exhaling, focusing on squeezing your chest muscles at the top. Drive through your chest and keep your back flat.</span>
                </li>
                <li class="instruction-item">
                  <span class="instruction-number">5</span>
                  <span class="instruction-text">Repeat for the desired number of repetitions, maintaining proper form throughout. Focus on mind-muscle connection with your chest.</span>
                </li>
              </ul>
            </div>
            
            <div class="exercise-section">
              <h3 class="section-title">
                <span class="material-symbols-rounded section-icon">accessibility_new</span>
                Target Muscles
              </h3>
              <div class="muscle-diagram">
                <img src="https://picsum.photos/seed/muscle-diagram/400/250.jpg" alt="Muscle Diagram">
              </div>
              <div class="muscle-grid">
                <div class="muscle-item">
                  <span class="material-symbols-rounded muscle-icon">circle</span>
                  <span class="muscle-name">Pectoralis Major</span>
                  <span class="muscle-percentage">85%</span>
                </div>
                <div class="muscle-item">
                  <span class="material-symbols-rounded muscle-icon">circle</span>
                  <span class="muscle-name">Anterior Deltoids</span>
                  <span class="muscle-percentage">65%</span>
                </div>
                <div class="muscle-item">
                  <span class="material-symbols-rounded muscle-icon">circle</span>
                  <span class="muscle-name">Triceps Brachii</span>
                  <span class="muscle-percentage">45%</span>
                </div>
                <div class="muscle-item">
                  <span class="material-symbols-rounded muscle-icon">circle</span>
                  <span class="muscle-name">Serratus Anterior</span>
                  <span class="muscle-percentage">25%</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="exercise-section">
            <h3 class="section-title">
              <span class="material-symbols-rounded section-icon">stars</span>
              Benefits
            </h3>
            <div class="benefit-list">
              <div class="benefit-item">
                <span class="material-symbols-rounded benefit-icon">check_circle</span>
                <span class="benefit-text">Builds significant upper body strength and muscle mass in the chest, shoulders, and triceps</span>
              </div>
              <div class="benefit-item">
                <span class="material-symbols-rounded benefit-icon">check_circle</span>
                <span class="benefit-text">Improves pushing strength for daily activities and sports performance</span>
              </div>
              <div class="benefit-item">
                <span class="material-symbols-rounded benefit-icon">check_circle</span>
                <span class="benefit-text">Enhances chest definition and creates a more aesthetic physique</span>
              </div>
              <div class="benefit-item">
                <span class="material-symbols-rounded benefit-icon">check_circle</span>
                <span class="benefit-text">Strengthens bones and connective tissues in the upper body</span>
              </div>
              <div class="benefit-item">
                <span class="material-symbols-rounded benefit-icon">check_circle</span>
                <span class="benefit-text">Boosts metabolic rate and calorie burning due to compound nature</span>
              </div>
              <div class="benefit-item">
                <span class="material-symbols-rounded benefit-icon">check_circle</span>
                <span class="benefit-text">Improves posture and shoulder stability when performed correctly</span>
              </div>
            </div>
          </div>
          
          <div class="content-grid">
            <div class="exercise-section">
              <h3 class="section-title">
                <span class="material-symbols-rounded section-icon">tips_and_updates</span>
                Pro Tips
              </h3>
              <div class="info-card tip-card">
                <div class="tip-title">
                  <span class="material-symbols-rounded">lightbulb</span>
                  Breathing Technique
                </div>
                <div class="tip-text">Inhale deeply as you lower the weight (2-3 seconds) and exhale forcefully as you push up. This breathing pattern helps maintain intra-abdominal pressure and stability.</div>
              </div>
              <div class="info-card tip-card">
                <div class="tip-title">
                  <span class="material-symbols-rounded">lightbulb</span>
                  Scapular Retraction
                </div>
                <div class="tip-text">Keep your shoulder blades pulled back and down throughout the movement. This creates a stable base and maximizes chest activation.</div>
              </div>
              <div class="info-card tip-card">
                <div class="tip-title">
                  <span class="material-symbols-rounded">lightbulb</span>
                  Mind-Muscle Connection
                </div>
                <div class="tip-text">Focus on squeezing your chest muscles at the top of the movement. Imagine pushing the bar through the ceiling rather than just moving it up.</div>
              </div>
            </div>
            
            <div class="exercise-section">
              <h3 class="section-title">
                <span class="material-symbols-rounded section-icon">error</span>
                Common Mistakes
              </h3>
              <div class="info-card mistake-card">
                <div class="mistake-title">
                  <span class="material-symbols-rounded">dangerous</span>
                  Bouncing the Bar
                </div>
                <div class="mistake-text">Never bounce the barbell off your chest. This reduces muscle engagement and can cause rib or sternum injuries. Control the weight throughout.</div>
              </div>
              <div class="info-card mistake-card">
                <div class="mistake-title">
                  <span class="material-symbols-rounded">dangerous</span>
                  Elbow Flaring
                </div>
                <div class="mistake-text">Keep your elbows at about 45 degrees to your body. Flaring them out to 90 degrees puts excessive stress on shoulder joints and can lead to injury.</div>
              </div>
              <div class="info-card mistake-card">
                <div class="mistake-title">
                  <span class="material-symbols-rounded">dangerous</span>
                  Lifting Hips
                </div>
                <div class="mistake-text">Keep your glutes on the bench throughout. Lifting your hips reduces chest involvement and can cause lower back strain.</div>
              </div>
            </div>
          </div>
          
          <div class="exercise-section">
            <h3 class="section-title">
              <span class="material-symbols-rounded section-icon">swap_horiz</span>
              Exercise Variations
            </h3>
            <div class="content-grid">
              <div class="info-card variation-card">
                <div class="variation-title">
                  <span class="material-symbols-rounded">trending_up</span>
                  Incline Bench Press
                </div>
                <div class="variation-text">Set the bench at 30-45 degrees to target the upper chest fibers more effectively. Great for developing a fuller chest appearance.</div>
              </div>
              <div class="info-card variation-card">
                <div class="variation-title">
                  <span class="material-symbols-rounded">trending_up</span>
                  Decline Bench Press
                </div>
                <div class="variation-text">Set the bench at a slight decline to emphasize the lower chest. Allows for heavier loads with reduced shoulder strain.</div>
              </div>
              <div class="info-card variation-card">
                <div class="variation-title">
                  <span class="material-symbols-rounded">trending_up</span>
                  Dumbbell Bench Press
                </div>
                <div class="variation-text">Using dumbbells allows for greater range of motion and unilateral strength development. Also helps identify and fix strength imbalances.</div>
              </div>
              <div class="info-card variation-card">
                <div class="variation-title">
                  <span class="material-symbols-rounded">trending_up</span>
                  Close-Grip Bench Press
                </div>
                <div class="variation-text">Place hands closer together (shoulder-width or slightly less) to emphasize triceps while still working the chest.</div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>

    <script src="js/script.js"></script>
  </body>
</html>