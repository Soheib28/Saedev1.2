CREATE DATABASE green_travel;

USE green_travel;

CREATE TABLE hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(2, 1) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);

INSERT INTO hotels (name, location, price_per_night, rating, image_url, description) VALUES
('Ecolodge Amazonie', 'Amazonie, Brésil', 120.00, 4.5, 'img/eco-lodge.jpg', 'Un hébergement respectueux de l\'environnement niché au cœur de la forêt amazonienne.'),
('Cabane en Montagne', 'Alpes, France', 90.00, 4.0, 'img/mountain-cabin.jpg', 'Une retraite paisible entourée de montagnes majestueuses.'),
('Maison de Plage', 'Bora Bora, Polynésie', 150.00, 4.8, 'img/beach-house.jpg', 'Détendez-vous dans cette maison de plage offrant une vue imprenable sur l\'océan.');
