<?php

namespace app\modules\question\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\modules\tag\models\Tag;
use app\modules\question\components\Controller;
use app\modules\question\models\Question;
use app\modules\question\models\QuestionForm;
use app\modules\question\models\QuestionSearch;
use app\modules\question\models\Answer;
use app\modules\question\models\AnswerSearch;

/**
 * DefaultController implements the CRUD actions for Question model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->with('tags');
        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'hotest' => [
                'asc' => [
                    'comment_count' => SORT_DESC,
                    'created_at' => SORT_DESC
                ],
                'desc' => [
                    'comment_count' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
            'uncommented' => [
                'asc' => [
                    'comment_count' => SORT_ASC,
                    'created_at' => SORT_DESC
                ],
                'desc' => [
                    'comment_count' => SORT_ASC,
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'sorts' => array(
                'newest' => '最新的',
                'hotest' => '热门的',
                'uncommented' => '未回答的'
            ),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $with = ['hate', 'like', 'author'];
        $model = $this->findQuestion($id, function($query) use ($with) {
            $with[] = 'favorite';
            $with[] = 'tags';
            $query->with($with);
        });

        $answer = $this->newAnswer($model);

        $request = Yii::$app->request;
        $answerDataProvider = (new AnswerSearch())->search($request->queryParams, $model->getAnswers());
        $answerDataProvider->query->with($with);

        return $this->render('view', [
            'model' => $model,
            'answer' => $answer,
            'answerDataProvider' => $answerDataProvider
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QuestionForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->author_id = Yii::$app->user->getId();
            if ($model->validate() && ($question = $model->create())) {
                return $this->message('问题发表成功!!', 'success', ['view', 'id' => $question->id], 'flash');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'tagModel' => new Tag()
        ]);
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findQuestion($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 创建新回答
     * @param $question
     * @return Answer
     */
    protected function newAnswer(Question $question)
    {
        $model = new Answer();
        if ($model->load(Yii::$app->request->post())) {
            $model->author_id = Yii::$app->user->id;
            if ($question->addAnswer($model)) {
                return $this->message('回答发表成功!', 'success', $this->refresh(), 'flash');
            }
        }
        return $model;
    }
}
