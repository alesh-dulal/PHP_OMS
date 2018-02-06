<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "userlog".
 *
 * @property integer $UserLogID
 * @property integer $UserID
 * @property string $Action
 * @property string $Remarks
 * @property string $LogDateTime
 */
class Userlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserID', 'Action'], 'required'],
            [['UserID'], 'integer'],
            [['Remarks'], 'string'],
            [['LogDateTime'], 'safe'],
            [['Action'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'UserLogID' => 'User Log ID',
            'UserID' => 'User ID',
            'Action' => 'Action',
            'Remarks' => 'Remarks',
            'LogDateTime' => 'Log Date Time',
        ];
    }
}
