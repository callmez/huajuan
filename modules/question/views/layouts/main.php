<?php
use app\modules\question\assets\QuestionAsset;
QuestionAsset::register($this);
?>

<?php $this->beginContent('@app/views/layouts/main.php') ?>
    <div class="row">
        <div class="col-xs-12 col-md-9">
            <?= $content ?>
        </div>
        <div class="col-xs-12 col-md-3">

        </div>
    </div>
<?php $this->endContent() ?>