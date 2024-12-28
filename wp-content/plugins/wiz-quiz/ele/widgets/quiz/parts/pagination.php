<?php 

/**
 * Template for displaying the 'reading-practice' taxonomy archive page.
 * 
 * @package Wiz Quiz
 */
?>

<div class="wiz-container pagination">
    <section class="pagination-main">
        <h2 class="pagination-progress">Progress summary</h2>
        <!-- Pagination Category Here -->
        <div class="pagination-category">
            <div class="pagination-category-containar">
                <div
                    class="pagination-category-containar-child active show-all"
                    data-quetions="all">
                    <p class="pagination-category-num" id="active"><?php echo $post_count; ?></p>
                    <h4 class="pagination-category-name">Show All</h4>
                </div>
                <div
                    class="pagination-category-containar-child answer"
                    data-quetions="answered">
                    <p class="pagination-category-num">0</p>
                    <h4 class="pagination-category-name">Answered</h4>
                </div>
                <div
                    class="pagination-category-containar-child notAns"
                    data-quetions="not-answered">
                    <p class="pagination-category-num">0</p>
                    <h4 class="pagination-category-name">Not Answered</h4>
                </div>
                <div
                    class="pagination-category-containar-child not-read"
                    data-quetions="not-read">
                    <p class="pagination-category-num">0</p>
                    <h4 class="pagination-category-name">Not Read</h4>
                </div>
                <div
                    class="pagination-category-containar-child flagged"
                    data-quetions="flagged">
                    <p class="pagination-category-num">0</p>
                    <h4 class="pagination-category-name">Flagged</h4>
                </div>
            </div>
        </div>

        <div class="pagination-total-quetions">

            <div class="m-page all not-answered" data-que="i">
                <div class="overlay"></div>
                <h3 class="ind-page">i</h3>
            </div>
        <?php 
        $paged_numb = 1;
        foreach ($posts as $post) { 
            $post_id = get_the_ID();
            ?> 
               <div class="m-page all not-read" data-que="<?php echo $paged_numb; ?>">
                <div class="overlay"></div>
                <h3 class="ind-page"><?php echo $paged_numb ; ?></h3>
            </div>
            <?php 
            $paged_numb++;
        }
        
        ?> 
        </div>
        <div class="finsishbutton">
            <div class="finish wiz-btn"><a> Back </a></div>
        </div>
    </section>
</div>