<?php

namespace backend\modules\user\models;

use Yii;
use backend\modules\user\models\Employee;
use backend\modules\user\models\Role;

/**
 * This is the model class for table "user".
 *
 * @property integer $UserId
 * @property string $UserName
 * @property string $auth_key
 * @property string $Password
 * @property integer $IsPasswordReset
 * @property string $PasswordKey
 * @property string $Email
 * @property string $CreatedDate
 * @property integer $CreatedBy
 * @property string $UpdatedDate
 * @property integer $UpdatedBy
 * @property integer $IsActive
 * @property integer $IsDeleted
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserName', 'Password', 'Email', 'CreatedDate', 'CreatedBy'], 'required'],
            [['IsPasswordReset', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['PasswordKey', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['UserName', 'Password', 'Email'], 'string', 'max' => 255],
            [['auth_key','PasswordKey'], 'safe'],
            [['UserName'], 'unique'],
            [['Email'], 'unique'],
        //    [['PasswordKey'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'UserId' => 'User ID',
            'UserName' => 'User Name',
            'auth_key' => 'Auth Key',
            'Password' => 'Password',
            'IsPasswordReset' => 'Is Password Reset',
            'PasswordKey' => 'Password Key',
            'Email' => 'Email',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }

}

