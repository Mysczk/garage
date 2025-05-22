CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    brand VARCHAR(50),
    model VARCHAR(50),
    year INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (email, username, password)
VALUES ('admin@example.com', 'admin', '$2y$12$mNlDoRRMvTFmefLuuImWQ.2QJ3aVqwcjF2FrsYTlH0OvihJALNFlu');

insert into cars (user_id, brand, model, year) values (1, 'Škoda', 'Octavia', 2020);