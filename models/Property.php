<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "property".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $state_id
 * @property double $price
 * @property string $money
 * @property double $commission
 * @property double $area
 * @property double $bedrooms
 * @property double $bathrooms
 * @property string $longitude
 * @property string $latitude
 * @property integer $active
 * @property string $datecreation
 * @property string $datestart
 * @property string $datelastupdate
 * @property string $owner
 * @property string $phoneowner
 * @property string $emailowner
 * @property string $address
 * @property integer $priority
 * @property double $garages
 * @property double $yearsold
 * @property integer $furnished
 * @property string $description
 * @property integer $user_id
 * @property integer $distrito_id
 *
 * @property Accesspropertydetail[] $accesspropertydetails
 * @property ImagesProperty[] $imagesProperties
 * @property Type $type
 */
class Property extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $photos;
    public $map;
    public $path = '/web/uploads/property/';
    public $imageFiles;
    public $extras;

    public static function tableName() {
        return 'property';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['datecreation', 'datelastupdate'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['datelastupdate'],
                ],
                'value' => function() {
            return date('Y-m-d H:i:s');
        },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type_id', 'state_id', 'money', 'commission', 'longitude', 'latitude', 'address', 'owner', 'phoneowner'], 'required'],
            [['priority', 'type_id', 'state_id', 'user_id', 'distrito_id', 'active', 'priority', 'furnished'], 'integer'],
            [['price', 'commission', 'area', 'bedrooms', 'bathrooms', 'garages', 'yearsold'], 'number'],
            [['datecreation', 'datestart', 'datelastupdate'], 'safe'],
            [['money'], 'string', 'max' => 1],
            [['longitude', 'latitude', 'owner'], 'string', 'max' => 50],
            [['phoneowner'], 'string', 'max' => 45],
            [['emailowner', 'address'], 'string', 'max' => 100],
            [['references'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 1000],
            [['photos'], 'safe'],
            [['photos'], 'file', 'extensions' => 'jpg, gif, png', 'maxFiles' => 25],
//            [['photos'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType'=>false, 'extensions' => 'jpg, gif, png', 'maxFiles' => 25],
//            [['photos'], 'file', 'maxSize' => '2000000'],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    public function extraFields() {
        return ['agent', 'type', 'state', 'imagesProperties'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Tipo ID'),
            'state_id' => Yii::t('app', 'Estado ID'),
            'price' => Yii::t('app', 'Precio'),
            'money' => Yii::t('app', 'Moneda'),
            'commission' => Yii::t('app', 'Comision'),
            'area' => Yii::t('app', 'Area mt2'),
            'bedrooms' => Yii::t('app', 'Cuartos'),
            'bathrooms' => Yii::t('app', 'Baños'),
            'longitude' => Yii::t('app', 'Longitud'),
            'latitude' => Yii::t('app', 'Latitud'),
            'active' => Yii::t('app', 'Activo'),
            'datecreation' => Yii::t('app', 'Dia de creacion'),
            'datestart' => Yii::t('app', 'Dia de inicio'),
            'datelastupdate' => Yii::t('app', 'Dia ultima actualizacion'),
            'owner' => Yii::t('app', 'Propietario'),
            'phoneowner' => Yii::t('app', 'Telefono propietario'),
            'emailowner' => Yii::t('app', 'Correo elec. propietario'),
            'address' => Yii::t('app', 'Direccion'),
            'references' => Yii::t('app', 'Referencias'),
            'photos' => Yii::t('app', 'Fotos'),
            'map' => Yii::t('app', 'Mapa'),
            'priority' => Yii::t('app', 'Prioridad'),
            'garages' => Yii::t('app', 'Estacionamientos'),
            'yearsold' => Yii::t('app', 'Años de antigüedad'),
            'furnished' => Yii::t('app', 'Amoblado'),
            'description' => Yii::t('app', 'Descripcion'),
            'extras' => Yii::t('app', 'Extras del inmueble'),
            'imageFiles' => Yii::t('app', 'Imagenes'),
            'user_id' => Yii::t('app', 'Agente encargado'),
            'distrito_id' => Yii::t('app', 'Distrito'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesspropertydetails() {
        return $this->hasMany(Accesspropertydetail::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesProperties() {
        return $this->hasMany(ImagesProperty::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState() {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrito() {
        return $this->hasOne(Distrito::className(), ['idDist' => 'distrito_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }    

    public function getFirstImageFromProperty() {
        return $this->getImagesProperties()->where(['order' => 1])->one()->name;
    }

    public static function getPropertyByPriority($priority, $limit) {
        return Property::find()->where(['priority' => $priority])->andWhere(['active' => 1])->with(['agent', 'type', 'state', 'imagesProperties'])->asArray()->limit($limit)->all();
    }

    public static function getPropertiesRecentlyAdded($daysold, $limit) {
        return Property::find()->where(['between', 'datecreation', date('Y-m-d H:i:s', strtotime("-$daysold day")), date('Y-m-d H:i:s')])->andWhere(['active' => 1])->orderBy("datecreation DESC")->limit($limit)->all();
    }

    public function savePropertyDetails($arrayPropertyDetail) {
        Accesspropertydetail::deleteAll(['property_id' => $this->primaryKey]);
        if (is_array($arrayPropertyDetail))
            foreach ($arrayPropertyDetail as $pd) {
                $aod = new Accesspropertydetail();
                $aod->property_id = $this->primaryKey;
                $aod->property_detail_id = intval($pd);
                $aod->active = 1;
                $aod->save();
            }
    }

    public function getPropertyDetail() {
        $html = "";
        $pds = $this->getAccesspropertydetails()->all();
        if (count($pds) > 0) {
            foreach ($pds as $pd) {
                $name = $pd->getPropertyDetail()->one()->name;
                $html .= "<span class='label label-success'>$name</span><br>";
            }
        } else {
            $html = "<span class='label label-danger'>No tiene extras.</span>";
        }
        return $html;
    }

    public function deleteImages() {
        $images = $this->getImagesProperties()->orderBy('order')->all();
        foreach ($images as $image) {
            $path = Yii::$app->basePath . $this->path;
            $filename = $image->name;
            $file = array();
            $file[] = $path . $filename;
            $file[] = $path . 'sqr_' . $filename;
            $file[] = $path . 'sm_' . $filename;
            foreach ($file as $f) {
                // check if file exists on server
                if (!empty($f) && file_exists($f)) {
                    // delete file
                    unlink($f);
                }
            }
        }
    }

    public function getImagesCarousel($size) {
        $images = $this->getImagesProperties()->orderBy('order')->all();
        if (count($images) > 0) {
            $divContent = "";
            foreach ($images as $image) {
                $img = Html::img("@web/uploads/property/$size$image->name", ['class' => 'img-responsive', 'alt' => $image->order]);
                $divContent .= Html::tag('div', $img, ['class' => 'item pull-left']);
            }
            return Html::tag('div', $divContent, ['id' => 'owl-images-property']);
        }
        return Html::tag('div', "No hay imagenes", ['id' => 'owl-images-property', 'class' => 'alert alert-warning']);
    }

    public function getImagesClient($size, $width) {
        $images = $this->getImagesProperties()->orderBy('order')->all();
        if (count($images) > 0) {
            $divContent = "";
            foreach ($images as $image) {
                $img = Html::img("@web/uploads/property/$size$image->name", ['width' => $width, 'class' => 'img-responsive', 'alt' => $image->order]);
                $divContent .= Html::tag('div', $img, ['class' => 'item pull-left']);
            }
            return Html::tag('div', $divContent, ['id' => 'owl-images-property']);
        }
        return Html::tag('div', "No hay imagenes", ['id' => 'owl-images-property', 'class' => 'alert alert-warning']);
    }

}
