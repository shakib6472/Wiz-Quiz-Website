jQuery(document).ready(function ($) {
console.log('init');

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
        var updated_answer = $('input[name="q' + question_id + '-ans"]:checked').val();

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

    $(".update_multiple_answer").on("click", function () {

        var quiz_id = $(this).data('quiz_id');
        var question_id = $(this).data('que');
        var parentofmltpls = $(this).parent().parent().parent();
        const answers = [];
        var select_answers = parentofmltpls.find('select');
        select_answers.each(function (e) {
            var dropdownid = $(this).data('index');
            var selected = $(this).val();
            answers.push(
                {
                    'dropdown_id': dropdownid,
                    'answer': selected
                })
        });


        $(this).text("Updating...");
        console.log(quiz_id);
        console.log(question_id);
        console.log(answers);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_multiple_result',
                quiz_id: quiz_id,
                question_id: question_id,
                updated_answer: answers
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

    $(".update_drag_answer").on("click", function () {

        var quiz_id = $(this).data('quiz_id');
        var question_id = $(this).data('que');
        var parentofmltpls = $(this).parent().parent().parent();
        const results = [];
        var dropAnswerboxes = parentofmltpls.find('.answer-box');
        dropAnswerboxes.each(function () {
            const boxId = $(this).data("que-id");
            const option = $(this).find(".drag_option").text() || "Empty";
            results.push({ boxId, option });
        });


        $(this).text("Updating...");
        console.log(quiz_id);
        console.log(question_id);
        console.log(results);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_drag_result',
                quiz_id: quiz_id,
                question_id: question_id,
                updated_answer: results
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

   // Common function to make elements draggable
   function makeDraggable($elements) {
    $elements.draggable({
        revert: "invalid",
        stack: ".drag_option",
        cursor: "move",
        containment: "window" // Use window for broader dragging
    });
}

// Initialize draggable options
makeDraggable($(".drag_option"));

// Make answer boxes droppable
$(".answer-box").droppable({
    accept: ".drag_option",
    drop: function (event, ui) {
        const $box = $(this);
        const $option = $(ui.draggable);

        // If the box is already filled, move existing option back
        if ($box.hasClass("filled")) {
            const $existingOption = $box.find(".drag_option").detach();
            $("#options").append($existingOption);
            $existingOption.css({ top: "0px", left: "0px" });
            makeDraggable($existingOption);
        }

        // Add the new option
        $box.empty().append($option).addClass("filled");
        $option.css({ top: "0px", left: "0px" });
    }
});
 
// Make the options pool droppable
$("#options").droppable({
    accept: ".drag_option",
    drop: function (event, ui) {
        const $option = $(ui.draggable);
        const $box = $option.closest(".answer-box");

        // Reset answer box if applicable
        if ($box.length) {
            $box.removeClass("filled").html('<span class="placeholder">Drop here</span>');
        }

        // Move option back to pool
        const $clonedOption = $option.clone().css({ top: "0px", left: "0px" });
        $(this).append($clonedOption);
        $option.remove(); // Remove the original to prevent duplicates

        makeDraggable($clonedOption);
    }
});


});