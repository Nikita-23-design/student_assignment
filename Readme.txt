Module File Structure

modules/custom/student_assignment/
├── student_assignment.info.yml
├── student_assignment.module
├── student_assignment.routing.yml
├── student_assignment.permissions.yml
├── src/
│   ├── Controller/
│   │   ├── StudentAssignmentController.php
│   │   └── TeacherController.php
│   └── Form/
│       ├── AssignmentForm.php
│       ├── ManageExtensionsForm.php
│       └── SubmitMarksForm.php
└── student_assignment.install

Step:

1) Run the SQL commnad in databse to create the table

CREATE TABLE student_submissions (
  id SERIAL PRIMARY KEY,
  student_roll_no VARCHAR(255) NOT NULL,
  student_name VARCHAR(255) NOT NULL,
  assignment_file VARCHAR(255) NOT NULL,
  marks VARCHAR(255)
);



2) Enable the module

3)Access the data from urls.


4) Uploaded files will be strore in sites->default->files->student_files
Provide nessasry write permisson.


URL:

1)View Student Submissions - /teacher/view-submissions
2)Submit assignment - /student/submit-assignment
3)Submit Marks - /teacher/submit-marks/{student_id}




