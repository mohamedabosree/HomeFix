

let cartTotal = 0;
let cartItemsCount = 0;

document.addEventListener('DOMContentLoaded', () => {
    const HomeFixApp = {
        init() {
            this.themeManager();
            this.formValidator();
            this.navLinkHighlighter();
            this.dynamicInteractionHooks();
        },

        themeManager() {
            const toggleBtn = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;

            
            const savedTheme = localStorage.getItem('homefix-theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-bs-theme', savedTheme);
                this.updateIcon(savedTheme);
            } else {
                this.updateIcon(htmlElement.getAttribute('data-bs-theme'));
            }

            if (!toggleBtn) return;

            toggleBtn.addEventListener('click', (e) => {
                if (!toggleBtn.hasAttribute('onclick')) {
                    window.toggleDarkMode();
                }
            });
        },

        updateIcon(theme) {
            const themeIcon = document.getElementById('theme-icon');
            if (!themeIcon) return;
            
            if (theme === 'dark') {
                themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            } else {
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            }
        },

        formValidator() {
           
            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        },

        navLinkHighlighter() {
           
            const currentPath = window.location.pathname.split("/").pop();
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath || link.getAttribute('href') === `/${currentPath}`) {
                    link.classList.add('active');
                    link.setAttribute('aria-current', 'page');
                }
            });
        },

        dynamicInteractionHooks() {
            const bookingsToggle = document.getElementById('bookings-toggle');
            if (bookingsToggle) {
                bookingsToggle.addEventListener('click', (e) => {
                    if (window.location.pathname.includes('index.php')) {
                        e.preventDefault();
                        document.getElementById('my-bookings')?.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        }
    };

    
    HomeFixApp.init();
});


window.hoverButton = function(element) {
    if (!element) return;
    element.style.transform = "scale(1.05)";
    element.style.boxShadow = "0 8px 15px rgba(13, 110, 253, 0.3)";
};

window.normalButton = function(element) {
    if (!element) return;
    element.style.transform = "scale(1)";
    element.style.boxShadow = "0 4px 6px rgba(13, 110, 253, 0.2)";
};

window.changeBorder = function(element) {
    if (!element) return;
    element.style.border = "2px solid #0d6efd";
    setTimeout(() => { element.style.border = "none"; }, 1000);
};


window.toggleDarkMode = function() {
    const htmlTag = document.documentElement;
    const currentTheme = htmlTag.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    htmlTag.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('homefix-theme', newTheme);
    
    const icon = document.getElementById('theme-icon');
    if (icon) {
        if (newTheme === 'dark') {
            icon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        } else {
            icon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
        }
    }
};


window.addToCart = function(serviceName, price) {
    const cartItemsDiv = document.getElementById('cart-items');
    const cartCountSpan = document.getElementById('cart-count');
    const cartTotalSpan = document.getElementById('cart-total');

    if (!cartItemsDiv || !cartCountSpan || !cartTotalSpan) return;

    
    if (cartItemsCount === 0) {
        cartItemsDiv.innerHTML = '';
    }

  
    cartItemsDiv.innerHTML += `
        <div class="d-flex justify-content-between text-dark border-bottom pb-2 mb-2">
            <span class="fw-bold">${serviceName}</span>
            <span>${price} EGP</span>
        </div>
    `;

    
    cartItemsCount++;
    cartTotal += price;

   
    cartCountSpan.innerHTML = cartItemsCount;
    cartTotalSpan.innerHTML = cartTotal + ' EGP';
    
   
    const offcanvasElement = document.getElementById('cartOffcanvas');
    if (offcanvasElement && typeof bootstrap !== 'undefined') {
        let cartOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
        cartOffcanvas.show();
    }
};


window.showMessage = function(event) {
    event.preventDefault();
    alert("Subscription executed successfully. Check your email for verification.");
    
    const modalElement = document.getElementById('rewardsModal');
    if (modalElement && typeof bootstrap !== 'undefined') {
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }
};
