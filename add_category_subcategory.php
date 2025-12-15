<?php

include "./config/db.php";

?>

<h2>Add Category</h2>

<form method="POST">
  <input type="text"
         name="category_name"
         placeholder="Chest / Shoulder / Legs"
         required>

  <button type="submit" name="add_category">
    Add Category
  </button>
</form>


<hr>

<h2>Add Sub Category</h2>

<form method="POST">

  <label>Select Category</label><br>
  <select name="category_id" required>
    <option value="">Select Category</option>
    <?php
    $catQ = mysqli_query($conn, "SELECT * FROM workout_categories");
    while ($cat = mysqli_fetch_assoc($catQ)) {
      echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
    }
    ?>
  </select>

  <br><br>

  <input type="text"
         name="subcategory_name"
         placeholder="Upper Chest / Front Delt"
         required>

  <button type="submit" name="add_subcategory">
    Add Sub Category
  </button>
</form>


<?php

/* ADD CATEGORY */
if (isset($_POST['add_category'])) {

  $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

  $check = mysqli_query($conn,
    "SELECT id FROM workout_categories WHERE name='$category_name'"
  );

  if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn,
      "INSERT INTO workout_categories (name)
       VALUES ('$category_name')"
    );
    echo "<p style='color:green'>Category added successfully</p>";
  } else {
    echo "<p style='color:orange'>Category already exists</p>";
  }
}

/* ADD SUB CATEGORY */
if (isset($_POST['add_subcategory'])) {

  $category_id = $_POST['category_id'];
  $subcategory_name = mysqli_real_escape_string($conn, $_POST['subcategory_name']);

  $checkSub = mysqli_query($conn,
    "SELECT id FROM workout_subcategories
     WHERE name='$subcategory_name'
     AND category_id='$category_id'"
  );

  if (mysqli_num_rows($checkSub) == 0) {
    mysqli_query($conn,
      "INSERT INTO workout_subcategories (category_id, name)
       VALUES ('$category_id', '$subcategory_name')"
    );
    echo "<p style='color:green'>Sub Category added successfully</p>";
  } else {
    echo "<p style='color:orange'>Sub Category already exists</p>";
  }
}
?>
