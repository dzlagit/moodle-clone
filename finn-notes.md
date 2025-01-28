### IMPORTANT: In my code you will see I use "localhost:3307" when connecting to the database, this is because I already have an SQL server running on my network which is accessible by me forwarding requests to this port to that machine. MAKE SURE TO UPDATE IT BACK TO LOCALHOST OTHERWISE YOU WON'T BE ABLE TO CONNECT TO THE DB

To create the course table:
```
CREATE TABLE user_management.courses (
	id INT auto_increment PRIMARY KEY,
	name varchar(255) NULL,
	user_id INT NOT NULL,
	description TEXT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;
```

To create the course enrollment table:
```
CREATE TABLE user_management.course_enrollments (
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (course_id, user_id),
    FOREIGN KEY (course_id) REFERENCES user_management.courses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_management.users(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;
```

The way this will work is a staff member will be able to create a course, then they can enroll users on the course. When they enrol a user it will create an entry in the `course_enrollments` table.
The `course_enrollments` table is set up so that there can only ever be one entry where the `course_id` and `user_id` is the same, this means one user can't be enrolled multiple times in the same class.
If either a user is deleted or course deleted then it should automatically remove the enrollment record from this table.