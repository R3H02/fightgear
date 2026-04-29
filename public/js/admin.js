/**
 * FightGear — Admin JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {

    // ---- Sidebar toggle (mobile) ----
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar  = document.getElementById('adminSidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', () => {
            adminSidebar.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!adminSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                adminSidebar.classList.remove('open');
            }
        });
    }

    // ---- Price input formatting ----
    const priceInput = document.getElementById('preco');
    if (priceInput) {
        priceInput.addEventListener('blur', () => {
            const v = parseFloat(priceInput.value.replace(',', '.'));
            if (!isNaN(v)) priceInput.value = v.toFixed(2).replace('.', ',');
        });
    }
});
