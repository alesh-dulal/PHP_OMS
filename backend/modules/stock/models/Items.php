<?php

namespace backend\modules\stock\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property integer $ItemID
 * @property string $Name
 * @property integer $CategoryID
 * @property integer $IsActive
 * @property integer $InserstedBy
 * @property string $InsertedDate
 * @property string $UpdatedBy
 * @property string $UpdateDate
 * @property integer $UnitID
 *
 * @property Category $category
 * @property Unit $unit
 * @property Stock[] $stocks
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name','CategoryID'], 'required'],
            [['IsActive', 'InserstedBy', 'UnitID','IsLongLasting','CategoryID'], 'integer'],
            [['CreatedDate', 'UpdateDate'], 'safe'],
            [['Name', 'UpdatedBy'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ItemID' => 'Item ID',
            'Name' => 'Name',
            'CategoryID' => 'Category',
            'IsActive' => 'Is Active',
            'InserstedBy' => 'Insersted By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdateDate' => 'Update Date',
            'UnitID' => 'Unit',
            'IsLongLasting'=>'IsLongLasting',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['ItemID' => 'ItemID']);
    }
}
