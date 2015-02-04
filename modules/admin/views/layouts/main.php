<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\admin\components\Menu;
use app\modules\admin\widgets\Alert;
use app\modules\admin\widgets\SidebarMenu;
use app\modules\admin\assets\AdminAsset;

AdminAsset::register($this);
$user = Yii::$app->getUser()->getIdentity();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="skin-blue">
<?php $this->beginBody() ?>
    <div class="wrapper">
        <header class="main-header">
            <?= Html::a(Yii::$app->name, ['/admin'], ['class' => 'logo']) ?>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?= $user->username ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header bg-light-blue">
                                    <img src="http://www.gravatar.com/avatar/632c8988831808e77ad27c4215384254?r=g&amp;s=128" alt="admin">                                <p>
                                        <?= $user->username ?><small><?= $user->email ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?= Url::to(['/user/logout']) ?>" class="btn btn-default btn-flat" data-method="post">退出</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search..."/>
                        <span class="input-group-btn">
                            <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                <?= SidebarMenu::widget([
                    'items' => Menu::get()
                ]) ?>
            </section>
        </aside>
        <aside class="content-wrapper">
            <section class="content-header">
                <h1>
                    Dashboard
                    <small>Control panel</small>
                </h1>
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </section>
            <section class="content">
                <?= Alert::widget() ?>
                <?php if (isset($this->params['bodyContainer']) && $this->params['bodyContainer'] == false): ?>
                    <?= $content ?>
                <?php else: ?>
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">
                                <?= Html::encode($this->title) ?>
                            </h4>
                        </div>
                        <div class="box-body">
                            <?= $content ?>
                        </div>
                    </div>
                <?php endif ?>
            </section>
        </aside>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                Powered by <b><a href="https://github.com/callmez">CallMeZ</a></b>
            </div>
        </footer>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
