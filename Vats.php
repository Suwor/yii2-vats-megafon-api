<?php

namespace suwor\VatsMegafonApi;

use yii\httpclient\Client;
use yii\web\HttpException;

class Vats extends \yii\base\Component
{
    public $apiUrl;
    public $crmToken;
    public $token;

    /**
     * Отправка запроса к API
     *
     * @param $data
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function send($data)
    {
        $data['token'] = $this->token;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->apiUrl)
            ->setData($data)
            ->send();
        if ($response->isOk) {
            return $response->data;
        } else {
            throw new HttpException(500, $response->data['error']);
        }
    }

    /**
     * Обработка данных, полученных от API
     *
     * @return array
     * @throws HttpException
     */
    public function process()
    {
        $data = \Yii::$app->request->post();

        if($data['crm_token'] !== $this->crmToken) { throw new HttpException(401, '{ error: "Invalid token" }'); }

        $model = new VatsModel();

        switch($data['cmd']){
            case VatsModel::SCENARIO_HISTORY: $model->scenario = VatsModel::SCENARIO_HISTORY;
                break;
            case VatsModel::SCENARIO_EVENT:   $model->scenario = VatsModel::SCENARIO_EVENT;
                break;
            case VatsModel::SCENARIO_CONTACT: $model->scenario = VatsModel::SCENARIO_CONTACT;
                break;
            default: throw new HttpException(400, '{ error: "Invalid parameters" }');
        }

        if ($model->load($data, '') && $model->validate()) {
            return $model->toArray($model->safeAttributes());
        } else {
            throw new HttpException(400, '{ error: "Invalid parameters" }');
        }
    }

}
