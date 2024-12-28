<?php
// Ensure this file is being included by a parent file
defined('ABSPATH') or die('No script kiddies please!');
?>
<!-- 
 Quiz Types
- Reading Test
- Mathematical Reasoning Test
- Thinking Skill Test
- Writting Test
 -->
<!-- Now Design for this 4 box. Each box will have a button to go the page. "View All" -->
<div class="wiz_container header_container">
    <h1>Student Test Attempts</h1>
    <p>View all test results</p>
</div>

<div class="main_page_container ">
    <div class="cards">
        <a href="<?php echo admin_url('admin.php?page=wiz-quiz-reading'); ?>" class="card">
            <div class="card-body">
                <h5 class="card-title">Reading Test</h5>
                <p class="card-text">View all Reading Test Results</p>
                <p class="link">View All</p>
            </div>
        </a>
        <a href="<?php echo admin_url('admin.php?page=wiz-quiz-mathematical'); ?>" class="card">
            <div class="card-body">
                <h5 class="card-title">Mathematical Reasoning Test</h5>
                <p class="card-text">View all Mathematical Reasoning Test Results</p>
                <p class="link">View All</p>
            </div>
        </a>
        <a href="<?php echo admin_url('admin.php?page=wiz-quiz-thinking'); ?>" class="card">
            <div class="card-body">
                <h5 class="card-title">Thinking Skill Test</h5>
                <p class="card-text">View all Thinking Skill Test Results</p>
                <p class="link">View All</p>
            </div>
        </a>
        <a href="<?php echo admin_url('admin.php?page=wiz-quiz-writtings'); ?>" class="card">
            <div class="card-body">
                <h5 class="card-title">Writing Test</h5>
                <p class="card-text">View all Writing Test Results</p>
                <p class="link">View All</p>
            </div>
        </a>
    </div>
</div>