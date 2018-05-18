<?php

namespace backend\modules\report\models;

use Yii;

/**
 * This is the model class for table "customreport".
 *
 * @property integer $CustomReportID
 * @property string $Name
 * @property string $Query
 * @property integer $DateRangeEnabled
 * @property string $SelectColumn
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 */
class Customreport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customreport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Query', 'DateRangeEnabled', 'CreatedBy'], 'required'],
            [['Query', 'SelectColumn'], 'string'],
            [['DateRangeEnabled', 'CreatedBy', 'UpdatedBy'], 'integer'],
            [['CreatedDate', 'UpdatedDate','SelectOption'], 'safe'],
            [['Name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CustomReportID' => 'Custom Report ID',
            'Name' => 'Name',
            'Query' => 'Query',
            'DateRangeEnabled' => 'Date Range Enabled',
            'SelectColumn' => 'Select Column',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
        ];
    }
}
