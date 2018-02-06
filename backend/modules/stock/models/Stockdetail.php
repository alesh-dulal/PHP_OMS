<?php

namespace backend\modules\stock\models;

use Yii;

/**
 * This is the model class for table "stockdetail".
 *
 * @property integer $StockDetailID
 * @property integer $Qty
 * @property string $Remarks
 * @property integer $IsStock
 * @property integer $UnitID
 * @property integer $IsActive
 * @property integer $InsertedBy
 * @property string $InsertedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 * @property integer $ItemID
 * @property integer $UserID
 * @property string $ExpiryDate
 *
 * @property Stock[] $stocks
 * @property Unit $unit
 */
class Stockdetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stockdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Qty', 'IsStock', 'UnitID', 'IsActive', 'InsertedBy', 'UpdatedBy', 'ItemID', 'UserID','IsDamaged'], 'integer'],
            [['CreatedDate', 'UpdatedDate', 'ExpiryDate','UserID'], 'safe'],
            [['ItemID', ], 'required'],
            [['Remarks'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'StockDetailID' => 'Stock Detail ID',
            'Qty' => 'Qty',
            'Remarks' => 'Remarks',
            'IsStock' => 'Stock',
            'UnitID' => 'Unit ID',
            'IsActive' => 'Is Active',
            'InsertedBy' => 'Inserted By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'ItemID' => 'Item',
            'UserID' => 'User',
            'ExpiryDate' => 'Expiry Date',
            'IsDamaged'=>'Is Damaged',
        ];
    }



   public function getItem()
    {
        return $this->hasOne(Items::className(), ['ItemID' => 'ItemID']);
    }
}

