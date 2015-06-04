<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use Yii;

class Like extends ActiveRecord
{
    const OBJECT_TYPE_GITHUB_PROJECT = 0;
    const OBJECT_TYPE_GITHUB_USER = 1;
    
    const STATUS_UNLIKE = 0;
    const STATUS_LIKE = 1;
    
    static $STATUS_TEXT = [
        self::STATUS_UNLIKE => 'unlike',
        self::STATUS_LIKE => 'like'
    ];

    public static function addLike($object_type, $object_id){
        if(!Yii::$app->user->isGuest){
            $like = static::findOne(
                [
                    'user_id' => Yii::$app->user->id,
                    'object_type' => $object_type, 
                    'object_id' => $object_id
                ]
            );
            if($like === null){
                $like = new Like();
                $like->user_id = Yii::$app->user->id;
                $like->object_type = $object_type;
                $like->object_id = $object_id;
                return $like->save();
            }
            return $like->save();
        }
        return false;
    }
    
    public static function deleteLike($object_type, $object_id){
        if(!Yii::$app->user->isGuest){
            $like = Like::findOne(
                [
                    'user_id' => Yii::$app->user->id,
                    'object_type' => $object_type, 
                    'object_id' => $object_id
                ]
            );
            if($like){
                return $like->delete();
            }
        }
        return false;
    }
    
    /**
     * 
     * @param type $object_type integer
     * @param type $object_id integer|string
     * @return boolean
     */
    public static function isLiked($object_type, $object_id){
        if(!Yii::$app->user->isGuest){
            return (bool) (new Query())
                ->select('user_id')
                ->from('like')
                ->where(
                    '`user_id` = :user_id AND `object_type` = :type AND `object_id` = :id', 
                    [':user_id' => Yii::$app->user->id, ':type' => $object_type, ':id' => $object_id])
                ->one();
        }
        return false;
    }
}
