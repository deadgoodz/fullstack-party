<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<section id="issues">
    <div class="issues-list-container">
        <div class="list-inner-container">
            <div class="filter-container">
                <a href="<?= Url::to(['/', 'state' => 'open']); ?>"
                   class="issues-filter-button issues-open-container <?= Yii::$app->functions->getActiveState($_GET,
                       'open') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">

                        <path id="ico-open" class="cls-1"
                              d="M1827,142a8,8,0,1,0,8,8A8.024,8.024,0,0,0,1827,142Zm0,12a1,1,0,1,1,1-1A0.945,0.945,0,0,1,1827,154Zm1-3h-2v-5h2v5Z"
                              transform="translate(-1819 -142)"/>
                    </svg>

                    <div class="issues-count">
                        <?= $counts['open'] ?> Open
                    </div>
                </a>
                <a href="<?= Url::to(['/', 'state' => 'closed']); ?>"
                   class="issues-filter-button issues-closed-container <?= Yii::$app->functions->getActiveState($_GET,
                       'closed') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="ico-closed" class="cls-1"
                              d="M1932,142a8,8,0,1,0,8,8A8.01,8.01,0,0,0,1932,142Zm0,14a6,6,0,1,1,6-6A6.005,6.005,0,0,1,1932,156Zm-1-2.586L1927.59,150l1.41-1.414,2,2,4-4,1.41,1.414Z"
                              transform="translate(-1924 -142)"/>
                    </svg>
                    <div class="issues-count">
                        <?= $counts['closed'] ?> Closed
                    </div>
                </a>

            </div>
            <div class="list-item-container">

                <?php

                if (!empty($issues)) {
                    foreach ($issues as $issue) { ?>
                        <div class="list-item">
                            <div class="list-item-inner-container">
                                <div class="img-warrning-container">
                                    <div class="img-holder">
                                        <img src="/images/list-warring.png">
                                    </div>
                                </div>
                                <div class="main-item-container">
                                    <div class="item-header">
                                        <div class="item-text">
                                            <?= $issue->title ?>
                                        </div>
                                        <?php if (isset($issue->labels)) { ?>
                                            <div class="item-label-container">
                                                <?php foreach ($issue->labels as $label) { ?>
                                                    <div class="item-label"
                                                         style="background-color: #<?= $label->color ?>"><?= $label->name ?></div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="item-info">
                                        #<?= $issue->id ?>
                                        opened <?= Yii::$app->functions->getTimeAgo($issue->created_at) ?> by <span
                                                class="owner"><?= $issue->user->login ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="list-comments">
                                <a href="" class="list-comment-container">
                                    <div class="img-holder-right">
                                        <img src="/images/comment-icon.png">
                                    </div>
                                    <p class="comment-count">
                                        <?= $issue->comments ?>
                                    </p>
                                </a>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>

            <?php
            echo LinkPager::widget([
                'pagination' => $pagination,
                'prevPageLabel' => 'Previous',
                'nextPageLabel' => 'Next',

                'options' => [
                    'tag' => 'ul',
                    'class' => 'pager-wrapper',
                    'id' => 'pager-container',

                ],
            ]);
            ?>

        </div>
    </div>
    <div class="issues-image-container">
        <div class="photo-text-container">
            <div class="side-photo-text">
                Full Stack Developer Task
            </div>
            <div class="side-by-container">
                <span>by</span>
                <div class="img-holder">
                    <img src="/images/logo-photo.png">
                </div>
            </div>
        </div>
    </div>
</section>