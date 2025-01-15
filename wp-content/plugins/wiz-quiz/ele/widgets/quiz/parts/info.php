
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
error_log(print_r($instructions,true));
// Check if instructions meta data exists and render it
if ( ! empty( $instructions ) ) {
    echo $instructions ;
} else {
    echo '<p>No instructions available for this Practice Set.</p>';
}

?>
 