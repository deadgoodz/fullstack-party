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
            throw new Exception('API call failed at ' . $path . ' : ' . $this->curl->responseCode);
        }

        return json_decode($response);

    }

    public function getIssues()
    {
        if (Yii::$app->user->getIdentity()) {
            $path = '/repos/' . Yii::$app->user->getIdentity()->username . '/proman/issues?state=all';
            $data = $this->performCurlGet($path);
            if (!empty($data)) {
                $data = $this->groupIssues($data);
            }

            return $data;
        }
    }

    public function getIssue($id)
    {
        if (Yii::$app->user->getIdentity()) {
            $path = '/repos/' . Yii::$app->user->getIdentity()->username . '/proman/issues/' . $id;
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

    public function groupIssues($data)
    {
        $items = [];
        foreach ($data as $item) {
            if ($item->state == 'open') {
                $items[$item->state][] = $item;
            } else {
                $items['closed'][] = $item;
            }
        }

        return $items;
    }

}