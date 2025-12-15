# Database Section:

## All Tables 
```sql
create table workout_categories(
	id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(100)
)

INSERT INTO workout_categories (name)
VALUES ('Chest'), ('Back'), ('Legs');

---- 

create table workout_subcategories(
	id int PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name varchar(100),
    FOREIGN KEY (category_id) REFERENCES workout_categories(id)
)

insert into workout_subcategories(category_id , name)
values
(1, "Upper Chest"),
(1, 'Middle Chest'),
(1, 'Lower Chest');


----

CREATE TABLE workouts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  title VARCHAR(150),
  difficulty VARCHAR(50),
  minutes INT,
  total_exercises INT,
  calories INT,
  FOREIGN KEY (category_id) REFERENCES workout_categories(id)
);



INSERT INTO workouts
(category_id, title, difficulty, minutes, total_exercises, calories)
VALUES
(1, 'Chest Workout', 'Intermediate', 45, 5, 350);


----

CREATE TABLE exercises (
  id INT AUTO_INCREMENT PRIMARY KEY,
  workout_id INT,
  subcategory_id INT,
  name VARCHAR(150),
  equipment VARCHAR(100),
  target VARCHAR(100),
  sets VARCHAR(20),
  slug VARCHAR(150),
  FOREIGN KEY (workout_id) REFERENCES workouts(id),
  FOREIGN KEY (subcategory_id) REFERENCES workout_subcategories(id)
);


INSERT INTO exercises
(workout_id, subcategory_id, name, equipment, target, sets, slug)
VALUES
(1, 1, 'Bench Press', 'Barbell', 'Upper Chest', '4x10', 'bench-press'),
(1, 1, 'Incline Dumbbell Press', 'Dumbbells', 'Upper Chest', '3x12', 'incline-dumbbell-press');

```