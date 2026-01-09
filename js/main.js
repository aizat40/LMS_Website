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
                // Reset transform so the element no longer keeps translateY offset
                entry.target.style.transform = 'none';
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

const coursePreviewModal = document.getElementById('coursePreviewModal');
if (coursePreviewModal) {
    coursePreviewModal.addEventListener('show.bs.modal', function (event) {
        // Butang yang diklik
        const button = event.relatedTarget;
        
        // Ambil data daripada atribut data-course-*
        const title = button.getAttribute('data-course-title');
        const desc = button.getAttribute('data-course-description');
        const learn = button.getAttribute('data-course-learn').split(', ');
        const instructor = button.getAttribute('data-course-instructor');
        const duration = button.getAttribute('data-course-duration');
        const lessons = button.getAttribute('data-course-lessons');
        const level = button.getAttribute('data-course-level');

        // Kemas kini kandungan modal
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDesc').textContent = desc;
        document.getElementById('modalInstructor').textContent = instructor;
        document.getElementById('modalDuration').textContent = duration;
        document.getElementById('modalLessons').textContent = lessons;
        
        const levelBadge = document.getElementById('modalLevel');
        levelBadge.textContent = level;
        // Tukar warna badge ikut level (optional logic)
        levelBadge.className = `badge-level bg-${level.toLowerCase()} px-2 py-1`;

        // Jana senarai "What will learn"
        const learnList = document.getElementById('modalLearn');
        learnList.innerHTML = '';
        learn.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            li.className = 'mb-1';
            learnList.appendChild(li);
        });
    });
}

// Simple Upvote Logic
document.querySelectorAll('.upvote-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const countSpan = this.querySelector('.count');
        let count = parseInt(countSpan.innerText);
        
        if (this.classList.contains('active')) {
            count--;
            this.classList.remove('active');
        } else {
            count++;
            this.classList.add('active');
        }
        countSpan.innerText = count;
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil parameter daripada URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // 2. Hanya tunjukkan modal JIKA status adalah 'success'
    if (status === 'authorized') {
        const successModalElement = document.getElementById('successModal');
        
        // Pastikan element modal wujud sebelum panggil
        if (successModalElement) {
            const successModal = new bootstrap.Modal(successModalElement);
            successModal.show();

            // 3. Bersihkan URL (buang ?status=success) supaya modal tak muncul bila refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // 1. Select the logout modal element
    const logoutModalElement = document.getElementById('logout');

    // 2. Initialize the Bootstrap Modal object
    if (logoutModalElement) {
        const logoutModal = new bootstrap.Modal(logoutModalElement);

        // Optional: If you want to trigger it via a specific JS function 
        // instead of data-attributes, you can use:
        // logoutModal.show();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil parameter daripada URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('posting');

    // 2. Hanya tunjukkan modal JIKA status adalah 'success'
    if (status === 'success') {
        const successModalElement = document.getElementById('success');
        
        // Pastikan element modal wujud sebelum panggil
        if (successModalElement) {
            const successModal = new bootstrap.Modal(successModalElement);
            successModal.show();

            // 3. Bersihkan URL (buang ?status=success) supaya modal tak muncul bila refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil parameter daripada URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('msg');

    // 2. Hanya tunjukkan modal JIKA status adalah 'success'    
    if (status === 'success') {
        const successModalElement = document.getElementById('successpayment');
        
        // Pastikan element modal wujud sebelum panggil
        if (successModalElement) {
            const successModal = new bootstrap.Modal(successModalElement);
            successModal.show();

            // 3. Bersihkan URL (buang ?status=success) supaya modal tak muncul bila refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
});

