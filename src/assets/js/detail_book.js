document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.swiper', {
        autoplay: {
            delay: 3000,
        },
        spaceBetween: 15,
        loop: true,
        breakpoints: {
            1440: {
                slidesPerView: 7
            },
            1024: {
                slidesPerView: 6
            },
            768: {
                slidesPerView: 4
            },
            640: {
                slidesPerView: 3
            },
            320: {
                slidesPerView: 2
            }
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});

