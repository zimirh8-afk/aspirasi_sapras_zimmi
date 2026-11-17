document.addEventListener('DOMContentLoaded', function () {

    const statCards = document.querySelectorAll('.stat-card');

    statCards.forEach(function (card) {

        card.addEventListener('mouseenter', function () {

            card.style.transform = 'translateY(-2px)';

            card.style.transition = '0.2s ease';

        });

        card.addEventListener('mouseleave', function () {

            card.style.transform = 'translateY(0)';

        });

    });

});