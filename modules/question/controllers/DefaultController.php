<?php

namespace app\modules\question\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\ControllerTrait;
use app\modules\question\models\Question;
use app\modules\question\models\QuestionSearch;
use app\modules\question\models\Answer;
use app\modules\question\models\AnswerSearch;

/**
 * DefaultController implements the CRUD actions for Question model.
 */
class DefaultController extends Controller
{
    use ControllerTrait;
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

        return $this->render('index', [
            'searchModel' => $searchModel,
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
        $model = $this->findModel($id, function($query) use ($with) {
            $with[] = 'favorite';
            $query->with($with);
        });
        $request = Yii::$app->request;
        $answerDataProvider = (new AnswerSearch())->search($request->queryParams, $model->getAnswers());
        $answerDataProvider->query->with($with);

        return $this->render('view', [
            'model' => $model,
            'answer' => $this->newAnswer($model),
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
        $model = new Question();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, \Closure $callback = null)
    {
        $query = Question::find();
        $callback !== null && $callback($query);
        if (($model = $query->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
            if ($question->addAnswer($model, true)) {
                $this->flash('回答发表成功!', 'success');
                Yii::$app->end(0, $this->refresh());
            }
        }
        return $model;
    }
}
