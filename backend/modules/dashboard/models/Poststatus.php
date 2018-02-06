<?php

namespace backend\modules\dashboard\models;

use Yii;

/**
 * This is the model class for table "poststatus".
 *
 * @property integer $PostID
 * @property string $Title
 * @property string $Description
 * @property string $Type
 * @property integer $UsersID
 */
class Poststatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poststatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Title', 'Description', 'Type'], 'required'],
            [['Description'], 'string'],
            [['UsersID'], 'string'],
            [['InsertedBy'], 'integer'],
            [['Title'], 'string', 'max' => 250],
            [['Type'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PostID' => 'Post ID',
            'Title' => 'Title',
            'Description' => 'Description',
            'Type' => 'Type',
            'UsersID' => 'Users ID',
            'PostedBy'=>'Posted By'
        ];
    }
}
