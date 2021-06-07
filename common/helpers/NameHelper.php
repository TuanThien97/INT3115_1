<?php

namespace common\helpers;

class NameHelper
{
    /**
     * split full name of object to first name and last name
     */
    public static function splitName($full_name)
    {
        $full_name = chop($full_name);
        $name_array = explode(" ", $full_name);
        $length = count($name_array);

        $last_name = $name_array[$length - 1];
        $first_name = chop(rtrim($full_name, $last_name));
        if ($first_name == null) {
            throw new \yii\base\Exception('Tên không hợp lệ');
        }

        return [
            'first_name'=>$first_name,
            'last_name'=>$last_name,
        ];
    }

    /**
     * Merge first name and last name into full name
     */
    public static function stickName($model)
    {
        $model->full_name = "$model->first_name $model->last_name";
        return $model;
    }
}
