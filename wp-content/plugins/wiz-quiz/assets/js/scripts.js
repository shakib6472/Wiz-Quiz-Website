jQuery(document).ready(function ($) {
    console.log('Connected 2.o0');

    function showLoader() {
        $('.preloader').css('display', 'flex')
    }
    function hideLoader() {
        setTimeout(() => {
            $('.preloader').css('display', 'none')
        }, 500);
    }

    function wiz_pagination_update(question) {
        $('.m-page[data-que="' + question + '"]').removeClass('not-read'); // Fixed the selector
        $('.m-page[data-que="' + question + '"]').removeClass('not-answered'); // Fixed the selector
        $('.m-page[data-que="' + question + '"]').addClass('answered'); // Fixed the selector
        console.log('question: ' + question);
    }
    function wiz_pagination_update_not_answred(question) {
        $('.m-page[data-que="' + question + '"]').removeClass('not-read'); // Fixed the selector
        $('.m-page[data-que="' + question + '"]').addClass('not-answered'); // Fixed the selector
        console.log('question: ' + question);
    }

    // Function to show a toast using Toastify.js
    function showToastPlugin(heading, message) {
        $.toast({
            heading: heading,
            text: message,
            showHideTransition: 'slide',
            icon: 'error',
            position: 'bottom-right',
            hideAfter: 5000,
            stack: 5,
            loaderBg: '#002664'
        });
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

    $('.flag-quiz').click(function (e) {
        e.preventDefault();
        let isActiveSlide = $('.slides.active');
        if (isActiveSlide.length > 0) {
            let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
            $('.m-page[data-que="' + currentSlide + '"]').addClass('flagged'); // Fixed the selector
        } else {
            $('.m-page[data-que="i"]').addClass('flagged'); // Fixed the selector

        }
    });
    $('.next-quiz').click(function (e) {
        showLoader();
        // Get the active slide
        let isActiveSlide = $('.slides.active');
        let maxSlide = 4; // Define the maximum slide number
        if (isActiveSlide.length > 0) {
            $('.information').hide();
            var radioInput = isActiveSlide.find('input[type="radio"]');
            // Check if the radio input is checked
            let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
            let nextSlide = currentSlide + 1; // Calculate the next slide number
            var checkedRadio = radioInput.filter(':checked');
            if (checkedRadio.length > 0) {
                console.log('Active Slide Found. Slide Number: ' + currentSlide);
                console.log('Next Slide Number: ' + nextSlide);
                $('.title-area .instruction').text('Question No. ' + nextSlide + ' of ' + maxSlide);
                wiz_pagination_update(currentSlide);
                wiz_pagination_update_not_answred(nextSlide)
                // Check if the next slide exceeds maxSlide
                if (nextSlide > maxSlide) {
                    console.log('This is the last Slide');
                } else {
                    // Remove active class from all slides and add it to the next slide
                    $('.slides').removeClass('active');
                    $('.slides[data-slide="' + nextSlide + '"]').addClass('active');
                }
            } else {
                wiz_pagination_update_not_answred(currentSlide)
                showToastPlugin('Error', 'Please Select a Answer First');
            }
            
        } else {
            $('.title-area .instruction').text('Question No. 1 of ' + maxSlide);
            console.log('Active Slide Not Found');
            $('.information').hide();
            // If no active slide is found, set the first slide as active
            $('.slides').removeClass('active');
            $('.slides[data-slide="1"]').addClass('active');
            wiz_pagination_update('i');

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
                $('.title-area .instruction').text('Instruction');
            } else {
                $('.information').hide();
                // Remove active class from all slides and add it to the next slide
                $('.slides').removeClass('active');
                $('.slides[data-slide="' + prevSlide + '"]').addClass('active'); 
                $('.title-area .instruction').text('Question No. ' + prevSlide + ' of ' + maxSlide);
            }
        } else {
            console.log('Active Slide Not Found');
            $('.information').show();
            $('.title-area .instruction').text('Instruction');
            // If no active slide is found, set the first slide as active
            $('.slides').removeClass('active');
            console.log('This is Information Slide');

        }
        hideLoader();


    });
    $('.pagination-page').click(function (e) {
        console.log('Pagination');
        let top = $('.wiz-container.pagination').css('top');
        console.log('Top: ' + top);
        if ('0px' == top) {
            $('.wiz-container.pagination').css('top', '-100%');
        } else {
            $('.wiz-container.pagination').css('top', '0%');
        }
    });

    $('.finsishbutton').click(function (e) {
        $('.wiz-container.pagination').css('top', '-100%');
    });

    $(".pagination-category-containar-child").click(function () {
        item = $(this).data('quetions');
        console.log('Item: ' + item);
        $(".pagination-category-containar-child").removeClass('active');
        $(this).addClass('active');
        $(".m-page").addClass('desable');
        console.log('All Desabled');
        $(".m-page." + item).removeClass('desable');
        console.log(".m-page." + item + 'Re enabled');


    });


});