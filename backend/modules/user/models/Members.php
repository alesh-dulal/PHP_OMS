<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property integer $MemberID
 * @property string $FullName
 * @property string $Address
 * @property string $CellPhone
 * @property string $Email
 * @property string $Remarks
 * @property string $Type
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 */
class Members extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FullName', 'Address', 'CellPhone', 'Email', 'Type', 'CreatedBy'], 'required'],
            [['Remarks'], 'string'],
            [['CreatedBy', 'UpdatedBy'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['FullName', 'Address'], 'string', 'max' => 250],
            [['CellPhone'], 'string', 'max' => 20],
            [['Email'], 'string', 'max' => 100],
            [['Type'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MemberID' => 'Member ID',
            'FullName' => 'Full Name',
            'Address' => 'Address',
            'CellPhone' => 'Cell Phone',
            'Email' => 'Email',
            'Remarks' => 'Remarks',
            'Type' => 'Type',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
        ];
    }
}
