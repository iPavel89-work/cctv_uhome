const MODAL = {

    init() {
        this.currentModal = [];
        this.currentZIndex = 1000;
        this.overlay = document.querySelectorAll('.modal-overlay');
        this.openBtn = document.querySelectorAll('[data-modal-btn]');
        this.closeBtn = document.querySelectorAll('[data-modal-close]');
        this.bindEvents();
    },

    bindEvents() {
        this.openBtn.forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const modalId = event.currentTarget.getAttribute('data-modal-btn');
                this.open(modalId);
            });
        });

        this.closeBtn.forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const modalId = event.currentTarget.closest('.modal-overlay').getAttribute('data-modal');
                this.close(modalId);
            });
        });


        this.overlay.forEach((overlay) => {
            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) {
                    const modalId = event.currentTarget.getAttribute('data-modal');
                    this.close(modalId);
                }
            });
        });
    },

    open(modalId) {
        const modalOverlay = document.querySelector(`.modal-overlay[data-modal="${modalId}"]`);
        const modal = modalOverlay.querySelector('.modal');
        lockScroll();

        this.currentModal.push(modal);
        modalOverlay.classList.add('isShow');

        this.currentZIndex += 1;
        modalOverlay.style.zIndex = this.currentZIndex;

        setTimeout(() => {
            modal.classList.add('modal-show');
        }, 100);
    },

    close(modalId) {
        const modalOverlay = document.querySelector(`.modal-overlay[data-modal="${modalId}"]`);
        const modal = modalOverlay.querySelector('.modal');
        unlockScroll();

        modalOverlay.classList.remove('isShow');
        modal.classList.remove('modal-show');

        this.currentModal.pop();
    }
};

