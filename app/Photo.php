<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
  protected $keyType = 'string'; // プライマリキーの型

  const ID_LENGTH = 12; // idの桁数

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if(! Arr::get($this->attributes, 'id')){
      $this->setId();
    }
  }

  // ランダムなID値をid属性に代入する
  private function setId()
  {
    $this->attributes['id'] = $this->getRandomId();
  }

  // ランダムなID値を生成する
  private function getRandomId()
  {
    $characters = Arr::merge(
      range(0,9),range(a,z),
      range(A,Z),['-','_',]
    );

    $length = count($characters);

    $id = '';

    for ($i = 0; $i < self::ID_LENGTH; $i++){
      $id .= $characters[randam_int(0, $length -1 )];
    }

    return $id;
  }
}
