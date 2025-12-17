## MYSQL Tables:

```sql
CREATE TABLE muscles (
    muscle_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_name VARCHAR(50) NOT NULL,
    image_url VARCHAR(255),       -- Muscle main image
    description TEXT
);


CREATE TABLE muscle_heads (
    head_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_id INT NOT NULL,
    head_name VARCHAR(50) NOT NULL,   -- Specific head, e.g., "Upper Chest"
    image_url VARCHAR(255),           -- Image for this specific head
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
    description TEXT,
    video_url VARCHAR(255),
    FOREIGN KEY (head_id) REFERENCES muscle_heads(head_id) ON DELETE CASCADE
);

```



```php
<?php
/* DATABASE CONNECTION */
include "./config/db.php";

/* ================= ADD MUSCLE ================= */
if (isset($_POST['add_muscle'])) {
    $muscle_name = $_POST['muscle_name'];
    $description = $_POST['muscle_desc'];

    $image = $_FILES['muscle_image']['name'];
    $tmp  = $_FILES['muscle_image']['tmp_name'];
    move_uploaded_file($tmp, "uploads/".$image);

    mysqli_query($conn,
        "INSERT INTO muscles (muscle_name,image_url,description)
         VALUES ('$muscle_name','$image','$description')"
    );
}

/* ================= ADD MUSCLE HEAD ================= */
if (isset($_POST['add_head'])) {
    $muscle_id = $_POST['muscle_id'];
    $head_name = $_POST['head_name'];
    $description = $_POST['head_desc'];

    $image = $_FILES['head_image']['name'];
    $tmp  = $_FILES['head_image']['tmp_name'];
    move_uploaded_file($tmp, "uploads/".$image);

    mysqli_query($conn,
        "INSERT INTO muscle_heads (muscle_id,head_name,image_url,description)
         VALUES ('$muscle_id','$head_name','$image','$description')"
    );
}

/* ================= ADD EXERCISE ================= */
if (isset($_POST['add_exercise'])) {
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
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>

<!-- ================= MUSCLE FORM ================= -->
<h2>Add Muscle</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="muscle_name" placeholder="Muscle Name" required><br><br>
    <input type="file" name="muscle_image" required><br><br>
    <textarea name="muscle_desc" placeholder="Description"></textarea><br><br>
    <button type="submit" name="add_muscle">Add Muscle</button>
</form>

<hr>

<!-- ================= MUSCLE HEAD FORM ================= -->
<h2>Add Muscle Head</h2>
<form method="POST" enctype="multipart/form-data">

    <select name="muscle_id" required>
        <option value="">Select Muscle</option>
        <?php while ($m = mysqli_fetch_assoc($muscles)) { ?>
            <option value="<?php echo $m['muscle_id']; ?>">
                <?php echo $m['muscle_name']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <input type="text" name="head_name" placeholder="Head Name" required><br><br>
    <input type="file" name="head_image" required><br><br>
    <textarea name="head_desc" placeholder="Description"></textarea><br><br>

    <button type="submit" name="add_head">Add Head</button>
</form>

<hr>

<!-- ================= EXERCISE FORM ================= -->
<h2>Add Exercise</h2>
<form method="POST">

<select name="head_id" required>
    <option value="">Select Muscle Head</option>
    <?php while ($h = mysqli_fetch_assoc($heads)) { ?>
        <option value="<?php echo $h['head_id']; ?>">
            <?php echo $h['muscle_name']; ?> → <?php echo $h['head_name']; ?>
        </option>
    <?php } ?>
</select>
<br><br>

    <input type="text" name="exercise_name" placeholder="Exercise Name" required><br><br>
    <input type="text" name="equipment" placeholder="Equipment"><br><br>
    <input type="text" name="sets_reps" placeholder="Sets x Reps"><br><br>
    <input type="number" name="time_minutes" placeholder="Time (minutes)"><br><br>
    <input type="number" name="rest_time" placeholder="Rest Time (sec)"><br><br>
    <input typetext="number" name="calories_burn" placeholder="Calories Burn"><br><br>

    <select name="difficulty">
        <option>Beginner</option>
        <option>Intermediate</option>
        <option>Advanced</option>
    </select><br><br>

    <textarea name="exercise_desc" placeholder="Exercise Description"></textarea><br><br>
    <input type="text" name="video_url" placeholder="Video URL"><br><br>

    <button type="submit" name="add_exercise">Add Exercise</button>
</form>

</body>
</html>

```