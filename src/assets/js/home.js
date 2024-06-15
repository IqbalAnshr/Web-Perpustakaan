document.addEventListener('DOMContentLoaded', function () {

    const swiper = new Swiper(
        '.swiper', {
        autoplay: {
            delay: 3000,
        },
        spaceBetween: 30,
        loop: true,
        breakpoints: {
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
        }
        
    }

    )


    const toggler = document.querySelector('.navbar-toggler');

    // Mendeteksi perubahan posisi gulir halaman
    window.addEventListener('scroll', function () {
        // Jika posisi gulir lebih besar dari 200, tambahkan kelas fixed-top
        if (window.scrollY > 200) {
            document.getElementById('navbar_top').classList.add('fixed-top');
            toggler.classList.remove('navbar-dark');
            toggler.classList.add('navbar-light');

            // Menambahkan padding atas untuk menunjukkan konten di belakang navbar
            const navbarHeight = document.querySelector('.navbar').offsetHeight;
            document.body.style.paddingTop = navbarHeight + 'px';
        } else {
            // Jika posisi gulir kurang dari atau sama dengan 200, hapus kelas fixed-top
            document.getElementById('navbar_top').classList.remove('fixed-top');
            toggler.classList.remove('navbar-light');
            toggler.classList.add('navbar-dark');

            // Menghapus padding atas dari body
            document.body.style.paddingTop = '0';
        }
    });
});
