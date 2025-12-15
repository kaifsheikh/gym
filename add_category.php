<?php
include "./config/db.php";
?>

<h2>Add Exercise</h2>

<form method="POST">

  <!-- Sub Category -->
  <label>Select Sub Category</label><br>
  <select name="subcategory_id" required>
    <option value="">Select Sub Category</option>
    <?php
    $sq = mysqli_query($conn, "
        SELECT s.id, s.name AS sub_name, c.name AS cat_name 
        FROM workout_subcategories s 
        JOIN workout_categories c ON s.category_id = c.id
        ORDER BY c.name, s.name
    ");
    while ($s = mysqli_fetch_assoc($sq)) {
      echo "<option value='{$s['id']}'>{$s['cat_name']} → {$s['sub_name']}</option>";
    }
    ?>
  </select>

  <br><br>

  <!-- Workout -->
  <label>Select Workout</label><br>
  <select name="workout_id" required>
    <option value="">Select Workout</option>
    <?php
    $wq = mysqli_query($conn, "SELECT * FROM workouts ORDER BY title");
    if(mysqli_num_rows($wq) > 0){
        while ($w = mysqli_fetch_assoc($wq)) {
          echo "<option value='{$w['id']}'>{$w['title']}</option>";
        }
    } else {
        echo "<option value=''>No Workouts Available</option>";
    }
    ?>
  </select>

  <br><br>

  <input type="text" name="exercise_name" placeholder="Exercise Name" required>
  <input type="text" name="equipment" placeholder="Equipment">
  <input type="text" name="target" placeholder="Target Muscle">
  <input type="text" name="sets" placeholder="Sets (e.g., 4x10)">

  <br><br>

  <button type="submit" name="add_exercise">Add Exercise</button>

</form>

<?php
if (isset($_POST['add_exercise'])) {

  $subcategory_id = $_POST['subcategory_id'];
  $workout_id     = $_POST['workout_id'];
  $name           = mysqli_real_escape_string($conn, $_POST['exercise_name']);
  $equipment      = mysqli_real_escape_string($conn, $_POST['equipment']);
  $target         = mysqli_real_escape_string($conn, $_POST['target']);
  $sets           = mysqli_real_escape_string($conn, $_POST['sets']);
  $slug           = strtolower(str_replace(" ", "-", $name));

  if($workout_id == '' || $subcategory_id == ''){
      echo "<p style='color:red'>Please select Workout and Sub Category first</p>";
      exit;
  }

  $insertExercise = "
      INSERT INTO exercises
      (workout_id, subcategory_id, name, equipment, target, sets, slug)
      VALUES
      ('$workout_id', '$subcategory_id', '$name', '$equipment', '$target', '$sets', '$slug')
  ";

  if (mysqli_query($conn, $insertExercise)) {
      echo "<p style='color:green'>Exercise added successfully</p>";
  } else {
      echo "<p style='color:red'>Exercise Error: ".mysqli_error($conn)."</p>";
  }
}
?>
