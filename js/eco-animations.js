// Animation des badges éco
document.querySelectorAll('.eco-badge').forEach(badge => {
    badge.addEventListener('mouseover', () => {
        badge.style.transform = 'scale(1.1)';
        badge.style.transition = 'transform 0.3s ease';
    });
    badge.addEventListener('mouseout', () => {
        badge.style.transform = 'scale(1)';
    });
});

// Animation des statistiques
const observerOptions = {
    threshold: 0.5
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-stat');
        }
    });
}, observerOptions);

document.querySelectorAll('.stat-item').forEach(stat => {
    observer.observe(stat);
});

// Animation des offres au survol
document.querySelectorAll('.offer').forEach(offer => {
    offer.addEventListener('mouseover', () => {
        offer.style.transform = 'translateY(-5px)';
        offer.style.transition = 'transform 0.3s ease';
    });
    offer.addEventListener('mouseout', () => {
        offer.style.transform = 'translateY(0)';
    });
}); 