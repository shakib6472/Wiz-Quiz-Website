jQuery(document).ready(function ($) {
    console.log('Connected');

    function showLoader() {
        $('.preloader').css('display', 'flex')
    }
    function hideLoader() {
        setTimeout(() => {
            $('.preloader').css('display', 'none')
        }, 500);
    }
    $('.extract-heads .heads').click(function (e) {
        e.preventDefault();
        extract = $(this).data('extract');
        console.log('object ' + extract);
        $('.extract-heads .heads').removeClass('active');
        $(this).addClass('active');
        $('.extracts .bodys').removeClass('active');
        $('.extracts .bodys').removeClass('active');
        $('.extracts .bodys[data-extract=' + extract + ']').addClass('active');
    });

    $('.close-icon').click(function (e) {
        e.preventDefault();
        $('.close-icon').fadeOut(500);
        $('.open-icon').fadeIn(500);
        $('.slides .contents .left').animate({ width: '100%' }, 300)
        $('.slides .contents .middle').animate({ width: '0%' }, 300)
        $('.slides .contents .right').animate({ width: '0%' }, 300)
    });
    $('.open-icon').click(function (e) {
        e.preventDefault();
        $('.open-icon').fadeOut(500);
        $('.close-icon').fadeIn(500);
        $('.slides .contents .left').animate({ width: '50%' }, 300)
        $('.slides .contents .middle').animate({ width: '1%' }, 300)
        $('.slides .contents .right').animate({ width: '49%' }, 300)
    });
    $('.next-quiz').click(function (e) {
        showLoader();
        // Get the active slide
        let isActiveSlide = $('.slides.active');
        let maxSlide = 4; // Define the maximum slide number
        if (isActiveSlide.length > 0) {
            $('.information').hide();
            let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
            let nextSlide = currentSlide + 1; // Calculate the next slide number
            console.log('Active Slide Found. Slide Number: ' + currentSlide);
            console.log('Next Slide Number: ' + nextSlide);
            // Check if the next slide exceeds maxSlide
            if (nextSlide > maxSlide) {
                console.log('This is the last Slide');
            } else {
                // Remove active class from all slides and add it to the next slide
                $('.slides').removeClass('active');
                $('.slides[data-slide="' + nextSlide + '"]').addClass('active');
            }
        } else {
            console.log('Active Slide Not Found');
            $('.information').hide();
            // If no active slide is found, set the first slide as active
            $('.slides').removeClass('active');
            $('.slides[data-slide="1"]').addClass('active');
        }
        hideLoader()
    });
    $('.prev-quiz').click(function (e) {
        showLoader();
        // Get the active slide
        let isActiveSlide = $('.slides.active');
        let maxSlide = 4; // Define the maximum slide number
        if (isActiveSlide.length > 0) {
            let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
            let prevSlide = currentSlide - 1; // Calculate the next slide number
            console.log('Active Slide Found. Slide Number: ' + currentSlide);
            console.log('Next Slide Number: ' + prevSlide);
            // Check if the next slide exceeds maxSlide
            if (prevSlide < 1) {
                console.log('This is the First Slide');
                $('.slides').removeClass('active');
                $('.information').show();
            } else {
                $('.information').hide();
                // Remove active class from all slides and add it to the next slide
                $('.slides').removeClass('active');
                $('.slides[data-slide="' + prevSlide + '"]').addClass('active');
            }
        } else {
            console.log('Active Slide Not Found');
            $('.information').show();
            // If no active slide is found, set the first slide as active
            $('.slides').removeClass('active');
            console.log('This is Information Slide');

        }
        hideLoader();


    });




});