<?php
namespace app\modules\user\models;

use app\modules\question\models\Answer;
use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use app\modules\question\models\Question;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'UID',
            'username' => '用户名',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 获取用户头像,(如果无数据则返回默认头像)
     * @return mixed
     */
    public function getAvatarUrl()
    {
        return Url::to(['/images/anonymous.jpg']);
    }

    /**
     * 获取用户喜欢列表
     * @return static
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['author_id' => 'id'])->inverseOf('author');
    }

    /**
     * 获取用户喜欢的指定问题
     * @return static
     */
    public function getLikeQuestion($id)
    {
        return $this->hasOne(Question::className(), ['id' => 'target_id'])
            ->via('likes', function($query) use ($id) {
                $query->andWhere([
                    'target_type' => Question::TYPE,
                    'target_id' => $id
                ]);
                $query->multiple = false;
            });
    }

    /**
     * 获取用户喜欢的问题
     * @return static
     */
    public function getLikeQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'target_id'])
            ->via('likes', function($query) {
                $query->andWhere([
                    'target_type' => Question::TYPE
                ]);
            });
    }

    /**
     * 获取用户喜欢的回答
     * @return static
     */
    public function getLikeAnswers()
    {
        return $this->hasMany(Answer::className(), ['id' => 'target_id'])
            ->via('likes', function($query) {
                $query->andWhere([
                    'target_type' => Answer::TYPE
                ]);
            });
    }

    /**
     * 获取用户喜欢的指定回答
     * @return static
     */
    public function getLikeAnswer($id)
    {
        return $this->hasOne(Answer::className(), ['id' => 'target_id'])
            ->via('likes', function($query) use ($id) {
                $query->andWhere([
                    'target_type' => Answer::TYPE,
                    'target_id' => $id
                ]);
                $query->multiple = false;
            });
    }

    /**
     * 获取用户发表的问题
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['author_id' => 'id'])->inverseOf('author');
    }

    /**
     * 获取用户发表的问题
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion($id)
    {
        return $this->hasOne(Question::className(), ['author_id' => 'id'])
            ->andWhere(['id' => $id]);
    }
}
