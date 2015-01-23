<?php
use app\modules\question\assets\QuestionAsset;
QuestionAsset::register($this);
!isset($this->params['breadcrumbs']) && $this->params['breadcrumbs'] = [];
array_unshift($this->params['breadcrumbs'], ['label' => '问答', 'url' => ['index']]);
?>
<?php $this->beginContent('@app/views/layouts/main.php') ?>
    <?= $content ?>
<?php $this->endContent() ?>