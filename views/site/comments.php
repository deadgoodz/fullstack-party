<section id="comments">
    <div class="comments-container">
        <a href="javascript:void()" onclick="history.back()" class="comments-back-container">
            <div class="img-holder">
                <img src="/images/ico-back.png">
            </div>
            <div class="back-text">
                Back To Issues
            </div>
        </a>

        <div class="comments-inner-container">
            <div class="comments-main-header-container">
                <div class="comments-main-text">
                    <?= $issue->title ?>
                </div>
                <span>
               #<?= $issue->id ?>
           </span>
            </div>

            <div class="comments-info-container">

                <div class="comments-status-container">
                    <div class="img-holder">
                        <img src="/images/ico-open.png">
                        <span><?= $issue->state ?></span>
                    </div>
                </div>

                <div class="comments-user-container">
                    <span class="user-name"><?= $issue->user->login ?></span>
                    <span class="time-before">
                       opened this issue <?= Yii::$app->functions->getTimeAgo($issue->created_at) ?>
                    </span>
                </div>

                <div class="comment-count-container">
                    <span>  · <?= count($comments) ?></span> comment
                </div>

            </div>
        </div>
        <?php foreach ($comments as $comment) { ?>

            <div class="comment-main-container">
                <div class="avatar-place">
                    <div class="img-background" style="background:url('<?= $comment->user->avatar_url ?>')">

                    </div>
                </div>

                <div class="comment-main-item-container">
                    <div class="comment-main-header">
                        <span class="user-name"><?= $comment->user->login ?> </span> &nbsp
                        <span class="time-before">commented <?= Yii::$app->functions->getTimeAgo($comment->created_at) ?></span>
                    </div>
                    <div class="comment-main-item-body">
                        <?= $comment->body ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
