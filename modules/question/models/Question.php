<?php
namespace app\modules\question\models;

use app\modules\user\models\User;
use Yii;
use app\models\Post;
use app\modules\tag\models\Tag;
use app\modules\tag\models\TagItem;
use app\modules\user\models\Favorite;

class Question extends Post
{
    /**
     * 公用QuestionTrait类
     */
    use QuestionTrait;

    const TYPE = 'question';
    /**
     * 激活
     */
    const STATUS_ACTIVE = 1;
    /**
     * 审核
     */
    const STATUS_AUDIT = 0;
    /**
     * 已删除
     */
    const STATUS_DELETED = -1;

    /**
     * type为question的为问题
     * @inherit
     */
    public static function find()
    {
        return (new QuestionQuery(get_called_class()))->where([
            'type' => self::TYPE,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public function rules()
    {
        return [
            [['subject', 'content', 'author_id'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => '问题',
            'content' => '问题内容'
        ];
    }

    /**
     * 获取帖子标签(关联tag_item表)
     * @return mixed
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tid'])
            ->viaTable(TagItem::tableName(), ['target_id' => 'id'], function($model) {
                $model->andWhere(['target_type' => self::TYPE]);
            });
    }

    /**
     * 获取帖子标签记录
     * @return mixed
     */
    public function getTagItems()
    {
        return $this->hasMany(TagItem::className(), ['target_id' => 'id'])
            ->andWhere(['target_type' => self::TYPE]);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * 获取指定用户的收藏记录
     * @param null|int $authorId
     * @return mixed
     */
    public function getFavorite($authorId = null)
    {
        return $this->hasOne(Favorite::className(), [
            'target_id' => 'id',
        ])->andWhere([
            'target_type' => self::TYPE,
            'author_id' => $authorId ?: Yii::$app->getUser()->getId()
        ]);
    }

    /**
     * 获取回答列表
     * @return ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['pid' => 'id']);
    }

    /**
     * 添加回答
     * @param Answer $model
     * @return mixed
     */
    public function addAnswer(Answer $model)
    {
        $model->setAttributes([
            'pid' => $this->id
        ]);
        if ($result = $model->save()) {
            $model->setActive(); //TODO 后期可以改为开关审核问题
        }
        return $result;
    }

    /**
     * 审核
     * @return bool
     */
    public function setActive()
    {
        $return = true;
        if ($this->status != static::STATUS_ACTIVE ) {
            $this->status = static::STATUS_ACTIVE;
            if (!$this->isNewRecord) {
                $return = $this->updateAttributes(['status' => static::STATUS_ACTIVE]);
            }
        }
        return $return;
    }
}