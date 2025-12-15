<?php
include './config/db.php';

// Handle form submission
if (isset($_POST['submit'])) {

    $muscle_id = 0; // default

    if (!empty($_POST['muscle_name'])) {
        $muscle_name = $_POST['muscle_name'];
        $muscle_description = $_POST['muscle_description'];
        
        $muscle_image = "";

        if (isset($_FILES['muscle_image']) && $_FILES['muscle_image']['name'] != "") {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["muscle_image"]["name"]);
            if (move_uploaded_file($_FILES["muscle_image"]["tmp_name"], $target_file)) {
                $muscle_image = $target_file;
            }
        }

        $sql = "INSERT INTO muscles (muscle_name, image_url, description) 
                VALUES ('$muscle_name', '$muscle_image', '$muscle_description')";
        if (mysqli_query($conn, $sql)) {
            $muscle_id = mysqli_insert_id($conn); // get inserted muscle_id
        } else {
            echo "Muscle Error: " . mysqli_error($conn) . "<br>";
        }
    }

    // --- 2️⃣ Add Muscle Head ---
    $head_id = 0;
    if (!empty($_POST['head_name'])) {
        // Use dropdown muscle_id if selected, else newly inserted
        $selected_muscle_id = !empty($_POST['existing_muscle']) ? $_POST['existing_muscle'] : $muscle_id;

        $head_name = $_POST['head_name'];
        $head_description = $_POST['head_description'];

        // Head Image Upload
        $head_image = "";
        if (isset($_FILES['head_image']) && $_FILES['head_image']['name'] != "") {
            $target_file = "uploads/" . basename($_FILES["head_image"]["name"]);
            if (move_uploaded_file($_FILES["head_image"]["tmp_name"], $target_file)) {
                $head_image = $target_file;
            }
        }

        $sql = "INSERT INTO muscle_heads (muscle_id, head_name, image_url, description)
                VALUES ('$selected_muscle_id', '$head_name', '$head_image', '$head_description')";
        if (mysqli_query($conn, $sql)) {
            $head_id = mysqli_insert_id($conn);
        } else {
            echo "Head Error: " . mysqli_error($conn) . "<br>";
        }
    }

    // --- 3️⃣ Add Exercise ---
    if (!empty($_POST['exercise_name'])) {
        $selected_head_id = !empty($_POST['existing_head']) ? $_POST['existing_head'] : $head_id;

        $exercise_name = $_POST['exercise_name'];
        $equipment = $_POST['equipment'];
        $sets_reps = $_POST['sets_reps'];
        $time_minutes = $_POST['time_minutes'];
        $rest_time = $_POST['rest_time'];
        $calories_burn = $_POST['calories_burn'];
        $difficulty = $_POST['difficulty'];
        $description = $_POST['exercise_description'];
        $video_url = $_POST['video_url'];

        $sql = "INSERT INTO exercises 
            (head_id, exercise_name, equipment, sets_reps, time_minutes, rest_time, calories_burn, difficulty, description, video_url)
            VALUES 
            ('$selected_head_id', '$exercise_name', '$equipment', '$sets_reps', '$time_minutes', '$rest_time', '$calories_burn', '$difficulty', '$description', '$video_url')";

        if (mysqli_query($conn, $sql)) {
            echo "Exercise added successfully!<br>";
        } else {
            echo "Exercise Error: " . mysqli_error($conn) . "<br>";
        }
    }

}

// Fetch existing muscles and heads for dropdowns
$muscles = mysqli_query($conn, "SELECT * FROM muscles");
$heads = mysqli_query($conn, "SELECT * FROM muscle_heads");
?>

<h2>Add Muscle, Head, and Exercise</h2>
<form method="POST" enctype="multipart/form-data">

    <!-- Muscle -->
    <h3>Muscle</h3>
    Add New Muscle: <input type="text" name="muscle_name">
    Description: <textarea name="muscle_description"></textarea><br>
    Image: <input type="file" name="muscle_image"><br>
    OR Select Existing Muscle:
    <select name="existing_muscle">
        <option value="">--Select--</option>
        <?php while($row = mysqli_fetch_assoc($muscles)) { ?>
            <option value="<?php echo $row['muscle_id']; ?>"><?php echo $row['muscle_name']; ?></option>
        <?php } ?>
    </select>
    <hr>

    <!-- Muscle Head -->
    <h3>Muscle Head</h3>
    Add New Head: <input type="text" name="head_name">
    Description: <textarea name="head_description"></textarea><br>
    Image: <input type="file" name="head_image"><br>
    OR Select Existing Head:
    <select name="existing_head">
        <option value="">--Select--</option>
        <?php while($row = mysqli_fetch_assoc($heads)) { ?>
            <option value="<?php echo $row['head_id']; ?>"><?php echo $row['head_name']; ?></option>
        <?php } ?>
    </select>
    <hr>

    <!-- Exercise -->
    <h3>Exercise</h3>
    Exercise Name: <input type="text" name="exercise_name"><br>
    Equipment: <input type="text" name="equipment"><br>
    Sets x Reps: <input type="text" name="sets_reps"><br>
    Time (minutes): <input type="number" name="time_minutes"><br>
    Rest (seconds): <input type="number" name="rest_time"><br>
    Calories Burn: <input type="number" name="calories_burn"><br>
    Difficulty: 
    <select name="difficulty">
        <option>Beginner</option>
        <option>Intermediate</option>
        <option>Advanced</option>
    </select><br>
    Description: <textarea name="exercise_description"></textarea><br>
    Video URL: <input type="text" name="video_url"><br><br>

    <input type="submit" name="submit" value="Add All">
</form>
