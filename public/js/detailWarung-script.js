document.addEventListener('scroll',function(){
    const navbarDetailWarung = document.getElementById('navbar-detail-warung')
    const titleDetail = document.getElementById('titleDetail');
    const scrolled = window.scrollY;
    if(scrolled > 100){
        navbarDetailWarung.style.marginTop = "0";
        navbarDetailWarung.classList.add('backdrop-blur')
        titleDetail.classList.add('visible')
        titleDetail.classList.remove('invisible')
    } else if (scrolled < 100){
        navbarDetailWarung.style.marginTop = "2.5rem";
        navbarDetailWarung.classList.remove('backdrop-blur')
        titleDetail.classList.add('invisible')
        titleDetail.classList.remove('visible')
    }
})