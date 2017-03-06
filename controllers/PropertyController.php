<?php

namespace app\controllers;

use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\Cors;

class PropertyController extends ActiveController {

    public $modelClass = 'app\models\Property';
    public $expand = ['agent', 'type', 'state', 'imagesProperties'];

    public function behaviors() {
        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => HttpBearerAuth::className(),
//            'only' => ['*'],
//        ];
//        $behaviors['contentNegotiator'] = [
//            'class' => ContentNegotiator::className(),
//            'formats' => [
//                'application/json' => Response::FORMAT_JSON,
//            ],
//        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
//            'only' => ['*'],
            'rules' => [
                [
//                    'actions' => ['*'],
                    'allow' => true,
//                    'roles' => ['@'],
                ],
            ],
        ];
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
        ];
        return $behaviors;
    }

    public function actionSearch() {
        if (!empty($_GET)) {
            $model = new $this->modelClass;
            foreach ($_GET as $key => $value) {
                if (!$model->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            try {
                $provider = new ActiveDataProvider([
                    'query' => $model->find()->where($_GET)->with($this->expand)->with($this->expand)->asArray(),
                    'pagination' => false
                ]);
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }

            if ($provider->getCount() <= 0) {
                throw new \yii\web\HttpException(404, 'No entries found with this query string');
            } else {
                return $provider;
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

}
