
<?php 

/**
 * Variables in use 
 * @var $term
 * 
 * 
 * 
 */
// Get the current taxonomy term ID on the 'reading-practice' taxonomy archive page


// Get the 'instructions' meta data for the current taxonomy
$instructions = get_term_meta( $term->term_id, 'instructions', true );

// Check if instructions meta data exists and render it
if ( ! empty( $instructions ) ) {
    echo $instructions ;
} else {
    echo '<p>No instructions available for this Practice Set.</p>';
}

?>
<!-- <div class="heading">
            <h2>Selective High School Placement Practice Test</h2>
            <h4>Reading</h4>
        </div>
        <div class="all-instructions">
            <h5>INSTRUCTIONS</h5>
            <p class="bold">Please read these instructions carefully.</p>
            <div class="instructions">
                <p class="lists">
                    You have&nbsp;<strong>40 minutes&nbsp;</strong>to
                    complete&nbsp;<strong>16 questions</strong> in this test.
                </p>
                <p class="lists">
                    For Questions 1-14, choose one correct answer to each question.
                </p>
                <p class="lists">
                    For Question 15, choose the six correct answers.
                </p>
                <p class="lists">
                    For Question 15, choose the six correct answers.
                </p>
                <p class="lists">
                    For Question 15, choose the six correct answers.
                </p>
                <p class="lists">
                    For Question 15, choose the six correct answers.
                </p>
                <p class="lists">
                    For Question 15, choose the six correct answers.
                </p>
                <p class="details">
                    Every reasonable effort has been made by the publisher to trace
                    copyright holders, but if any items requiring clearance have
                    unwittingly been included, the publisher will be pleased to make
                    amends at the earliest possible opportunity.
                </p>
            </div>
        </div>
        <div class="info-logo">
            <img
                src="https://insights2prodp44s3w.azureedge.net/uploads/se-practice/moduleassets/3bbebce2-aed1-ec11-bc3c-93f806bd891d/f1be541a-d347-ef11-8d66-95eddcf3d24e.png?v=1"
                alt="" />
        </div> -->