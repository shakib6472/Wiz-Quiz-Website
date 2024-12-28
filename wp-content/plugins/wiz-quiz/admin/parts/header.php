<?php 
/**
 * @package Wiz Quiz Admin
 * @subpackage Heading
 */

?>

<div class="header">
    <h1>Review the answer</h1>
</div>
<div class="info">
    <div class="info-item"><strong>User Name:</strong> <?php echo $results->user_name; ?></div>
    <div class="info-item"><strong>User ID:</strong> <?php echo $results->user_id; ?> </div>
    <div class="info-item"><strong>Device ID:</strong> <?php echo $results->device_id; ?></div>
    <div class="info-item"><strong>Quiz ID:</strong> <?php echo $results->quiz_id; ?></div>
    <div class="info-item"><strong>Practice Name:</strong> <?php echo $practice_name; ?></div>
    <div class="info-item"><strong>Date:</strong> <?php echo $results->date; ?></div>
    <div class="info-item"><strong>Time Spent:</strong> <?php echo $formatted_time; ?></div>
    <div class="info-item"><strong>Total Points:</strong> <?php echo $results->total_point; ?></div>
    <div class="info-item"> <strong>User Agent:</strong> <?php echo $results->user_agent; ?> </div>
</div>