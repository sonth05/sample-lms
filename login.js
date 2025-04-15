// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const modal = document.getElementById('loginModal');
    
    // Get the button that opens the modal
    const loginBtn = document.getElementById('loginBtn');
    
    // Get the close button
    const closeBtn = document.querySelector('.close');
    
    // When the user clicks the login button, show the modal
    if (loginBtn) {
        loginBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior
            modal.style.display = 'flex';
        });
    }
    
    // When the user clicks the close button, hide the modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Add scroll event listener for header transparency effect
    const header = document.getElementById('header');
    
    if (header) {
        // Set initial state on page load
        if (window.scrollY > 5) {
            header.classList.remove('transparent');
            header.classList.add('scrolled');
        } else {
            header.classList.add('transparent');
            header.classList.remove('scrolled');
        }
        
        // Update on scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 5) {
                header.classList.remove('transparent');
                header.classList.add('scrolled');
            } else {
                header.classList.add('transparent');
                header.classList.remove('scrolled');
            }
        });
    }
});