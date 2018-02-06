<?php

namespace backend\modules\holiday\models;

use Yii;

/**
 * This is the model class for table "holiday".
 *
 * @property integer $HolidayID
 * @property string $Name
 * @property string $Description
 * @property string $Day
 * @property integer $Year
 * @property integer $IsActive
 * @property integer $InsertedBy
 * @property string $InsertedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Description', 'Day', 'Year', 'InsertedBy'], 'required'],
            [['Description'], 'string'],
            [['Day', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['Year', 'IsActive', 'InsertedBy', 'UpdatedBy'], 'integer'],
            [['Name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'HolidayID' => 'Holiday ID',
            'Name' => 'Name',
            'Description' => 'Description',
            'Day' => 'Day',
            'Year' => 'Year',
            'IsActive' => 'Is Active',
            'InsertedBy' => 'Inserted By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
        ];
    }
}
