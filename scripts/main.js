
function toggleTheme() {
    if(document.documentElement.getAttribute('data-bs-theme') === 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'light')
    }
    else {
        document.documentElement.setAttribute('data-bs-theme', 'dark')
    }
}

function toggleMenu() {
    var size = getViewport();
    if(size === 'xs' || size === 'sm' || size === 'md') {
        const menuToggle = document.getElementById('navbarSupportedContent');
        if(menuToggle.classList.contains('collapse')) {
            menuToggle.classList.toggle('collapse');
        }
    }
}

function getViewport () {
    // https://stackoverflow.com/a/8876069
    const width = Math.max(
        document.documentElement.clientWidth,
        window.innerWidth || 0
    )
    if (width <= 576) return 'xs'
    if (width <= 768) return 'sm'
    if (width <= 992) return 'md'
    if (width <= 1200) return 'lg'
    if (width <= 1400) return 'xl'
    return 'xxl'
}
