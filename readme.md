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
-- 1️⃣ Muscles Table
CREATE TABLE muscles (
    muscle_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_name VARCHAR(50) NOT NULL,
    description TEXT
);

-- 2️⃣ SubCategories Table
CREATE TABLE subcategories (
    subcategory_id INT AUTO_INCREMENT PRIMARY KEY,
    muscle_id INT NOT NULL,
    subcategory_name VARCHAR(50) NOT NULL,
    image_url VARCHAR(255),
    description TEXT,
    FOREIGN KEY (muscle_id) REFERENCES muscles(muscle_id) ON DELETE CASCADE
);

-- 3️⃣ Exercises Table
CREATE TABLE exercises (
    exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    subcategory_id INT NOT NULL,
    exercise_name VARCHAR(100) NOT NULL,
    equipment VARCHAR(50),
    time_minutes INT,
    rest_time INT,
    calories_burn INT,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner',
    sets_reps VARCHAR(20),
    description TEXT,
    video_url VARCHAR(255),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(subcategory_id) ON DELETE CASCADE
);

---

-- Muscles
INSERT INTO muscles (muscle_name, description) VALUES 
('Chest', 'Chest muscles upper and lower parts include karte hain.');

-- SubCategories
INSERT INTO subcategories (muscle_id, subcategory_name, image_url, description) VALUES 
(1, 'Upper Chest', 'https://example.com/upper-chest.jpg', 'Upper part of chest muscles.');

-- Exercises
INSERT INTO exercises 
(subcategory_id, exercise_name, equipment, time_minutes, rest_time, calories_burn, difficulty, sets_reps, description, video_url)
VALUES 
(1, 'Inline Dumbbell Press', 'Barbell', 45, 2, 300, 'Advanced', '4x12', 'Press dumbbells inline for upper chest.', 'https://example.com/video.mp4');

```