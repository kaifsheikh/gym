<?php
include './config/db.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Exercise ID missing']);
    exit;
}

$id = (int) $_GET['id'];

$q = "
    SELECT 
        e.exercise_name,
        e.equipment,
        e.difficulty,
        mh.image_url,
        mh.description,
        mh.head_name,
        m.muscle_name
    FROM exercises e
    INNER JOIN muscle_heads mh ON mh.head_id = e.head_id
    INNER JOIN muscles m ON m.muscle_id = mh.muscle_id
    WHERE e.exercise_id = $id
";

$res = mysqli_query($conn, $q);

// 🔴 Query error handling
if (!$res) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$data = mysqli_fetch_assoc($res);

echo json_encode($data);
