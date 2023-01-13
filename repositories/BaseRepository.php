<?php

namespace app\repositories;

use app\components\exceptions\RepositoryException;
use app\interfaces\IBaseRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\NotFoundHttpException;

abstract class BaseRepository implements IBaseRepository
{

    /**
     * @var Query
     */
    protected $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new Query();
    }

    public function find(): Query
    {
        $model = $this->getModel();
        return $this->queryBuilder->from($model::tableName());
    }

    /**
     * @param ActiveRecord $model
     * @return bool
     * @throws RepositoryException
     */
    public function save(ActiveRecord $model)
    {
        if (!$model->save()) {
            throw new RepositoryException('Save error', 500);
        }
        return true;
    }

    /**
     * @param ActiveRecord $model
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(ActiveRecord $model)
    {
        return $model->delete();
    }

    /**
     * @param int $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function oneById(int $id)
    {
        $exists = $this->find()->andWhere(['id' => $id])->one();
        if ($exists instanceof ActiveRecord) {
            return $exists;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Данные не найдены'));
    }

}
