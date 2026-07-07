-- ==========================================================
-- HRFlowSn Database Schema
-- Version 1.0
-- ==========================================================

DROP DATABASE IF EXISTS hrflowsn_db;
CREATE DATABASE hrflowsn_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hrflowsn_db;

CREATE TABLE company(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 logo VARCHAR(255),
 ninea VARCHAR(50),
 rccm VARCHAR(50),
 address TEXT,
 phone VARCHAR(30),
 email VARCHAR(120),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE roles(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(50) NOT NULL UNIQUE,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE departments(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE positions(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users(
 id INT AUTO_INCREMENT PRIMARY KEY,
 role_id INT NOT NULL,
 email VARCHAR(120) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 last_login DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_user_role FOREIGN KEY(role_id) REFERENCES roles(id)
);

CREATE TABLE employees(
 id INT AUTO_INCREMENT PRIMARY KEY,
 employee_code VARCHAR(20) UNIQUE NOT NULL,
 user_id INT NOT NULL UNIQUE,
 department_id INT NOT NULL,
 position_id INT NOT NULL,
 manager_id INT NULL,
 first_name VARCHAR(100) NOT NULL,
 last_name VARCHAR(100) NOT NULL,
 gender ENUM('Male','Female') NOT NULL,
 birth_date DATE NOT NULL,
 birth_place VARCHAR(100),
 nationality VARCHAR(100) DEFAULT 'Senegalese',
 marital_status ENUM('Single','Married','Divorced','Widowed'),
 phone VARCHAR(30),
 email VARCHAR(120),
 address TEXT,
 qualification VARCHAR(150),
 hire_date DATE NULL,
 base_salary DECIMAL(12,2) NULL,
 photo VARCHAR(255),
 cv VARCHAR(255),
 identity_document VARCHAR(255),
 diploma VARCHAR(255),
 contract_scan VARCHAR(255),
 status ENUM('Active','On Leave','Suspended','Resigned','Retired') DEFAULT 'Active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id),
 FOREIGN KEY(department_id) REFERENCES departments(id),
 FOREIGN KEY(position_id) REFERENCES positions(id),
 FOREIGN KEY(manager_id) REFERENCES employees(id)
);

CREATE TABLE contracts(
 id INT AUTO_INCREMENT PRIMARY KEY,
 contract_code VARCHAR(20) UNIQUE NOT NULL,
 employee_id INT NOT NULL,
 contract_type ENUM('CDI','CDD','Stage','Essai') NOT NULL,
 salary DECIMAL(12,2) NOT NULL,
 start_date DATE NOT NULL,
 end_date DATE NULL,
 trial_period INT DEFAULT 0,
 signed_at DATE,
 status ENUM('En cours','Expire','Resilie') DEFAULT 'En cours',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id)
);

CREATE TABLE leave_types(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 allowed_days INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE leave_requests(
 id INT AUTO_INCREMENT PRIMARY KEY,
 employee_id INT NOT NULL,
 leave_type_id INT NOT NULL,
 start_date DATE NOT NULL,
 end_date DATE NOT NULL,
 reason TEXT,
 status ENUM('Pending','Approved','Rejected','Cancelled') DEFAULT 'Pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id),
 FOREIGN KEY(leave_type_id) REFERENCES leave_types(id)
);

CREATE TABLE payrolls(
 id INT AUTO_INCREMENT PRIMARY KEY,
 payroll_code VARCHAR(20) UNIQUE NOT NULL,
 employee_id INT NOT NULL,
 month TINYINT NOT NULL,
 year YEAR NOT NULL,
 base_salary DECIMAL(12,2),
 bonus DECIMAL(12,2) DEFAULT 0,
 overtime_hours DECIMAL(6,2) DEFAULT 0,
 absence_days DECIMAL(5,2) DEFAULT 0,
 gross_salary DECIMAL(12,2),
 ipres DECIMAL(12,2),
 css DECIMAL(12,2),
 cfce DECIMAL(12,2),
 income_tax DECIMAL(12,2),
 net_salary DECIMAL(12,2),
 generated_at DATETIME,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id)
);

CREATE TABLE trainings(
 id INT AUTO_INCREMENT PRIMARY KEY,
 training_code VARCHAR(20) UNIQUE,
 title VARCHAR(150),
 description TEXT,
 location VARCHAR(150),
 start_date DATE,
 end_date DATE,
 status ENUM('Programmee','En cours','Terminee') DEFAULT 'Programmee',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE training_participants(
 training_id INT,
 employee_id INT,
 attendance BOOLEAN DEFAULT FALSE,
 PRIMARY KEY(training_id,employee_id),
 FOREIGN KEY(training_id) REFERENCES trainings(id),
 FOREIGN KEY(employee_id) REFERENCES employees(id)
);

CREATE TABLE evaluations(
 id INT AUTO_INCREMENT PRIMARY KEY,
 employee_id INT NOT NULL,
 evaluation_date DATE,
 objectives TEXT,
 score DECIMAL(4,2),
 comments TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id)
);

CREATE TABLE audit_logs(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 action TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id)
);

INSERT INTO roles(name) VALUES
('Administrateur'),('RH'),('Manager'),('Employe');

INSERT INTO departments(name) VALUES
('Direction'),('Ressources Humaines'),('Comptabilite'),
('Finance'),('Commercial'),('Informatique');

INSERT INTO positions(name) VALUES
('Directeur'),('Responsable RH'),('Comptable'),
('Developpeur'),('Commercial'),('Assistant RH');

INSERT INTO leave_types(name,allowed_days) VALUES
('Conge annuel',30),
('Conge maladie',30),
('Conge maternite',98),
('Conge exceptionnel',5);

INSERT INTO company(name,email,phone)
VALUES('HRFlowSn Demo','contact@hrflowsn.sn','+221770000000');
