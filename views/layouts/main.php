<?php
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
Html::beginForm()
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript">
        var G = {
            baseUrl: '<?= Url::base(true) ?>'
        };
    </script>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-white navbar-fixed-top',
                ],
            ]);
            $items = [
                ['label' => '首页', 'url' => ['/site/index']],
                ['label' => '问答', 'url' => ['/question/default/index']],
            ];
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'encodeLabels' => false,
                'items' => $items,
            ]);
            $items = [];
            if (Yii::$app->user->isGuest) {
                $items = [
                    ['label' => '登录', 'url' => Yii::$app->user->loginUrl],
                    ['label' => '注册', 'url' => ['/user/signup']]
                ];
            } else {
                $user = Yii::$app->user;
                $identity = $user->identity;

                $items = [
                    [
                        'label' => '发表问题',
                        'url' => ['/question/default/create']
                    ],
                    [
                        'label' => Html::img($identity->getAvatarUrl([
                            'width' => 32,
                            'height' => 32
                        ]), [
                            'class' => 'avatar avatar-xs',
                        ]) . ' ' . $identity->username,
                        'items' => [
                            [
                                'label' => '<span class="fa fa-home fa-fw"></span> 个人中心',
                                'url' => ['/user/home/index', 'id' => $user->id]
                            ],
                            [
                                'label' => '<span class="fa fa-user fa-fw"></span> 后台管理',
                                'url' => ['/admin'],
                                'visible' => $user->can('visitAdmin')
                            ],
                            '<li class="divider"></li>',
                            [
                                'label' => '<span class="fa fa-sign-out fa-fw"></span> 退出登录',
                                'url' => ['/user/logout']
                            ]
                        ]
                    ]
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items' => $items,
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <dt>网站信息</dt>
                    <dd> <a href="<?= Url::to(['/site/about']) ?>">关于我们</a> </dd>
                </div>
                <div class="col-sm-2">
                    <dt>相关合作</dt>
                    <dd> <a href="<?= Url::to(['/site/contact']) ?>">联系我们</a> </dd>
                </div>
                <div class="col-sm-2">
                    <dt>关注我们</dt>
                    <dd> <a href="<?= Url::to(['/site']) ?>">成长日志</a> </dd>
                </div>
                <div class="col-sm-6">
                    <dt> 技术采用 </dt>
                    <dd> 由 <a href="https://github.com/callmez">CallMeZ</a> 创建 项目地址: <a href="https://github.com/callmez/huajuan">huajuan</a> </dd>
                    <dd> <?= Yii::powered() ?> <?= Yii::getVersion() ?> </dd>
                </div>
            </div>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
