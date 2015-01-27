<?php
namespace app\modules\tag\models;

use yii\db\ActiveRecord;

class Tag extends ActiveRecord
{
    /**
     * 审核通过
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

    public static function find()
    {
        return parent::find()->andWhere(['status' => self::STATUS_ACTIVE]);
    }

    public static function tableName()
    {
        return '{{%tag}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['description'], 'default', 'value' => ''],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '标签名',
            'description' => '标签描述',
            'icon' => '标签图标'
        ];
    }

    public function getItems()
    {
        return $this->hasMany(TagItem::className(), ['id' => 'tid']);
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

    public function addItem(TagItem $item)
    {
        $item->setAttributes([
            'tid' => $this->id
        ]);
        return $item->save();
    }

}