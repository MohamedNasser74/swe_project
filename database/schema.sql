-- Virtual Career Counseling Platform Database Schema
-- Run this script to create the database structure

CREATE DATABASE IF NOT EXISTS career_counseling_platform;
USE career_counseling_platform;

-- Users table (students, counselors, admins)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('student', 'counselor', 'admin') NOT NULL DEFAULT 'student',
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    email_verified BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Counselor-managed student records
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    counselor_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    profile_image VARCHAR(255),
    education_level VARCHAR(120),
    specialization VARCHAR(150),
    status ENUM('active', 'inactive', 'completed') DEFAULT 'active',
    join_date DATE DEFAULT NULL,
    last_session_date DATE DEFAULT NULL,
    total_sessions INT DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY idx_students_counselor_email (counselor_id, email),
    CONSTRAINT fk_students_counselor FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Student profiles
CREATE TABLE student_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    education_level VARCHAR(100),
    field_of_study VARCHAR(100),
    graduation_year YEAR,
    career_interests TEXT,
    skills TEXT,
    experience_level ENUM('fresher', 'entry', 'intermediate', 'experienced'),
    resume_url VARCHAR(255),
    linkedin_profile VARCHAR(255),
    github_profile VARCHAR(255),
    portfolio_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Counselor profiles
CREATE TABLE counselor_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    specialization VARCHAR(100),
    experience_years INT,
    bio TEXT,
    qualifications TEXT,
    certifications TEXT,
    hourly_rate DECIMAL(10,2),
    available_from TIME,
    available_to TIME,
    available_days VARCHAR(20), -- JSON array of days
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_sessions INT DEFAULT 0,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Appointments/Sessions
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    counselor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration INT DEFAULT 60, -- minutes
    session_type ENUM('career_guidance', 'interview_prep', 'job_search') NOT NULL,
    status ENUM('scheduled', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    feedback TEXT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    meeting_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE
);



-- Job postings
CREATE TABLE job_postings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    company VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    job_type ENUM('full-time', 'part-time', 'internship', 'freelance') NOT NULL,
    experience_level VARCHAR(50),
    salary_min DECIMAL(10,2),
    salary_max DECIMAL(10,2),
    description TEXT NOT NULL,
    requirements TEXT,
    benefits TEXT,
    application_url VARCHAR(255),
    contact_email VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    expires_at DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Job applications
CREATE TABLE job_applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT NOT NULL,
    student_id INT NOT NULL,
    cover_letter TEXT,
    status ENUM('applied', 'under_review', 'interview', 'rejected', 'accepted') DEFAULT 'applied',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES job_postings(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);



-- Settings/Configuration
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default data



INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'Virtual Career Counseling Platform', 'Name of the website'),
('site_description', 'Empowering students with personalized career guidance', 'Site description'),
('max_appointment_duration', '120', 'Maximum appointment duration in minutes'),
('appointment_buffer_time', '30', 'Buffer time between appointments in minutes');

-- Dynamic Menu (Self-Referencing for nested menus)
CREATE TABLE menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(50) DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    role_access ENUM('all', 'guest', 'student', 'counselor', 'admin') DEFAULT 'all',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES menus(id) ON DELETE CASCADE
);

-- Create admin user (password: admin123 - hashed)
INSERT INTO users (username, email, password, first_name, last_name, role, email_verified) VALUES
('admin', 'admin@careerplatform.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', 'admin', TRUE);

-- Insert default menu items
INSERT INTO menus (title, url, icon, parent_id, sort_order, is_active, role_access) VALUES
('Home', '/', 'fas fa-home', NULL, 1, TRUE, 'all'),
('Learn More', '/home/learn-more', 'fas fa-book-open', NULL, 2, TRUE, 'all'),
('Services', '/home/services', 'fas fa-briefcase', NULL, 3, TRUE, 'all'),
('Contact', '/home/contact', 'fas fa-envelope', NULL, 4, TRUE, 'all');

-- Get the ID of 'Services' to add sub-menu items (assuming it's ID 3)
INSERT INTO menus (title, url, icon, parent_id, sort_order, is_active, role_access) VALUES
('Career Guidance', '/home/services#career-guidance', 'fas fa-compass', 3, 1, TRUE, 'all'),
('Interview Prep', '/home/services#interview-prep', 'fas fa-user-tie', 3, 2, TRUE, 'all'),
('Job Search', '/home/services#job-search', 'fas fa-search', 3, 3, TRUE, 'all');