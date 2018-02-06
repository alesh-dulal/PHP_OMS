<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "listitems".
 *
 * @property integer $ListItemID
 * @property string $Type
 * @property string $Title
 * @property string $Value
 * @property integer $IsParent
 * @property string $Options
 * @property string $CreatedDate
 * @property integer $CreatedBy
 * @property string $UpdatedDate
 * @property integer $UpdatedBy
 * @property integer $IsActive
 * @property integer $IsDeleted
 */
class Listitems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'listitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Type', 'Title', 'Value', 'Options', 'CreatedBy'], 'required'],
            [['CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted', 'ParentID'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['Type', 'Title', 'Value', 'Options'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ListItemID' => 'List Item ID',
            'Type' => 'Type',
            'Title' => 'Title',
            'Value' => 'Value',
            'ParentID' => 'Parent',
            'Options' => 'Options', 
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
