<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
$isQuestion = !$model->pid;
?>
<div class="<?= $model->type ?>">
    <div class="clearfix">
        <?php if ($isQuestion) : ?>
            <?php $this->title = Html::encode($model->subject) ?>
            <div class="question-title">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
        <?php endif ?>
        <div class="question-cell">
            <a class="question-like <?= $model->like ? 'active' : '' ?>" href="#"
               data-id="<?= $model->id ?>" data-do="like" data-type="<?= $model->type ?>" >
                <span class="num"><?= $model->like_count ?></span>
                <span class="fa fa-thumbs-o-up pull-left"></span> 赞
            </a>
            <a class="question-hate <?= $model->hate ? 'active' : '' ?>" href="#"
               data-id="<?= $model->id ?>" data-do="hate" data-type="<?= $model->type ?>"
               data-toggle="tooltip" data-placement="right" rel="tooltip" title="" data-original-title="谨慎使用">
                <span class="fa fa-thumbs-o-down pull-left"></span> 踩
            </a>
            <?php if ($isQuestion) : ?>
                <a class="question-fav <?= $model->favorite ? 'active' : '' ?>" href="#"
                   data-id="<?= $model->id ?>" data-do="favorite" data-type="<?= $model->type ?>"
                   data-toggle="tooltip" data-placement="right" rel="tooltip" title="" data-original-title="关注并收藏">
                    <span class="fa fa-star-o"></span> 收藏
                </a>
            <?php endif ?>
        </div>
        <div class="question-content">
            <div class="question-post">
                <?= Markdown::process($model->content, 'gfm') ?>
            </div>
            <?php if ($isQuestion) : ?>
                <div class="question-tags">
                    <?php foreach($model->tags as $tag): ?>
                        <span class="label label-success"><?= $tag->name ?></span>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            <div class="question-author">
                <?= Html::a(Html::img($model->author->getAvatarUrl(), ['class' => 'avatar avatar-sm']), ['question/view', 'id' => $model->id], [
                    'title' => Html::encode($model->author->username),
                ]) ?>
                <p><?= Html::a(Html::encode($model->author->username), ['/user/home/index', 'id' => $model->author->id]) ?></p>
                <p><?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?> <?= $isQuestion ? '提问' : '回答'?></p>
            </div>

        </div>
    </div>
    <?php if ($isQuestion): ?>
        <h4 class="mb0"> <?= $model->comment_count ?>个评论 </h4>
    <?php endif ?>
</div>