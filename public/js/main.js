/**
 * FightGear — Main JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {

    // ---- Header scroll effect ----
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 40);
        }, { passive: true });
    }

    // ---- Mobile nav toggle ----
    const navToggle = document.getElementById('navToggle');
    const navLinks  = document.getElementById('navLinks');
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!navToggle.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('open');
            }
        });
    }

    // ---- Animate elements on scroll ----
    const animItems = document.querySelectorAll('[data-animate]');
    if (animItems.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animated');
                    }, i * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        animItems.forEach(el => observer.observe(el));
    }

    // ---- Image preview for file input ----
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
    fileInputs.forEach(input => {
        const previewId = input.getAttribute('data-preview');
        const preview   = document.getElementById(previewId);
        if (preview) {
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    preview.src = url;
                    preview.style.display = 'block';
                }
            });
        }
    });

    // ---- Category pills (product list page) ----
    const pills = document.querySelectorAll('.category-pill[data-cat]');
    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            const cat = pill.getAttribute('data-cat');
            const url = new URL(window.location.href);
            if (cat === '') {
                url.searchParams.delete('categoria');
            } else {
                url.searchParams.set('categoria', cat);
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    });

    // ---- Auto-dismiss alerts ----
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ---- Confirm delete modal ----
    const deleteButtons = document.querySelectorAll('[data-delete-id]');
    const modal = document.getElementById('deleteModal');
    const modalIdInput = document.getElementById('deleteIdInput');
    const modalCancelBtn = document.getElementById('modalCancel');

    if (modal) {
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-delete-id');
                if (modalIdInput) modalIdInput.value = id;
                modal.classList.add('open');
            });
        });
        if (modalCancelBtn) {
            modalCancelBtn.addEventListener('click', () => modal.classList.remove('open'));
        }
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('open');
        });
    }
});
