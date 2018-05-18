<?php

namespace backend\modules\mail\models;

use Yii;

/**
 * This is the model class for table "emailtemplate".
 *
 * @property integer $EmailTemplateID
 * @property string $Name
 * @property string $Details
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 */
class Emailtemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emailtemplate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Details', 'CreatedBy'], 'required'],
            [['Details'], 'string'],
            [['CreatedBy', 'UpdatedBy'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['Name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EmailTemplateID' => 'Email Template ID',
            'Name' => 'Name',
            'Details' => 'Details',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
        ];
    }
}
