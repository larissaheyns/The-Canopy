CREATE DATABASE IF NOT EXISTS The_Canopy;
Use The_Canopy;

CREATE TABLE minds (
   mind_id  INT AUTO_INCREMENT PRIMARY KEY,
   cosmic_signature VARCHAR(50) NOT NULL UNIQUE,
   alias    VARCHAR(50) NULL UNIQUE,
   password_hash VARCHAR(255) NULL, 
   user_role ENUM('Drifter', 'Awakened', 'Architect') NOT NULL DEFAULT 'Drifter', 
   dominant_state ENUM('Liminal', 'Lucid', 'Nightmare') NOT NULL DEFAULT 'Liminal',
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
   last_active DATETIME NULL
);

CREATE TABLE emanations (
    emanations_id   INT AUTO_INCREMENT PRIMARY KEY,
    mind_id         INT NOT NULL,
    title   VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    dream_type    ENUM('Nightmare', 'Lucid', 'Liminal') NOT NULL, 
    is_core_memory  BOOLEAN  Default FALSE, 
    is_visible  BOOLEAN  Default FALSE, 
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP, 
    updated_at  DATETIME NULL,
    FOREIGN KEY (mind_id) REFERENCES minds(mind_id)
);

CREATE TABLE resonances  (
    resonances_id  INT AUTO_INCREMENT PRIMARY KEY,
    emanations_id   INT NOT NULL,
    mind_id INT NOT NULL,
    tag_type    VARCHAR(50) NOT NULL, 
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (emanations_id) REFERENCES emanations(emanations_id),
    FOREIGN KEY (mind_id) REFERENCES minds(mind_id)
);

CREATE TABLE interpretations  (
    interpretations_id  INT AUTO_INCREMENT PRIMARY KEY,
    mind_id             INT NOT NULL,
    emanations_id       INT NOT NULL,
    interpretation      TEXT NOT NULL, 
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mind_id) REFERENCES minds(mind_id),
    FOREIGN KEY (emanations_id) REFERENCES emanations(emanations_id)
);
