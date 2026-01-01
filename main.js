function slowScroll(targetId, duration) {
    const target = document.querySelector(targetId);
    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - 70;
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const run = ease(timeElapsed, startPosition, distance, duration);
        window.scrollTo(0, run);
        if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    // Fungsi "Ease" untuk pergerakan yang lebih organik
    function ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animation);
}

// Cara guna: Tukar nilai '1500' (1.5 saat) untuk kelajuan yang anda mahu
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        slowScroll(this.getAttribute('href'), 10000); // <-- ADJUST AREA (ms)
    });
});

const cursorDot = document.querySelector(".cursor-dot");
const cursorOutline = document.querySelector(".cursor-outline");

window.addEventListener("mousemove", function (e) {
    const posX = e.clientX;
    const posY = e.clientY;

    // Gerakkan dot kecil serta-merta
    cursorDot.style.left = `${posX}px`;
    cursorDot.style.top = `${posY}px`;

    // Gerakkan outline dengan sedikit delay (smooth effect)
    cursorOutline.animate({
        left: `${posX}px`,
        top: `${posY}px`
    }, { duration: 500, fill: "forwards" });
});

// Kesan apabila hover pada link atau button
document.querySelectorAll('a, button').forEach(link => {
    link.addEventListener('mouseenter', () => {
        cursorOutline.classList.add('cursor-active');
    });
    link.addEventListener('mouseleave', () => {
        cursorOutline.classList.remove('cursor-active');
    });
});

const observerOptions = {
    threshold: 0.2 // Animasi bermula apabila 20% elemen kelihatan pada skrin
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Tambah class animasi dan jadikan opacity 1
            entry.target.classList.add('animate__fadeInUp');
            entry.target.style.opacity = '1';
            // Berhenti memerhati elemen ini setelah animasi bermula
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Mula memerhati semua elemen yang mempunyai class 'reveal'
document.querySelectorAll('.reveal').forEach((el) => {
    observer.observe(el);
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('courseSearch');
    const difficultySelect = document.getElementById('filterDifficulty');
    const lecturerSelect = document.getElementById('filterLecturer');
    const categoryBtns = document.querySelectorAll('.filter-btn');
    const courseCards = document.querySelectorAll('.course-item');
    const noResultsMsg = document.getElementById('noResults');

    let currentCategory = 'all';

    function filterCourses() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedDifficulty = difficultySelect.value;
        const selectedLecturer = lecturerSelect.value;
        let visibleCount = 0;

        courseCards.forEach(card => {
            const title = card.querySelector('.card-title').innerText.toLowerCase();
            const category = card.getAttribute('data-category');
            const difficulty = card.getAttribute('data-difficulty');
            const lecturer = card.getAttribute('data-lecturer');

            // Logik Padanan (Match)
            const matchesSearch = title.includes(searchTerm);
            const matchesCategory = currentCategory === 'all' || category === currentCategory;
            const matchesDifficulty = selectedDifficulty === 'all' || difficulty === selectedDifficulty;
            const matchesLecturer = selectedLecturer === 'all' || lecturer === selectedLecturer;

            if (matchesSearch && matchesCategory && matchesDifficulty && matchesLecturer) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });

        noResultsMsg.classList.toggle('d-none', visibleCount > 0);
    }

    // Event Listeners
    searchInput.addEventListener('input', filterCourses);
    difficultySelect.addEventListener('change', filterCourses);
    lecturerSelect.addEventListener('change', filterCourses);

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-filter');
            filterCourses();
        });
    });
});
