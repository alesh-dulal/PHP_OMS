<?php

namespace backend\modules\mail\models;

use Yii;

/**
 * This is the model class for table "sendmail".
 *
 * @property integer $MailID
 * @property string $Reciever
 * @property string $Subject
 * @property string $Message
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 */
class Sendmail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sendmail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Reciever', 'Subject', 'Message', 'CreatedBy'], 'required'],
            [['Subject', 'Message'], 'string'],
            [['CreatedBy', 'UpdatedBy'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['Reciever'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MailID' => 'Mail ID',
            'Reciever' => 'Reciever',
            'Subject' => 'Subject',
            'Message' => 'Message',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
        ];
    }
}
