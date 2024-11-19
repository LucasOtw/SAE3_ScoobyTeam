const scrollAmount = 300;

function scrollcontentLeft() {
    const container = document.querySelector('.a-la-une');
    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
}

function scrollcontentRight() {
    const container = document.querySelector('.a-la-une');
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}