jQuery(document).ready(function ($) {
    

    function showToastPlugin(heading, message) {
        $.toast({
            heading: heading,
            text: message,
            showHideTransition: 'slide',
            icon: 'success',
            position: 'bottom-right',
            hideAfter: 5000,
            stack: 5,
            loaderBg: '#002664'
        });
    }
    
    
    $('.view_result').on('click', function () {
        var quizId = $(this).data('quiz_id');
        console.log(quizId);
        // redirect to the same link but just add quiz_id parametere
        window.location.href = window.location.href + '&quiz_id=' + quizId;
    });

    $('.view_result').on('click', function () {
        var quizId = $(this).data('edit_result');
        console.log(quizId);
    });

    $(".btn-update_result").on("click", function () { 
        var score = $("#score").val();
        var quiz_id = $(this).data('quiz_id');
        var question_id = $(this).data('que');
        var updated_answer = quill.root.innerHTML;
        var comment = $("#comment").val();
        $(this).text("Updating...");
        console.log(score);
        console.log(quiz_id);
        console.log(question_id);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_wittings_result',
                score: score,
                quiz_id: quiz_id,
                question_id: question_id,
                updated_answer: updated_answer,
                comment: comment
            },
            success: function (response) {
                console.log(response);
                if (response.data == "success") {
                    $(".btn-update_result").text("Updated");
                    showToastPlugin("Success", "Everything Updated Successfully");
                    setTimeout(function () {
                        $(".btn-update_result").text("Update");
                    }, 2000);
                }
            }
        });
    });

    $(".update_mcq_answer").on("click", function () {  
        var quiz_id = $(this).data('quiz_id');
        var question_id = $(this).data('que');
        var updated_answer = $('input[name="q'+question_id+'-ans"]:checked').val();
         
        $(this).text("Updating..."); 
        console.log(quiz_id);
        console.log(question_id);
        console.log(updated_answer);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_mcq_result', 
                quiz_id: quiz_id,
                question_id: question_id,
                updated_answer: updated_answer
            },
            success: function (response) {
                console.log(response);
                if (response.data == "success") {
                    $(".update_mcq_answer").text("Updated");
                    showToastPlugin("Success", "Updated Successfully");
                    setTimeout(function () {
                        $(".update_mcq_answer").text("Update");
                    }, 2000);
                }
            }
        });
    });





});