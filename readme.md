# Card ki Info
muje apne gym ka liya aik database ki tables create karna hai kch is terha ka

Muscle: Chest
SubCategory ka andar oiska Head: Upper Chest or oiski Images bhe ho

Exercise Name: Inline Dumbbell Press
Equipment: Barbbell
Time : 45 Minutes
Rest Time: 2
Calories Bearn : 300
Difficulty: Advanced
Sets and Rep: 4x12
Ager Exercise ko Describe karna ho oiska bhe hona chaiya
Video ka Link ka option bhe hona chaiya jaha video show ho

is subka liya muje tables bana kardu mysql mein 

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
    time_minutes INT,
    rest_time INT,
    calories_burn INT,
    difficulty ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
    description TEXT,
    video_url VARCHAR(255),
    FOREIGN KEY (head_id) REFERENCES muscle_heads(head_id) ON DELETE CASCADE
);

```