-- Drop existing views and tables if they exist
DROP VIEW IF EXISTS student_profile;
DROP TABLE IF EXISTS student_courses;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS documents;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS education;
DROP TABLE IF EXISTS students;

-- Create the students table
CREATE TABLE students (
    student_id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20),
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    address TEXT,
    date_of_birth DATE,
    password VARCHAR(50)
    -- Add other relevant fields as needed
);

-- Create the education table
CREATE TABLE education (
    education_id SERIAL PRIMARY KEY,
    student_id INT REFERENCES students(student_id),
    degree VARCHAR(100) NOT NULL,
    major VARCHAR(100) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    start_date DATE,
    end_date DATE
    -- Add other relevant fields as needed
);

-- Create the skills table
CREATE TABLE skills (
    skill_id SERIAL PRIMARY KEY,
    student_id INT REFERENCES students(student_id),
    skill_name VARCHAR(100) NOT NULL,
    proficiency VARCHAR(50)
    -- Add other relevant fields as needed
);

-- Create the documents table
CREATE TABLE documents (
    document_id SERIAL PRIMARY KEY,
    student_id INT REFERENCES students(student_id),
    title VARCHAR(100) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -- Add other relevant fields as needed
);

-- Create the courses table
CREATE TABLE courses (
    course_id SERIAL PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    instructor VARCHAR(100),
    description TEXT
    -- Add other relevant fields as needed
);

-- Create the student_courses table
CREATE TABLE student_courses (
    student_id INT REFERENCES students(student_id),
    course_id INT REFERENCES courses(course_id),
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, course_id)
);

-- Create the student_profile view
CREATE VIEW student_profile AS
SELECT 
    s.student_id,
    s.first_name,
    s.last_name,
    s.email,
    s.phone_number,
    s.emergency_contact_name,
    s.emergency_contact_phone,
    s.address,
    s.date_of_birth,
    d.title AS document_title,
    d.description AS document_description,
    d.file_path AS document_file_path,
    d.upload_date AS document_upload_date,
    sk.skill_name,
    sk.proficiency,
    e.degree AS education_degree,
    e.major AS education_major,
    e.institution AS education_institution,
    e.start_date AS education_start_date,
    e.end_date AS education_end_date
FROM students s
LEFT JOIN documents d ON s.student_id = d.student_id
LEFT JOIN skills sk ON s.student_id = sk.student_id
LEFT JOIN education e ON s.student_id = e.student_id;