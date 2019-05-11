CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username varchar(255) NOT NULL UNIQUE,
  password varchar(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS orders (
  id SERIAL PRIMARY KEY, 
  userid INTEGER NOT NULL REFERENCES users(id) ,
  productid varchar(255) NOT NULL,
  issuedate  TIMESTAMP NOT NULL DEFAULT current_timestamp
);
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$SF2MypoQBo/0YmzZSPePzuPT/9o.Q/5jaIVfnBpk3DsAPrFpQZRBK');

