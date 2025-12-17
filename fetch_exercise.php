<?php
include './config/db.php';

$id = (int)$_GET['id'];

$q = "
SELECT 
    e.exercise_name,
    e.equipment,
    e.difficulty,
    e.time_minutes,
    mh.image_url,
    mh.description,
    mh.head_name,
    m.muscle_name
FROM exercises e
JOIN muscle_heads mh ON mh.head_id = e.head_id
JOIN muscles m ON m.muscle_id = mh.muscle_id
WHERE e.exercise_id = $id
";

$res = mysqli_query($conn, $q);
echo json_encode(mysqli_fetch_assoc($res));
