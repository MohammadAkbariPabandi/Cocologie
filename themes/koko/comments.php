<?php
echo '<h2 class="base mb-5">بازخورد افرادی که قبلا در این بازی شرکت کرده اند</h2>';
// Get only approved comments for the current post
$args = array(
    'post_id' => get_the_ID(), // Current post
    'status'  => 'approve',    // Only approved comments
    'order'   => 'ASC'         // Oldest first (use 'DESC' for newest first)
);

$comments = get_comments($args);

if ($comments) {
    $comment_count = 0; // Initialize counter
    foreach ($comments as $comment) {
        $comment_count++; // Increment counter for each comment
        ?>
        <div class="comment comment-<?php echo $comment_count; ?>">
            <div class="comment-meta">
                <p class="comment-number mb-3 base">بازخورد شماره <?php echo $comment_count; ?></p>
                <!-- <span class="comment-author"><?php echo get_comment_author_link($comment); ?></span>
                <span class="comment-date"><?php echo get_comment_date('', $comment); ?></span> -->
            </div>
            <div class="comment-text base"><?php echo wpautop($comment->comment_content); ?></div>
        </div>
        <?php
    }
} else {
    echo '<p>No approved comments yet.</p>';
}
?>