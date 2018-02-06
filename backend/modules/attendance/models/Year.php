<?php

namespace backend\modules\attendance\models;

use Yii;

/**
 * This is the model class for table "year".
 *
 * @property integer $YearID
 * @property integer $Name
 */
class Year extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'year';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'YearID' => 'Year ID',
            'Name' => 'Name',
        ];
    }
}
