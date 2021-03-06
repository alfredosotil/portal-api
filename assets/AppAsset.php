<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public static function menuDashboard() {
        $menu = [];
        if (!Yii::$app->user->isGuest) {
//            $menu["isGuest"] = Yii::$app->user->isGuest;
//            $menu["userID"] = Yii::$app->user->identity->id;
//            $menu["DB"] = Yii::$app->db;
//             $menu["sql"] = str_replace(":USER_ID", Yii::$app->user->identity->id, "select distinct m.type_id, t.type from profile p, user u, module m, access a, type t where u.id=:USER_ID and t.category='module' and p.id=u.profile_id and p.id=a.profile_id and m.id=a.module_id and t.id=m.type_id order by 1");
            $typemodules = AppAsset::executeQuery(Yii::$app->db, "select distinct m.type_id, t.type
            from profile p, user u, module m, access a, type t
            where u.id=:USER_ID
            and t.category='module'
            and p.id=u.profile_id
            and p.id=a.profile_id
            and m.id=a.module_id
            and t.id=m.type_id
            order by 1", [':USER_ID' => intval(Yii::$app->user->identity->id)]);
//            $menu["typeModules"] = $typemodules;
            foreach ($typemodules as $value) {
                $object = [];
                $object["typeModule"] = $value;
                $modules = AppAsset::executeQuery(Yii::$app->db, "select p.name, 
                m.label,
                m.iconfa,
                m.controller,
                m.description,
                m.active
                from access a, module m, profile p, type t, user u
                where u.id=:USER_ID
                and t.id=:TYPE
                and t.category='module'
                and p.id=u.profile_id
                and p.id=a.profile_id
                and m.id=a.module_id
                and t.id=m.type_id
                and m.active=1
                order by 2", [':USER_ID' => intval(Yii::$app->user->identity->id), ':TYPE' => intval($value['type_id'])]);
                $object["modules"] = $modules;
                $menu[] = $object;
            }
        }
        return $menu;
    }
    
    public static function executeQuery($db, $query, $params) {
        return $db->createCommand($query, $params)->queryAll();
    }

    public static function updateQuery($db, $query, $params) {
        return $db->createCommand($query, $params)->execute();
    }
}
