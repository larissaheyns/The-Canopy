(function () {
    var cards = document.querySelectorAll('.landing-card');

    function measure(card) {
        var text = card.querySelector('.dream-text');
        var btn = card.querySelector('.show-more-btn');
        if (!text || !btn) return;

        if (btn.getAttribute('aria-expanded') === 'true') return;

        text.classList.add('is-clamped');
        var overflowing = text.scrollHeight - text.clientHeight > 1;
        btn.hidden = !overflowing;
        if (!overflowing) text.classList.remove('is-clamped');
    }

    cards.forEach(function (card) {
        var text = card.querySelector('.dream-text');
        var btn = card.querySelector('.show-more-btn');
        if (!text || !btn) return;

        btn.addEventListener('click', function () {
            var expanding = btn.getAttribute('aria-expanded') !== 'true';
            text.classList.toggle('is-clamped', !expanding);
            btn.setAttribute('aria-expanded', expanding ? 'true' : 'false');
            btn.textContent = expanding ? 'Show less' : 'Show more';
        });

        measure(card);
    });

    function measureAll() { cards.forEach(measure); }

    if (document.fonts && document.fonts.ready) document.fonts.ready.then(measureAll);
    window.addEventListener('load', measureAll);

    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(measureAll, 150);
    });
})();
