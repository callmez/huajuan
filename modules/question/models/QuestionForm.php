<?php
namespace app\modules\question\models;

use yii\base\Model;
use app\modules\tag\models\Tag;
use app\modules\tag\models\TagItem;

class QuestionForm extends Model
{
    public $subject;
    public $tags;
    public $content;
    public $author_id;

    public function rules()
    {
        return [
            [['subject', 'content', 'tags', 'author_id'], 'required'],
            [['tags'], 'checkTags']
        ];
    }

    public function checkTags($attribute, $params)
    {
        $tags = is_array($this->tags) ? $this->tags : explode(',', $this->tags);
        $maxLength = 5; // TODO 后台控制最大标签数
        if (count($tags) > $maxLength) {
            $this->addError($attribute, '最多可以选择' . $maxLength . '个标签');
        }
    }

    public function create()
    {
        $question = new Question();
        $question->setAttributes([
            'subject' => $this->subject,
            'content' => $this->content,
            'author_id' => $this->author_id,
        ]);
        if (($result = $question->save()) && ($result = $question->setActive())) {
            $tags = Tag::findAll([
                'name' => is_array($this->tags) ? $this->tags : explode(',', $this->tags)
            ]);
            foreach ($tags as $tag) {
                $tagItem = new TagItem();
                $tagItem->setAttributes([
                    'target_id' => $question->id,
                    'target_type' => $question::TYPE,
                ]);
                $tag->addItem($tagItem);
            }
        }
        return $result ? $question : false;
    }

    public function attributeLabels()
    {
        return [
            'subject' => '问题',
            'content' => '内容',
            'tags' => '标签'
        ];
    }
}