document.addEventListener('DOMContentLoaded', () => {

    // FIX HEIGHT
    setVh();
    window.addEventListener('resize', setVh);

    // MODAL
    MODAL.init();


    const COPY_BUTTONS = document.querySelectorAll("[data-copy]");
    if (COPY_BUTTONS.length > 0) {
        COPY_BUTTONS.forEach(button => {
            button.addEventListener("click", () => {
                const text =
                    button.dataset.copy ||
                    document.querySelector(button.dataset.target)?.value ||
                    '';
                copyToClipboard(text);
            });
        });
    }

    const SIDEBAR_BUTTONS = document.querySelectorAll("[data-sidebar-toggle-btn]");
    if (SIDEBAR_BUTTONS.length > 0) {
        SIDEBAR_BUTTONS.forEach(button => {
            button.addEventListener("click", () => {
                const target = document.querySelector(`[data-sidebar-toggle="${button.dataset.sidebarToggleBtn}"]`);
                if(target) {
                    target.classList.toggle("isHide");
                }
            });
        });
    }




})