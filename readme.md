## MYSQL Tables:

```sql
CREATE TABLE muscles (
    muscle_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_name VARCHAR(50) NOT NULL,
    image_url VARCHAR(255)      -- Muscle main image
);


CREATE TABLE muscle_heads (
    head_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_id INT NOT NULL,
    head_name VARCHAR(50) NOT NULL,   -- Specific head, e.g., "Upper Chest"
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
    FOREIGN KEY (head_id) REFERENCES muscle_heads(head_id) ON DELETE CASCADE
);

```