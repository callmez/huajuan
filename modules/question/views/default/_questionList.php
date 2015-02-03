<?php
use yii\helpers\Html;
?>
<div class="question-list">
    <div class="row">
        <div class="col-xs-12">
            <div class="info text-right">
                <span class="comment <?= $model->comment_count ? 'commented' : '' ?>" title="<?= $model->comment_count ?> 个评论">
                    <span class="pull-left num"><?= $model->comment_count ?></span>
                    <span class="fa fa-comment<?= $model->comment_count ? '' : '-o' ?>"></span>
                </span>
                <span class="like" title="<?= $model->like_count ?> 个投票">
                    <span class="pull-left num"><?= $model->like_count ?></span>
                    <span class="fa fa-thumbs<?= $model->like_count ? '' : '-o' ?>-up"></span>
                </span>
            </div>
            <div class="summary">
                <?= Html::a(Html::img($model->author->getAvatarUrl(), ['class' => 'avatar']), ['question/view', 'id' => $model->id], [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                    'title' => Html::encode($model->author->username),
                    'class' => 'pull-right'
                ]) ?>
                <h5>
                    <?= Html::a(Html::encode($model->subject), ['view', 'id' => $model->id]) ?>
                    <small>
                        <?php foreach($model->tags as $tag): ?>
                            <span class="label label-success"><?= $tag->name ?></span>
                        <?php endforeach ?>
                    </small>
                </h5>
                <div class="text-muted">
                    <span class="meta"> <span class="fa fa-eye"></span> <?= $model->view_count ?></span>&nbsp;
                    <span class="meta"> <span class="fa fa-clock-o"></span> <?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>