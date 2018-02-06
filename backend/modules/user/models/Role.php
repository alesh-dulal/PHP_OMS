<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $RoleID
 * @property string $Name
 * @property string $MenuID
 * @property string $CreatedDate
 * @property integer $CreatedBy
 * @property string $UpdatedDate
 * @property integer $UpdatedBy
 * @property integer $IsActive
 * @property integer $IsDeleted
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CreatedDate', 'UpdatedDate','MenuID'], 'safe'],
            [['CreatedBy'], 'required'],
            [['CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Name'], 'string', 'max' => 50],
            [['MenuID'], 'string', 'max' => 3000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RoleID' => 'Role ID',
            'Name' => 'Name',
            'MenuID' => 'Menu ID',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
