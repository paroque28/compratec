CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY,
  username varchar(255) NOT NULL UNIQUE,
  password varchar(255) NOT NULL
);
INSERT INTO users (id, username, password) VALUES (1, 'admin', 'admin');