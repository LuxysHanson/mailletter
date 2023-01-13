<?php

namespace app\models\entities;

use yii\base\Model;

class CurlEntity extends Model
{

    private $url;
    private $type;
    private $headers = [];
    private $postData = [];


    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function getPostData(): array
    {
        return $this->postData;
    }

    public function setPostData(array $postData): void
    {
        $this->postData = $postData;
    }

}
