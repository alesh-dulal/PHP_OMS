<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "excel".
 *
 * @property int $ID
 * @property string $Name
 * @property int $Age
 * @property string $CellNumber
 */
class Excel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'excel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Age'], 'integer'],
            [['Name', 'CellNumber'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Name',
            'Age' => 'Age',
            'CellNumber' => 'Cell Number',
        ];
    }
}
