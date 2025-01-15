jQuery(document).ready(function ($) {
    console.log('Connected 3.0');
    // console.log(wizQuizData);
    setInterval(function () {
        $('grammarly-desktop-integration').remove();
        $('[data-grammarly-shadow-root]').remove();
        $('grammarly-extension').remove();

        // Disable Grammarly on all text inputs
        $('textarea, input[type="text"], input[type="search"]').attr('data-grammarly', 'false');
    }, 2000);

    // Desable Right Click
    $(document).on('contextmenu', function (e) {
        showToastPlugin('Sorry', 'Right Click is Disabled');
        e.preventDefault(); // Prevent the default right-click behavior
    });

    // Check if the cookie 'user_gmt_offset' exists
    if (document.cookie.indexOf('user_gmt_offset=') === -1) { 
        const offsetMinutes = new Date().getTimezoneOffset(); 
        const offsetHours = -offsetMinutes / 60; 
        const gmtOffset = `GMT${offsetHours >= 0 ? '+' : ''}${offsetHours}`; 
        document.cookie = `user_gmt_offset=${encodeURIComponent(gmtOffset)}; path=/;`;
        
    } 
    function showLoader() {
        $('.preloader').css('display', 'flex')
    }
    function hideLoader() {
        setTimeout(() => {
            $('.preloader').css('display', 'none')
        }, 500);
    }

    function wiz_update_counting() {
        $('.pagination-category .answer p').text($('.m-page.answered').length);
        $('.pagination-category .notAns p').text($('.m-page.not-answered').length);
        $('.pagination-category .not-read p').text($('.m-page.not-read').length);
        $('.pagination-category .flagged p').text($('.m-page.flagged').length);
    }

    function updateFlagging(question) {
        console.log('Update Flagging Called for Question: ' + question);
        var is_flagged = $('.m-page[data-que="' + question + '"]').hasClass('flagged');
        if (is_flagged) {
            console.log('Question is Flagged');
            $('.btn-flag span').text('De-Flag');
            $('.btn-flag').addClass('deflag');
        } else {
            console.log('Question is Not Flagged');
            $('.btn-flag span').text('Flag');
            $('.btn-flag').removeClass('deflag');
        }
    }

    function wiz_pagination_update(question) {
        $('.m-page[data-que="' + question + '"]').removeClass('not-read');
        $('.m-page[data-que="' + question + '"]').removeClass('not-answered');
        $('.m-page[data-que="' + question + '"]').addClass('answered');
        wiz_update_counting();
        updateFlagging(question)
    }
    function wiz_pagination_update_not_answred(question) {
        var is_answered = $('.m-page[data-que="' + question + '"]').hasClass('answered');
        if (!is_answered) {
            $('.m-page[data-que="' + question + '"]').removeClass('not-read');
            $('.m-page[data-que="' + question + '"]').addClass('not-answered');
            wiz_update_counting();
        }
        updateFlagging(question)
    }

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

    function wiz_moving_sliders(nextSlide, maxSlide) {
        console.log('Next Slide: ' + nextSlide);
        console.log('maxSlide : ' + maxSlide);
        if (nextSlide > maxSlide) {
            // redirect to Result Page 
            $('.wiz_modal').css('display', 'flex');
            hideLoader();
        } else {
            if (nextSlide == maxSlide) {
                $('.next-quiz').text('Submit');
                $('.next-quiz').addClass('sub-quiz').removeClass('next-quiz');
            }
            // Remove active class from all slides and add it to the next slide
            $('.title-area .instruction').text('Question No. ' + nextSlide + ' of ' + maxSlide);
            hideLoader();
            $('.slides').removeClass('active');
            $('.slides[data-slide="' + nextSlide + '"]').addClass('active');
            console.log('Activating Slide:' + '.slides[data-slide="' + nextSlide + '"]');
        }
    }

    function wiz_proccess_mcq_question(isActiveSlide) {
        var radioInput = isActiveSlide.find('input[type="radio"]');
        // Check if the radio input is checked
        let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
        let question_id = isActiveSlide.data('que'); // Get the current slide number
        let nextSlide = currentSlide + 1; // Calculate the next slide number
        let maxSlide = wizQuizData.post_count; // Define the maximum slide number
        var checkedRadio = radioInput.filter(':checked');
        var answer = checkedRadio.val();
        if (checkedRadio.length > 0) {
            wiz_pagination_update(currentSlide);
            wiz_pagination_update_not_answred(nextSlide);
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url, // WordPress AJAX URL provided via wp_localize_script
                data: {
                    action: 'wiz_update_result_database_mcq', // Action hook to handle the AJAX request in your functions.php
                    type: 'MCQ',
                    answer: answer,
                    question_id: question_id,
                    wizQuizData: wizQuizData
                },
                dataType: 'json',
                success: function (response) {
                    wiz_moving_sliders(nextSlide, maxSlide);

                },
                error: function (xhr, textStatus, errorThrown) {
                    // Handle error 
                    if (confirm('Something went wrong. Would you like to start again?')) {
                        window.location.href = ajax_object.home_url;
                    }
                }
            });
        } else {
            wiz_pagination_update_not_answred(currentSlide);
            wiz_moving_sliders(nextSlide, maxSlide);
            // showToastPlugin('Error', 'Please Select a Answer First');
            hideLoader();
        }
    }

    function wiz_proccess_writting_question(isActiveSlide) {
        let currentSlide = isActiveSlide.data('slide');
        let question_id = isActiveSlide.data('que');
        let nextSlide = currentSlide + 1;
        let maxSlide = wizQuizData.post_count;
        var answer = quill.root.innerHTML; // Get the text from the Quill editor
        var isBlank = isActiveSlide.find('.ql-editor').hasClass('ql-blank');
        if (isBlank) {
            wiz_pagination_update_not_answred(currentSlide)
            wiz_moving_sliders(nextSlide, maxSlide);
            // showToastPlugin('Blank', 'You Must need to put something in writting');
            hideLoader();
        } else {
            wiz_pagination_update(currentSlide);
            wiz_pagination_update_not_answred(nextSlide)
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url, // WordPress AJAX URL provided via wp_localize_script
                data: {
                    action: 'wiz_update_result_database_writting', // Action hook to handle the AJAX request in your functions.php
                    type: 'Writtings',
                    answer: answer,
                    question_id: question_id,
                    wizQuizData: wizQuizData
                },
                dataType: 'json',
                success: function (response) {
                    wiz_moving_sliders(nextSlide, maxSlide);

                },
                error: function (xhr, textStatus, errorThrown) {
                    // Handle error 
                    if (confirm('Something went wrong. Would you like to start again?')) {
                        window.location.href = ajax_object.home_url;
                    }
                }
            });

            hideLoader();
        }
    }

    function wiz_proccess_drag_question(isActiveSlide) {
        let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
        let question_id = isActiveSlide.data('que'); // Get the current slide number
        let nextSlide = currentSlide + 1; // Calculate the next slide number
        let maxSlide = wizQuizData.post_count; // Define the maximum slide number

        const results = [];
        var dropAnswerboxes = isActiveSlide.find('.answer-box');
        dropAnswerboxes.each(function () {
            const boxId = $(this).data("que-id");
            const option = $(this).find(".drag_option").text() || "Empty";
            results.push({ boxId, option });
        });

        if (!results.some(result => result.option === "Empty")) {
            wiz_pagination_update(currentSlide);
            wiz_pagination_update_not_answred(nextSlide)
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url, // WordPress AJAX URL provided via wp_localize_script
                data: {
                    action: 'wiz_update_result_database_drag', // Action hook to handle the AJAX request in your functions.php
                    type: 'MCQ',
                    results: results,
                    question_id: question_id,
                    wizQuizData: wizQuizData
                },
                dataType: 'json',
                success: function (response) {
                    wiz_moving_sliders(nextSlide, maxSlide);
                },
                error: function (xhr, textStatus, errorThrown) {
                    // Handle error
                    // alert('Something Went wrong, Please start again');
                    if (confirm('Something went wrong. Would you like to start again?')) {
                        window.location.href = ajax_object.home_url;
                    }
                }
            });
        } else {
            //if any option empty, then 
            wiz_moving_sliders(nextSlide, maxSlide);
            wiz_pagination_update_not_answred(currentSlide)
            // showToastPlugin('Error', 'Please drop all answer first');
            hideLoader();
        }
        hideLoader();
    }

    function wiz_proccess_multiple_question(isActiveSlide) {
        let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
        let question_id = isActiveSlide.data('que'); // Get the current slide number
        let nextSlide = currentSlide + 1; // Calculate the next slide number
        let maxSlide = wizQuizData.post_count; // Define the maximum slide number
        const answers = [];
        var select_answers = isActiveSlide.find('select');
        select_answers.each(function (e) {
            var dropdownid = $(this).data('index');
            var selected = $(this).val();
            answers.push(
                {
                    'dropdown_id': dropdownid,
                    'answer': selected
                })
        });
        if (!answers.some(answers => answers.answer === null)) {
            wiz_pagination_update(currentSlide);
            wiz_pagination_update_not_answred(nextSlide)
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url, // WordPress AJAX URL provided via wp_localize_script
                data: {
                    action: 'wiz_update_result_database_multiple', // Action hook to handle the AJAX request in your functions.php
                    type: 'Multiple',
                    answers: answers,
                    question_id: question_id,
                    wizQuizData: wizQuizData
                },
                dataType: 'json',
                success: function (response) {
                    wiz_moving_sliders(nextSlide, maxSlide);

                },
                error: function (xhr, textStatus, errorThrown) {
                    // Handle error 
                    if (confirm('Something went wrong. Would you like to start again?')) {
                        window.location.href = ajax_object.home_url;
                    }
                }
            });
        } else {
            wiz_moving_sliders(nextSlide, maxSlide);
            wiz_pagination_update_not_answred(currentSlide)
            // showToastPlugin('Error', 'Please Select all answer first');
            hideLoader();
        }
    }

    $('.extract-heads .heads').click(function (e) {
        e.preventDefault();
        extract = $(this).data('extract');
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

    $('.btn-flag').click(function (e) {
        e.preventDefault();
        var is_not_flagged = $(this).hasClass('flag-quiz');
        if (is_not_flagged) {
            let isActiveSlide = $('.slides.active');
            if (isActiveSlide.length > 0) {
                let currentSlide = isActiveSlide.data('slide');
                $('.m-page[data-que="' + currentSlide + '"]').addClass('flagged'); // Fixed the selector 
            } else {

                $('.m-page[data-que="i"]').addClass('flagged'); // Fixed the selector

            }
            $(this).addClass('deflag');
            $(this).find('span').text('De-Flag');
            $(this).removeClass('flag-quiz');
            wiz_update_counting();
        } else {
            var alreadyflagged = $(this).hasClass('deflag');
            let isActiveSlide = $('.slides.active');
            if (isActiveSlide.length > 0) {
                let currentSlide = isActiveSlide.data('slide');
                $('.m-page[data-que="' + currentSlide + '"]').removeClass('flagged'); // Fixed the selector 
            } else {
                $('.m-page[data-que="i"]').removeClass('flagged'); // Fixed the selector

            }
            if (!alreadyflagged) {
                $(this).addClass('deflag');
                $(this).removeClass('flag-quiz');
                $(this).find('span').text('De-Flag');
            } else {
                $(this).removeClass('deflag');
                $(this).addClass('flag-quiz');
                $(this).find('span').text('Flag');
            }
            wiz_update_counting();

        }

    });

    $('.next-quiz').click(function (e) {
        showLoader();
        // Get the active slide
        let isActiveSlide = $('.slides.active');
        let maxSlide = wizQuizData.post_count; // Define the maximum slide number
        if (isActiveSlide.length > 0) {
            $('.information').hide();
            var questionType = isActiveSlide.data('que-type');
            if ('MCQ' === questionType) {
                wiz_proccess_mcq_question(isActiveSlide);
            } else if ('Writings' === questionType) {
                wiz_proccess_writting_question(isActiveSlide);
            } else if ('Drag & Drop' === questionType) {
                wiz_proccess_drag_question(isActiveSlide);
            } else if ('Multiple Drop Down' === questionType) {
                wiz_proccess_multiple_question(isActiveSlide);
            }
        } else {
            $('.title-area .instruction').text('Question No. 1 of ' + maxSlide);
            if (1 == maxSlide) {
                $('.next-quiz').text('Submit');
                $('.next-quiz').addClass('sub-quiz').removeClass('next-quiz');
            }
            $('.information').hide();
            // If no active slide is found, set the first slide as active
            $('.slides').removeClass('active');
            $('.slides[data-slide="1"]').addClass('active');
            wiz_pagination_update('i');
            wiz_pagination_update_not_answred(1);
            hideLoader();
        }
    });

    $('.next-quiz-non-update').click(function (e) {
        console.log('Next Quiz Non Update Clicked');
        showLoader();
        // Get the active slide
        let isActiveSlide = $('.slides.active');
        // Check if the radio input is checked
        let currentSlide = isActiveSlide.data('slide'); // Get the current slide number 
        let nextSlide = currentSlide + 1; // Calculate the next slide number
        let maxSlide = wizQuizData.post_count;
        if (isActiveSlide.length > 0) {
            $('.information').hide();
            var questionType = isActiveSlide.data('que-type');
            wiz_moving_sliders(nextSlide, maxSlide);
        } else {
            $('.title-area .instruction').text('Question No. 1 of ' + maxSlide);
            if (1 == maxSlide) {
                $('.next-quiz').text('Submit');
                $('.next-quiz').addClass('sub-quiz').removeClass('next-quiz');
            }
            $('.information').hide();
            $('.slides').removeClass('active');
            // $('.slides[data-slide="1"]').addClass('active');
            // wiz_pagination_update('i');
            // wiz_pagination_update_not_answred(1);
            hideLoader();
        }
    });

    $('.prev-quiz').click(function (e) {
        showLoader();
        // Get the active slide
        let isActiveSlide = $('.slides.active');
        let maxSlide = wizQuizData.post_count; // Define the maximum slide number
        if (isActiveSlide.length > 0) {
            let currentSlide = isActiveSlide.data('slide'); // Get the current slide number
            let prevSlide = currentSlide - 1;
            // Check if the next slide exceeds maxSlide
            if (prevSlide < 1) {
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
            $('.information').show();
            $('.title-area .instruction').text('Instruction');
            // If no active slide is found, set the first slide as active
            $('.slides').removeClass('active');
        }
        hideLoader();


    });

    $('.pagination-page').click(function (e) {
        let top = $('.wiz-container.pagination').css('top');
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
        $(".pagination-category-containar-child").removeClass('active');
        $(this).addClass('active');
        $(".m-page").addClass('desable');
        $(".m-page." + item).removeClass('desable');


    });
    // Pagination Page, Question Number click
    $('.m-page').click(function (e) {
        desabled = $(this).hasClass('desable');
        let maxSlide = wizQuizData.post_count; // Define the maximum slide number
        slide = $(this).data('que');
        if (!desabled) {
            if ('i' == slide) {
                $('.title-area .instruction').text('Instructions');
                $('.slides').removeClass('active');
                $('.wiz-container.pagination').css('top', '-100%');
                $('.information').show();
                updateFlagging(slide)
            } else {
                $('.information').hide();
                $('.title-area .instruction').text('Question No. ' + slide + ' of ' + maxSlide);
                $('.slides').removeClass('active');
                $('.slides[data-slide="' + slide + '"]').addClass('active');
                $('.wiz-container.pagination').css('top', '-100%');
                wiz_pagination_update_not_answred(slide);
                console.log('SLide Actiavted');
            }
        }
    });

    $('.wiz_container .header .zoom i').click(function (e) {
        e.preventDefault();
        console.log('Header Zoom Clicked');
        $('.wiz_container .header .zoom .zoom-options').toggle();
    });

    $('.wiz_container .header .zoom .option').click(function (e) {
        e.preventDefault();
        var percent = $(this).data('per');
        var active_per = $('.wiz_container .header .zoom .option.active').data('per');
        var scale = percent / 100;
        $('.wiz_container main *').each(function () {
            var currentFontSize = parseFloat($(this).css('font-size'));
            var originalFontSize = (currentFontSize * 100) / active_per;
            var newFontSize = originalFontSize * scale;
            $(this).css('font-size', newFontSize + 'px');
        });
        $('.wiz_container .header .zoom .option').removeClass('active');
        $(this).addClass('active');
        $('.wiz_container .header .zoom .zoom-options').hide();
    });

    // Drag & Drop
    $(".drag_option").draggable({
        revert: "invalid",
        stack: ".option",
        cursor: "move",
        containment: "body",
    });

    // Make answer boxes droppable
    $(".answer-box").droppable({
        accept: ".drag_option",
        drop: function (event, ui) {
            const $box = $(this);
            const $option = $(ui.draggable);

            if ($box.hasClass("filled")) {
                const $existingOption = $box.find(".drag_option");
                $("#options").append($existingOption);
                $existingOption.css({ top: "0px", left: "0px" });
            }

            $box.empty().append($option).addClass("filled");
            $option.css({ top: "0px", left: "0px" });
        },
    });

    // Options pool droppable
    $("#options").droppable({
        accept: ".drag_option",
        drop: function (event, ui) {
            const $option = $(ui.draggable);
            const $box = $option.closest(".answer-box");
            if ($box.length) {
                $box
                    .removeClass("filled")
                    .html('<span class="placeholder">Drop here</span>');
            }
            $(this).append($option);
            $option.css({ top: "0px", left: "0px" });
        },
    });

    $('.set-cockies').click(function (e) {
        e.preventDefault();
        var user_name = $('.name-box input').val();
        if (user_name) {
            document.cookie = "user_name=" + encodeURIComponent(user_name) + "; path=/; max-age=" + 30 * 24 * 60 * 60;
            $('.wiz-popup').fadeOut(500);
            $('.wiz-tax-hidden').fadeIn(500);
        } else {
            alert('Put Your Name Please');
        }
    });
    $('.view_results_on_page').click(function (e) {
        e.preventDefault();
        window.location = ajax_object.result_url + '?quiz_id=' + wizQuizData.quiz_id + '&term=' + wizQuizData.term_id;
    });

    $('.close_modal').click(function (e) {
        $('.wiz_modal').css('display', 'none');
    }); 
});





