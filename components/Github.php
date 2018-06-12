<?php

namespace app\components;


use linslin\yii2\curl\Curl;
use Yii;
use yii\base\Exception;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class Github
{
    /**
     * @var Curl
     */
    private $curl;
    public static $baseUrl = "https://api.github.com";


    public function __construct()
    {
        $this->curl = new Curl();
    }

    public function performCurlGet($path, $data = [])
    {
        $response = $this->curl->setGetParams($data)
            ->get(self::$baseUrl . $path);


        if ($this->curl->responseCode != 200) {
            throw new Exception('API call failed at '.$path.' : '. $this->curl->responseCode);
        }

        return json_decode($response);

    }

    public function getIssues($type)
    {
        if (Yii::$app->user->getIdentity()) {
            $state = '';

            if (!empty($type)) {
                $state = '?state=' . $type;
            }

            $path = '/repos/' . Yii::$app->user->getIdentity()->username . '/proman/issues' . $state;
            $data = $this->performCurlGet($path);

            return $data;
        }
    }

    public function getComments($id)
    {
        if (Yii::$app->user->getIdentity()) {
            $path = '/repos/' . Yii::$app->user->getIdentity()->username . '/proman/issues/' . $id . '/comments';
            $data = $this->performCurlGet($path);

            return $data;
        }
    }

}