DROP TABLE IF EXISTS vertical_clues, horizontal_clues, cells, saved_cells, grids, users;
-- Table users
CREATE TABLE IF NOT EXISTS users     (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'registered', 'guest') DEFAULT 'registered',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table grids
CREATE TABLE IF NOT EXISTS grids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    user_id INT NOT NULL,
    num_rows INT NOT NULL,
    num_columns INT NOT NULL,
    difficulty ENUM('debutant', 'intermediaire', 'expert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- Table grilles en cours

CREATE TABLE IF NOT EXISTS saved_cells (
    user_id INT NOT NULL,
    grid_id INT ,

    rowa INT,
    col INT ,
    content VARCHAR(20),

    
    PRIMARY KEY (user_id, grid_id,rowa,col),
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table cellules
CREATE TABLE IF NOT EXISTS cells(
    grid_id INT ,
    rowa INT,
    col INT ,
    content VARCHAR(20),
    PRIMARY KEY ( grid_id,rowa,col),
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);


-- Table Définitions horizentales

CREATE TABLE IF NOT EXISTS horizontal_clues(
    id INT  ,
    grid_id INT ,
    content VARCHAR(20),
    PRIMARY KEY (id, grid_id),
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
) ;

-- Table Définitions verticales

CREATE TABLE IF NOT EXISTS vertical_clues(
    id INT  ,
    grid_id INT ,
    content VARCHAR(20),
    PRIMARY KEY (id, grid_id),
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
) ;