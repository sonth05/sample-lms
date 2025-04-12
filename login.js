        // JavaScript for header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.remove('transparent');
                header.classList.add('scrolled');
            } else {
                header.classList.add('transparent');
                header.classList.remove('scrolled');
            }
        });

        // JavaScript for modal
        const loginBtn = document.getElementById('loginBtn');
        const modalOverlay = document.getElementById('modalOverlay');
        const loginModal = document.getElementById('loginModal');
        const closeModal = document.getElementById('closeModal');

        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modalOverlay.classList.add('show');
            loginModal.classList.add('show');
        });

        closeModal.addEventListener('click', function() {
            modalOverlay.classList.remove('show');
            loginModal.classList.remove('show');
        });

        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('show');
                loginModal.classList.remove('show');
            }
        });

document.addEventListener("DOMContentLoaded", function () {
    const header = document.querySelector(".header");

    // Khi tải trang, thêm lớp "transparent"
    header.classList.add("transparent");

    // Lắng nghe sự kiện cuộn
    window.addEventListener("scroll", function () {
        if (window.scrollY > 5) { // Giảm giá trị xuống 5
            header.classList.remove("transparent");
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
            header.classList.add("transparent");
        }
    });
});
