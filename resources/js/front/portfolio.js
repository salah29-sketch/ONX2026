function initPortfolioPage() {
    const cards = Array.from(document.querySelectorAll('#portfolioGrid .portfolio-card'));
    if (!cards.length) return;

    const filterButtons = document.querySelectorAll('.filter-btn');
    const loadMoreBtn = document.getElementById('loadMoreBtn');

    let currentFilter = 'all';
    let visibleCount = 6;

    // ─── helpers ─────────────────────────────────────────────────────────────────
    function getFilteredCards() {
        if (currentFilter === 'all') return cards;
        return cards.filter((card) => card.dataset.category == currentFilter);
    }

    function renderCards() {
        const filteredCards = getFilteredCards();
        cards.forEach((card) => card.classList.add('hidden'));
        filteredCards.forEach((card, index) => {
            if (index < visibleCount) card.classList.remove('hidden');
        });
        if (loadMoreBtn) {
            filteredCards.length > visibleCount
                ? loadMoreBtn.classList.remove('hidden')
                : loadMoreBtn.classList.add('hidden');
        }
    }

    function setActiveFilterButton(activeBtn) {
        filterButtons.forEach((btn) => {
            btn.classList.remove('bg-orange-500', 'text-black');
            btn.classList.add('border', 'border-white/15', 'bg-white/5', 'text-white');
        });
        activeBtn.classList.remove('border', 'border-white/15', 'bg-white/5', 'text-white');
        activeBtn.classList.add('bg-orange-500', 'text-black');
    }

    // ─── event listeners ─────────────────────────────────────────────────────────
    filterButtons.forEach((button) => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentFilter = this.dataset.filter;
            visibleCount  = 6;
            setActiveFilterButton(this);
            renderCards();
        });
    });

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            visibleCount += 3;
            renderCards();
        });
    }

    renderCards();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPortfolioPage);
} else {
    initPortfolioPage();
}
