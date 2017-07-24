<?php

namespace backend\models;


use yii\base\Model;

class SearchForm extends Model
{
    public $name;
    public $sn;

    public function rules()
    {
        return [
            ['name','string','max'=>50],
            ['sn','string'],
        ];
    }

    //搜索条件
    public function search($query)
    {
        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
    }

}