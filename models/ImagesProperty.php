<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "images_property".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $name
 * @property integer $order
 * @property integer $active
 *
 * @property Property $property
 */
class ImagesProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id'], 'required'],
            [['property_id', 'order', 'active'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Property::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'property_id' => Yii::t('app', 'Property ID'),
            'name' => Yii::t('app', 'Name'),
            'order' => Yii::t('app', 'Order'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }

    /**
     * @inheritdoc
     * @return ImagesPropertyQuery the active query used by this AR class.
     */
    
}
