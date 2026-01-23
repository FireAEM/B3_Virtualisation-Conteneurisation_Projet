CREATE DATABASE IF NOT EXISTS tasksdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE tasksdb;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    status ENUM('todo','done') NOT NULL DEFAULT 'todo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tasks (title, status) 
VALUES ('Example task 1', 'todo');
