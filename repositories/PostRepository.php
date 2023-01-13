<?php

namespace app\repositories;

use app\models\Post;
use yii\db\ActiveRecord;

/**
 * Class PostRepository
 * @package app\repositories
 */
class PostRepository extends BaseRepository
{

    public function getModel(): ActiveRecord
    {
        return new Post;
    }

    /**
     * @param int $city_id
     * @return array
     * @throws \yii\db\Exception
     */
    public function getPostsByCityId(int $city_id): array
    {

        $command = $this->find()
            ->andWhere([
                'city_id' => $city_id,
                'hidden_from_site' => 0
            ])
            ->andWhere([
                '>=', 'endDate', date('Y-m-d')
            ])
            ->orderBy('priority DESC')
            ->createCommand();

        return $command->queryAll();
    }

}
