<?php

namespace backend\modules\stock\models;;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property integer $StockID
 * @property integer $Qty
 * @property string $IsActive
 * @property integer $InsertedBy
 * @property string $InsertedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 * @property integer $ItemID
 *
 * @property Items $item
 * @property Stockdetail $item0
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Qty', 'InsertedBy', 'UpdatedBy', 'ItemID'], 'integer'],
            [['IsActive'], 'string'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['ItemID'], 'required'],
            [['ItemID'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['ItemID' => 'ItemID']],
            [['ItemID'], 'exist', 'skipOnError' => true, 'targetClass' => Stockdetail::className(), 'targetAttribute' => ['ItemID' => 'ItemID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'StockID' => 'Stock ID',
            'Qty' => 'Qty',
            'IsActive' => 'Is Active',
            'InsertedBy' => 'Inserted By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'ItemID' => 'Item',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['ItemID' => 'ItemID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem0()
    {
        return $this->hasOne(Stockdetail::className(), ['ItemID' => 'ItemID']);
    }
}
