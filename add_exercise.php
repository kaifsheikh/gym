<?php
/* DATABASE CONNECTION */
include "./config/db.php";

/* ================= ADD MUSCLE ================= */
if (isset($_POST['add_muscle'])) {
    // It's highly recommended to use prepared statements to prevent SQL injection
    $muscle_name = mysqli_real_escape_string($conn, $_POST['muscle_name']);
    $description = mysqli_real_escape_string($conn, $_POST['muscle_desc']);
    // $muscle_name = $_POST['muscle_name'];
    // $description = $_POST['muscle_desc'];

    // Basic file validation and sanitization is also recommended
    $image = $_FILES['muscle_image']['name'];
    $tmp  = $_FILES['muscle_image']['tmp_name'];
    
    // Create a unique filename to prevent overwrites
    $unique_image_name = time() . '_' . basename($image);
    $upload_path = "uploads/" . $unique_image_name;

    if (move_uploaded_file($tmp, $upload_path)) {
        mysqli_query($conn,
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
    // $muscle_id = $_POST['muscle_id'];
    // $head_name = $_POST['head_name'];
    // $description = $_POST['head_desc'];

    $image = $_FILES['head_image']['name'];
    $tmp  = $_FILES['head_image']['tmp_name'];

    $unique_image_name = time() . '_' . basename($image);
    $upload_path = "uploads/" . $unique_image_name;

    if (move_uploaded_file($tmp, $upload_path)) {
        mysqli_query($conn,
            "INSERT INTO muscle_heads (muscle_id,head_name,image_url,description)
             VALUES ('$muscle_id','$head_name','$unique_image_name','$description')"
        );
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

/* ================= ADD EXERCISE ================= */
if (isset($_POST['add_exercise'])) {
    // It's highly recommended to use prepared statements here as well
    $head_id = $_POST['head_id'];
    $name = $_POST['exercise_name'];
    $equipment = $_POST['equipment'];
    $sets_reps = $_POST['sets_reps'];
    $time = $_POST['time_minutes'];
    $rest = $_POST['rest_time'];
    $calories = $_POST['calories_burn'];
    $difficulty = $_POST['difficulty'];
    $description = $_POST['exercise_desc'];
    $video = $_POST['video_url'];

    mysqli_query($conn,
        "INSERT INTO exercises
        (head_id,exercise_name,equipment,sets_reps,time_minutes,rest_time,calories_burn,difficulty,description,video_url)
        VALUES
        ('$head_id','$name','$equipment','$sets_reps','$time','$rest','$calories','$difficulty','$description','$video')"
    );
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
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* --- General Setup & Theming --- */
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #2c2c2c;
            --card-bg: #333333;
            --accent-color: #ff7b00;
            --accent-hover: #ff9500;
            --text-color: #e0e0e0;
            --heading-color: #ffffff;
            --border-color: #555555;
            --font-heading: 'Bebas Neue', cursive;
            --font-body: 'Roboto', sans-serif;
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
        }

        .admin-container {
            max-width: 800px;
            margin: 40px auto;
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
        }

        /* --- Form Card Styling --- */
        .form-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-color);
            animation: fadeIn 0.5s ease-in-out;
        }

        .form-card h2 {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 25px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }

        /* --- Form Element Styling --- */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--heading-color);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            background-color: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 5px;
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
            box-shadow: 0 0 8px var(--accent-color);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* --- Custom File Upload Button --- */
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
            padding: 12px;
            background-color: var(--secondary-bg);
            border: 1px dashed var(--accent-color);
            border-radius: 5px;
            color: var(--accent-color);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input-label:hover {
            background-color: var(--accent-color);
            color: #fff;
        }

        /* --- Button Styling --- */
        .btn {
            display: inline-block;
            background: linear-gradient(45deg, var(--accent-color), var(--accent-hover));
            color: #fff;
            font-family: var(--font-heading);
            font-size: 1.2rem;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            letter-spacing: 1px;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 123, 0, 0.4);
        }

        /* --- Animation --- */
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
    </style>
</head>
<body>

    <div class="admin-container">
        <h1>GYM ADMIN</h1>

        <!-- ================= MUSCLE FORM ================= -->
        <div class="form-card">
            <h2>Add Muscle</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="muscle_name">Muscle Name</label>
                    <input type="text" id="muscle_name" name="muscle_name" placeholder="e.g., Biceps Brachii" required>
                </div>
                <div class="form-group">
                    <label for="muscle_image">Muscle Image</label>
                    <div class="file-input-wrapper">
                        <label for="muscle_image" class="file-input-label">Choose File...</label>
                        <input type="file" id="muscle_image" name="muscle_image" required onchange="this.previousElementSibling.innerHTML = this.files[0].name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="muscle_desc">Description</label>
                    <textarea id="muscle_desc" name="muscle_desc" placeholder="A brief description of the muscle group..."></textarea>
                </div>
                <button type="submit" name="add_muscle" class="btn">Add Muscle</button>
            </form>
        </div>

        <!-- ================= MUSCLE HEAD FORM ================= -->
        <div class="form-card">
            <h2>Add Muscle Head</h2>
            <form method="POST" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="head_name">Head Name</label>
                    <input type="text" id="head_name" name="head_name" placeholder="e.g., Short Head" required>
                </div>
                <div class="form-group">
                    <label for="head_image">Head Image</label>
                    <div class="file-input-wrapper">
                        <label for="head_image" class="file-input-label">Choose File...</label>
                        <input type="file" id="head_image" name="head_image" required onchange="this.previousElementSibling.innerHTML = this.files[0].name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="head_desc">Description</label>
                    <textarea id="head_desc" name="head_desc" placeholder="A brief description of the muscle head..."></textarea>
                </div>
                <button type="submit" name="add_head" class="btn">Add Head</button>
            </form>
        </div>

        <!-- ================= EXERCISE FORM ================= -->
        <div class="form-card">
            <h2>Add Exercise</h2>
            <form method="POST">
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
                <div class="form-group">
                    <label for="exercise_name">Exercise Name</label>
                    <input type="text" id="exercise_name" name="exercise_name" placeholder="e.g., Barbell Curl" required>
                </div>
                <div class="form-group">
                    <label for="equipment">Equipment</label>
                    <input type="text" id="equipment" name="equipment" placeholder="e.g., Barbell, Dumbbells">
                </div>
                <div class="form-group">
                    <label for="sets_reps">Sets x Reps</label>
                    <input type="text" id="sets_reps" name="sets_reps" placeholder="e.g., 3 x 12">
                </div>
                <div class="form-group">
                    <label for="time_minutes">Time (minutes)</label>
                    <input type="number" id="time_minutes" name="time_minutes" placeholder="e.g., 5">
                </div>
                <div class="form-group">
                    <label for="rest_time">Rest Time (seconds)</label>
                    <input type="number" id="rest_time" name="rest_time" placeholder="e.g., 60">
                </div>
                <div class="form-group">
                    <label for="calories_burn">Calories Burn</label>
                    <!-- Fixed typo here: typetext="number" changed to type="number" -->
                    <input type="number" id="calories_burn" name="calories_burn" placeholder="e.g., 50">
                </div>
                <div class="form-group">
                    <label for="difficulty">Difficulty</label>
                    <select id="difficulty" name="difficulty">
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exercise_desc">Exercise Description</label>
                    <textarea id="exercise_desc" name="exercise_desc" placeholder="Describe how to perform the exercise..."></textarea>
                </div>
                <div class="form-group">
                    <label for="video_url">Video URL</label>
                    <input type="text" id="video_url" name="video_url" placeholder="e.g., https://www.youtube.com/watch?v=...">
                </div>
                <button type="submit" name="add_exercise" class="btn">Add Exercise</button>
            </form>
        </div>
    </div>

</body>
</html>