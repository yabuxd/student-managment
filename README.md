# School Management System API

This is a well-structured PHP backend for a multi-school management system in Ethiopia.

## Database Setup
1. Open XAMPP and start MySQL.
2. Go to `phpMyAdmin` or use a MySQL client.
3. Import the schema found at: `sql/schema.sql`
4. Update `src/Config/db.php` if your database credentials differ from the default (root/no password).

## API Endpoints

### 1. Record Assessment Score
- **URL**: `/api/assessments/record`
- **Method**: `POST`
- **Body** (JSON):
  ```json
  {
    "assessment_id": 1,
    "student_id": 1,
    "score": 85.5
  }
  ```

### 2. Get Student Academic Report
- **URL**: `/api/assessments/report?student_id=1&term_id=1`
- **Method**: `GET`
- **Description**: Returns weighted averages for all subjects and the overall GPA.

### 3. Get Section Performance & Rankings
- **URL**: `/api/assessments/performance?section_id=1&term_id=1`
- **Method**: `GET`
- **Description**: Returns the average score of the section and a ranked list of students.

## Project Structure
- `public/index.php`: API Entry point and router.
- `src/Config/`: Database connection.
- `src/Controllers/`: Handles API requests.
- `src/Services/`: Business logic (Grading, Ranking, ID Generation).
- `src/Models/`: Database interaction layers.
- `sql/schema.sql`: Complete database schema.
