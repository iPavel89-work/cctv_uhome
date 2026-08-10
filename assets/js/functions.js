// FIX SCROLL
let savedScrollY = 0;

function setVh() {
    const BODY_HEIGHT = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${BODY_HEIGHT}px`);
}

function lockScroll() {
    if (document.body.classList.contains('scroll-locked')) return;
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.documentElement.style.setProperty(
        '--scrollbar-width',
        `${scrollbarWidth}px`
    );

    savedScrollY = window.scrollY;
    document.body.style.top = `-${savedScrollY}px`;
    document.body.classList.add('scroll-locked');
}


function unlockScroll() {
    if (!document.body.classList.contains('scroll-locked')) return;

    document.body.classList.remove('scroll-locked');
    document.body.style.top = '';

    document.documentElement.style.removeProperty('--scrollbar-width');
    window.scrollTo(0, savedScrollY);
}

async function copyToClipboard(text = '') {
    try {
        await navigator.clipboard.writeText(text);
        showToast('Скопировано', 'success');
    } catch (error) {
        showToast('Ошибка при копировании', 'error');
    }
}

function clearUrlParams(value = '') {
    const url = new URL(window.location.href);
    if (url.searchParams.has(value)) {
        url.searchParams.delete('value');
        window.history.replaceState({}, document.title, url.toString());
    }
}


function takeVideoScreenshot(id = 'video') {
    const video = document.getElementById(id);
    const filename = 'screenshot_' + new Date().getTime() + '.jpg';
    if (!video) return;

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataURL = canvas.toDataURL('image/jpeg');

    const link = document.createElement('a');
    link.download = filename;
    link.href = dataURL;
    link.click();
}




// TOAST
function showToast(text = '', status = 'info', time = 5000) {
    let TosatStyles = {
        "info": {
            MozTransition: "all 0.3s ease",
            WebkitTransition: "all 0.3s ease",
            alignItems: "center",
            backgroundColor: "var(--bg-color-base)",
            borderRadius: "var(--radius)",
            boxShadow: "var(--color-info-shadow) 6px 15px 25px",
            borderBottom: "2px solid var(--color-info)",
            color: "var(--color-info)",
            display: "flex",
            fontSize: "clamp(12px, 3vw, 14px)",
            height: "auto",
            justifyContent: "center",
            maxWidth: "400px",
            msTransition: "all 0.3s ease",
            padding: "8px 16px",
            textAlign: "center",
            transition: "all 0.3s ease",
            width: "fit-content",
        },
        "success": {
            MozTransition: "all 0.3s ease",
            WebkitTransition: "all 0.3s ease",
            alignItems: "center",
            backgroundColor: "var(--bg-color-base)",
            borderRadius: "var(--radius)",
            boxShadow: "var(--color-success-shadow) 6px 15px 25px",
            borderBottom: "2px solid var(--color-success)",
            color: "var(--color-success)",
            display: "flex",
            fontSize: "clamp(12px, 3vw, 14px)",
            height: "auto",
            justifyContent: "center",
            maxWidth: "400px",
            msTransition: "all 0.3s ease",
            padding: "8px 16px",
            textAlign: "center",
            transition: "all 0.3s ease",
            width: "fit-content",
        },
        "warning": {
            MozTransition: "all 0.3s ease",
            WebkitTransition: "all 0.3s ease",
            alignItems: "center",
            backgroundColor: "var(--bg-color-base)",
            borderRadius: "var(--radius)",
            boxShadow: "var(--color-warning-shadow) 6px 15px 25px",
            borderBottom: "2px solid var(--color-warning)",
            color: "var(--color-warning)",
            display: "flex",
            fontSize: "clamp(12px, 3vw, 14px)",
            height: "auto",
            justifyContent: "center",
            maxWidth: "400px",
            msTransition: "all 0.3s ease",
            padding: "8px 16px",
            textAlign: "center",
            transition: "all 0.3s ease",
            width: "fit-content",
        },
        "error": {
            MozTransition: "all 0.3s ease",
            WebkitTransition: "all 0.3s ease",
            alignItems: "center",
            backgroundColor: "var(--bg-color-base)",
            borderRadius: "var(--radius)",
            boxShadow: "var(--color-error-shadow) 6px 15px 25px",
            borderBottom: "2px solid var(--color-error)",
            color: "var(--color-error)",
            display: "flex",
            fontSize: "clamp(12px, 3vw, 14px)",
            height: "auto",
            justifyContent: "center",
            maxWidth: "400px",
            msTransition: "all 0.3s ease",
            padding: "8px 16px",
            textAlign: "center",
            transition: "all 0.3s ease",
            width: "fit-content",
        },
        "danger": {
            MozTransition: "all 0.3s ease",
            WebkitTransition: "all 0.3s ease",
            alignItems: "center",
            backgroundColor: "var(--bg-color-base)",
            borderRadius: "var(--radius)",
            boxShadow: "var(--color-danger-shadow) 6px 15px 25px",
            borderBottom: "2px solid var(--color-danger)",
            color: "var(--color-danger)",
            display: "flex",
            fontSize: "clamp(12px, 3vw, 14px)",
            height: "auto",
            justifyContent: "center",
            maxWidth: "400px",
            msTransition: "all 0.3s ease",
            padding: "8px 16px",
            textAlign: "center",
            transition: "all 0.3s ease",
            width: "fit-content",
        },
    };

    const toast = Toast.makeText(
        document.body,
        text,
        time
    );

    toast.setPosition(Toast.POSITION_TOP_CENTER);
    // toast.setDismissible(true, "var(--color-text)");
    toast.setStyle(TosatStyles[status]);
    toast.setAnimation(Toast.FADE, Toast.FADE);
    toast.show();
}
