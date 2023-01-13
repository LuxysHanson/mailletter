<?php

namespace app\interfaces;

use yii\db\ActiveRecord;
use yii\db\Query;

interface IBaseRepository
{

    public function getModel(): ActiveRecord;

    public function find(): Query;

    public function save(ActiveRecord $model);

    public function delete(ActiveRecord $model);

}
