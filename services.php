<?php
include 'db.php';

$search_country = isset($_GET['country']) ? $_GET['country'] : '';

if ($search_country) {
    $stmt = $pdo->prepare("SELECT * FROM hotels WHERE location LIKE :country");
    $stmt->execute(['country' => '%'.$search_country.'%']);
} else {
    $stmt = $pdo->query("SELECT * FROM hotels");
}
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste unique des pays
$stmt_countries = $pdo->query("SELECT DISTINCT SUBSTRING_INDEX(location, ',', -1) as country FROM hotels");
$countries = $stmt_countries->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Green Travel</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .services-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                        url('https://images.unsplash.com/photo-1682687220067-469c053495b1') center/cover;
            padding: 60px 20px;
            text-align: center;
            color: white;
        }

        .services-hero h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .services-hero p {
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
        }

        .offers-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .offer {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .offer:hover {
            transform: translateY(-5px);
        }

        .offer-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .offer-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .offer:hover .offer-image img {
            transform: scale(1.05);
        }

        .offer-content {
            padding: 20px;
        }

        .offer-title {
            font-size: 1.5em;
            color: var(--primary-green);
            margin-bottom: 10px;
        }

        .eco-rating {
            color: var(--primary-green);
            margin-bottom: 10px;
        }

        .eco-badges {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .eco-badge {
            background-color: var(--primary-light);
            color: var(--primary-green);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .price {
            font-size: 1.2em;
            color: #333;
            font-weight: bold;
            margin: 15px 0;
        }

        .details-button {
            background-color: var(--primary-green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .details-button:hover {
            background-color: var(--hover-green);
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .offers-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Styles pour les filtres */
        .filters-section {
            background: var(--primary-light);
            padding: 30px 20px;
            margin-bottom: 40px;
        }

        .filters-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--primary-green);
        }

        .filter-group select, 
        .filter-group input[type="number"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }

        .filter-buttons {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .filter-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .apply-filters {
            background: var(--primary-green);
            color: white;
        }

        .reset-filters {
            background: #f0f0f0;
            color: #333;
        }

        .filter-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Tags de tri */
        .sort-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0;
            padding: 0 20px;
        }

        .sort-tag {
            padding: 8px 15px;
            background: white;
            border: 1px solid var(--primary-green);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sort-tag.active {
            background: var(--primary-green);
            color: white;
        }

        .sort-tag:hover {
            background: var(--primary-light);
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="services.php" class="active">Services</a></li>
                <li><a href="about.html">À propos</a></li>
                <li><a href="equipe.html">Notre Équipe</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section class="services-hero">
        <h1>Nos Destinations Éco-responsables</h1>
        <p>Découvrez nos destinations soigneusement sélectionnées pour leur engagement environnemental et leur authenticité.</p>
    </section>

    <section class="filters-section">
        <div class="filters-container">
            <div class="filter-group">
                <label for="country">Pays</label>
                <select id="country">
                    <option value="all">Tous les pays</option>
                    <option value="costa-rica">Costa Rica</option>
                    <option value="islande">Islande</option>
                    <option value="japon">Japon</option>
                    <option value="norvege">Norvège</option>
                    <option value="indonesie">Indonésie</option>
                    <option value="nouvelle-zelande">Nouvelle-Zélande</option>
                    <option value="thailande">Thaïlande</option>
                    <option value="suisse">Suisse</option>
                    <option value="maldives">Maldives</option>
                    <option value="perou">Pérou</option>
                    <option value="canada">Canada</option>
                    <option value="grece">Grèce</option>
                    <option value="vietnam">Vietnam</option>
                    <option value="maroc">Maroc</option>
                    <option value="tanzanie">Tanzanie</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="destination-type">Type de destination</label>
                <select id="destination-type">
                    <option value="all">Toutes les destinations</option>
                    <option value="nature">Nature & Aventure</option>
                    <option value="culture">Culture & Tradition</option>
                    <option value="wellbeing">Bien-être & Détente</option>
                    <option value="eco">100% Écologique</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="price-range">Budget maximum par nuit</label>
                <input type="number" id="price-range" min="0" max="1000" step="50" value="300">
            </div>

            <div class="filter-group">
                <label for="duration">Durée du séjour</label>
                <select id="duration">
                    <option value="all">Toutes durées</option>
                    <option value="short">Court séjour (1-3 jours)</option>
                    <option value="medium">Moyen séjour (4-7 jours)</option>
                    <option value="long">Long séjour (8+ jours)</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button class="filter-button apply-filters">Appliquer les filtres</button>
                <button class="filter-button reset-filters">Réinitialiser</button>
            </div>
        </div>
    </section>

    <div class="sort-tags">
        <span class="sort-tag active">Tous</span>
        <span class="sort-tag">Prix croissant</span>
        <span class="sort-tag">Prix décroissant</span>
        <span class="sort-tag">Mieux notés</span>
        <span class="sort-tag">Plus populaires</span>
    </div>

    <div class="offers-container">
        <div class="offers-grid">
            <?php
            $destinations = [
                [
                    'title' => 'Costa Rica - Éco-lodge dans la forêt tropicale',
                    'image' => 'https://images.unsplash.com/photo-1590089415225-401ed6f9db8e',
                    'rating' => 5,
                    'badges' => ['Éco-responsable', 'Biodiversité'],
                    'price_per_night' => 150,
                    'type' => 'nature',
                    'duration' => 'medium',
                    'country' => 'costa-rica'
                ],
                [
                    'title' => 'Islande - Aurores boréales & Énergies renouvelables',
                    'image' => 'https://images.unsplash.com/photo-1551373884-8a0750074df7',
                    'rating' => 4,
                    'badges' => ['Énergie verte', 'Nature'],
                    'price_per_night' => 200,
                    'type' => 'nature',
                    'duration' => 'medium',
                    'country' => 'islande'
                ],
                [
                    'title' => 'Japon - Ryokan traditionnel à Kyoto',
                    'image' => 'https://images.unsplash.com/photo-1624253321171-1be53e12f5f4',
                    'rating' => 4,
                    'badges' => ['Culture', 'Tradition'],
                    'price_per_night' => 180,
                    'type' => 'culture',
                    'duration' => 'short',
                    'country' => 'japon'
                ],
                [
                    'title' => 'Norvège - Fjords & Mobilité douce',
                    'image' => 'https://images.unsplash.com/photo-1516544820488-4a99efa70a58',
                    'rating' => 5,
                    'badges' => ['Zéro émission', 'Nature'],
                    'price_per_night' => 220,
                    'type' => 'nature',
                    'duration' => 'long',
                    'country' => 'norvege'
                ],
                [
                    'title' => 'Bali - Retraite bien-être & Agriculture locale',
                    'image' => 'https://images.unsplash.com/photo-1537953773345-d172ccf13cf1',
                    'rating' => 4,
                    'badges' => ['Bio', 'Bien-être'],
                    'price_per_night' => 130,
                    'type' => 'wellbeing',
                    'duration' => 'medium',
                    'country' => 'indonesie'
                ],
                [
                    'title' => 'Nouvelle-Zélande - Trek éco-responsable',
                    'image' => 'https://images.unsplash.com/photo-1578757671167-bdc99f7c0bab',
                    'rating' => 5,
                    'badges' => ['Aventure', 'Nature'],
                    'price_per_night' => 170,
                    'type' => 'nature',
                    'duration' => 'long',
                    'country' => 'nouvelle-zelande'
                ],
                [
                    'title' => 'Thaïlande - Sanctuaire d\'éléphants',
                    'image' => 'https://images.unsplash.com/photo-1585970480901-90d6bb2a48b5',
                    'rating' => 5,
                    'badges' => ['Protection animale', 'Nature'],
                    'price_per_night' => 140,
                    'type' => 'eco',
                    'duration' => 'short',
                    'country' => 'thailande'
                ],
                [
                    'title' => 'Suisse - Chalet solaire dans les Alpes',
                    'image' => 'https://images.unsplash.com/photo-1502784444187-359ac186c5bb',
                    'rating' => 4,
                    'badges' => ['Énergie solaire', 'Montagne'],
                    'price_per_night' => 250,
                    'type' => 'eco',
                    'duration' => 'medium',
                    'country' => 'suisse'
                ],
                [
                    'title' => 'Maldives - Villa sur pilotis écologique',
                    'image' => 'https://images.unsplash.com/photo-1573843981267-be1999ff37cd',
                    'rating' => 5,
                    'badges' => ['Luxe durable', 'Océan'],
                    'price_per_night' => 400,
                    'type' => 'wellbeing',
                    'duration' => 'medium',
                    'country' => 'maldives'
                ],
                [
                    'title' => 'Pérou - Communauté locale & Machu Picchu',
                    'image' => 'https://images.unsplash.com/photo-1587595431973-160d0d94add1',
                    'rating' => 4,
                    'badges' => ['Culture', 'Patrimoine'],
                    'price_per_night' => 160,
                    'type' => 'culture',
                    'duration' => 'long',
                    'country' => 'perou'
                ],
                [
                    'title' => 'Canada - Observation des aurores boréales',
                    'image' => 'https://images.unsplash.com/photo-1579033461380-adb47c3eb938',
                    'rating' => 5,
                    'badges' => ['Nature', 'Aventure'],
                    'price_per_night' => 280,
                    'type' => 'nature',
                    'duration' => 'short',
                    'country' => 'canada'
                ],
                [
                    'title' => 'Grèce - Retraite yoga à Santorin',
                    'image' => 'https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff',
                    'rating' => 4,
                    'badges' => ['Bien-être', 'Méditerranée'],
                    'price_per_night' => 190,
                    'type' => 'wellbeing',
                    'duration' => 'medium',
                    'country' => 'grece'
                ],
                [
                    'title' => 'Vietnam - Écolodge dans la baie d\'Halong',
                    'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592',
                    'rating' => 5,
                    'badges' => ['Éco-tourisme', 'Baie'],
                    'price_per_night' => 180,
                    'type' => 'nature',
                    'duration' => 'medium',
                    'country' => 'vietnam'
                ],
                [
                    'title' => 'Maroc - Riad solaire à Marrakech',
                    'image' => 'https://images.unsplash.com/photo-1548813831-7aa3ed5b0819',
                    'rating' => 4,
                    'badges' => ['Culture', 'Énergie solaire'],
                    'price_per_night' => 140,
                    'type' => 'culture',
                    'duration' => 'short',
                    'country' => 'maroc'
                ],
                [
                    'title' => 'Tanzanie - Safari écologique Serengeti',
                    'image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e',
                    'rating' => 5,
                    'badges' => ['Safari', 'Nature'],
                    'price_per_night' => 320,
                    'type' => 'nature',
                    'duration' => 'long',
                    'country' => 'tanzanie'
                ],
                [
                    'title' => 'Finlande - Igloo en verre aurores boréales',
                    'image' => 'https://images.unsplash.com/photo-1549221952-37f0135d4196',
                    'rating' => 5,
                    'badges' => ['Luxe', 'Nature'],
                    'price_per_night' => 450,
                    'type' => 'eco',
                    'duration' => 'short',
                    'country' => 'finlande'
                ],
                [
                    'title' => 'Cambodge - Temple d\'Angkor à vélo',
                    'image' => 'https://images.unsplash.com/photo-1540525080980-b97c4ab7bad5',
                    'rating' => 4,
                    'badges' => ['Culture', 'Vélo'],
                    'price_per_night' => 110,
                    'type' => 'culture',
                    'duration' => 'medium',
                    'country' => 'cambodge'
                ],
                [
                    'title' => 'Équateur - Écolodge Amazonie',
                    'image' => 'https://images.unsplash.com/photo-1629229359993-575d52b5c68f',
                    'rating' => 4,
                    'badges' => ['Nature', 'Biodiversité'],
                    'price_per_night' => 200,
                    'type' => 'nature',
                    'duration' => 'long',
                    'country' => 'equateur'
                ],
                [
                    'title' => 'Portugal - Ferme bio Alentejo',
                    'image' => 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e',
                    'rating' => 4,
                    'badges' => ['Bio', 'Agriculture'],
                    'price_per_night' => 95,
                    'type' => 'eco',
                    'duration' => 'medium',
                    'country' => 'portugal'
                ],
                [
                    'title' => 'Népal - Trek durable Annapurna',
                    'image' => 'https://images.unsplash.com/photo-1585669060258-2dc6a3976d09',
                    'rating' => 5,
                    'badges' => ['Trekking', 'Montagne'],
                    'price_per_night' => 140,
                    'type' => 'nature',
                    'duration' => 'long',
                    'country' => 'nepal'
                ]
            ];

            foreach ($destinations as $destination) : ?>
                <div class="offer" 
                     data-type="<?php echo $destination['type']; ?>"
                     data-price="<?php echo $destination['price_per_night']; ?>"
                     data-duration="<?php echo $destination['duration']; ?>"
                     data-rating="<?php echo $destination['rating']; ?>"
                     data-country="<?php echo $destination['country']; ?>">
                    <div class="offer-image">
                        <img src="<?php echo $destination['image']; ?>" alt="<?php echo $destination['title']; ?>">
                    </div>
                    <div class="offer-content">
                        <h2 class="offer-title"><?php echo $destination['title']; ?></h2>
                        <div class="eco-rating">
                            <?php for($i = 0; $i < $destination['rating']; $i++): ?>
                                <i class="fas fa-leaf"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="eco-badges">
                            <?php foreach($destination['badges'] as $badge): ?>
                                <span class="eco-badge"><?php echo $badge; ?></span>
                            <?php endforeach; ?>
                        </div>
                        <p class="price">À partir de <?php echo number_format($destination['price_per_night'], 2); ?>€ par nuit</p>
                        <button class="details-button">Voir les détails</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            const offers = document.querySelectorAll('.offer');
            const filterForm = document.querySelector('.filters-container');
            const sortTags = document.querySelectorAll('.sort-tag');
            let currentFilters = {
                type: 'all',
                maxPrice: 300,
                duration: 'all',
                country: 'all'
            };

            // Initialisation
            initializeFilters();

            // Gestionnaire d'événements pour les filtres
            document.querySelector('.apply-filters').addEventListener('click', function(e) {
                e.preventDefault();
                applyFilters();
            });

            // Gestionnaire pour réinitialiser
            document.querySelector('.reset-filters').addEventListener('click', function(e) {
                e.preventDefault();
                resetAllFilters();
            });

            // Gestionnaire pour le tri
            sortTags.forEach(tag => {
                tag.addEventListener('click', function() {
                    sortTags.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    applySort(this.textContent);
                });
            });

            // Fonction d'initialisation
            function initializeFilters() {
                document.getElementById('destination-type').value = 'all';
                document.getElementById('price-range').value = 300;
                document.getElementById('duration').value = 'all';
                document.getElementById('country').value = 'all';
                showAllOffers();
            }

            // Fonction pour appliquer les filtres
            function applyFilters() {
                const type = document.getElementById('destination-type').value;
                const maxPrice = parseInt(document.getElementById('price-range').value);
                const duration = document.getElementById('duration').value;
                const country = document.getElementById('country').value;

                currentFilters = { type, maxPrice, duration, country };

                offers.forEach(offer => {
                    const offerType = offer.dataset.type;
                    const offerPrice = parseInt(offer.dataset.price);
                    const offerDuration = offer.dataset.duration;
                    const offerCountry = offer.dataset.country;

                    const typeMatch = type === 'all' || offerType === type;
                    const priceMatch = offerPrice <= maxPrice;
                    const durationMatch = duration === 'all' || offerDuration === duration;
                    const countryMatch = country === 'all' || offerCountry === country;

                    if (typeMatch && priceMatch && durationMatch && countryMatch) {
                        showOffer(offer);
                    } else {
                        hideOffer(offer);
                    }
                });

                // Réappliquer le tri actuel
                const activeSort = document.querySelector('.sort-tag.active');
                if (activeSort) {
                    applySort(activeSort.textContent);
                }
            }

            // Fonction de tri améliorée
            function applySort(criteria) {
                const offersList = Array.from(offers);
                const container = document.querySelector('.offers-grid');

                offersList.sort((a, b) => {
                    if (a.style.display === 'none' || b.style.display === 'none') return 0;

                    switch(criteria) {
                        case 'Prix croissant':
                            return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                        case 'Prix décroissant':
                            return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                        case 'Mieux notés':
                            return parseInt(b.dataset.rating) - parseInt(a.dataset.rating);
                        case 'Plus populaires':
                            // Tri aléatoire pour la démo
                            return 0.5 - Math.random();
                        default:
                            return 0;
                    }
                });

                offersList.forEach(offer => container.appendChild(offer));
            }

            // Fonction de réinitialisation complète
            function resetAllFilters() {
                initializeFilters();
                currentFilters = {
                    type: 'all',
                    maxPrice: 300,
                    duration: 'all',
                    country: 'all'
                };
                
                sortTags.forEach(tag => tag.classList.remove('active'));
                sortTags[0].classList.add('active');
                
                showAllOffers();
            }

            // Fonctions utilitaires
            function showOffer(offer) {
                offer.style.display = 'flex';
                offer.style.animation = 'fadeIn 0.5s ease forwards';
            }

            function hideOffer(offer) {
                offer.style.display = 'none';
            }

            function showAllOffers() {
                offers.forEach(offer => showOffer(offer));
            }
        });

        // Styles d'animation
        const animationStyles = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = animationStyles;
        document.head.appendChild(styleSheet);
    </script>

    <footer>
        <p>&copy; 2025 Green Travel. Tous droits réservés.</p>
    </footer>
</body>
</html>
