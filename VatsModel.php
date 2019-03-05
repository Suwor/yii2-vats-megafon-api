<?php
namespace suwor\VatsMegafonApi;

use yii\base\Model;

class VatsModel extends Model {

    const SCENARIO_HISTORY = 'history';
    const SCENARIO_EVENT   = 'event';
    const SCENARIO_CONTACT = 'contact';

    public $cmd;
    public $type;
    public $user;
    public $ext;
    public $groupRealName;
    public $telnum;
    public $phone;
    public $diversion;
    public $start;
    public $duration;
    public $callid;
    public $link;
    public $crm_token;
    public $status;

    public $typeLabel;
    public $statusLabel;

    public function scenarios()
    {
        return [
            self::SCENARIO_HISTORY => ['cmd', 'type', 'user', 'ext', 'groupRealName', 'telnum', 'phone', 'diversion', 'start', 'duration', 'callid', 'link', 'crm_token', 'status', 'typeLabel', 'statusLabel'],
            self::SCENARIO_EVENT =>   ['cmd', 'type', 'phone', 'diversion', 'user', 'groupRealName', 'ext', 'telnum', 'callid', 'crm_token', 'typeLabel'],
            self::SCENARIO_CONTACT => ['cmd', 'phone', 'crm_token', 'callid'],
        ];
    }

    public function rules()
    {
        return [
            [['cmd', 'type', 'user', 'ext', 'groupRealName', 'telnum', 'phone', 'diversion', 'start', 'callid', 'link', 'status', 'typeLabel', 'statusLabel'], 'string', 'max' => 1000],
            [['cmd', 'type', 'user', 'ext', 'groupRealName', 'telnum', 'phone', 'diversion', 'start', 'callid', 'link', 'status', 'typeLabel', 'statusLabel'], 'trim'],
            [['cmd', 'type', 'user', 'ext', 'groupRealName', 'telnum', 'phone', 'diversion', 'start', 'callid', 'link', 'status', 'typeLabel', 'statusLabel'], 'filter', 'filter' => 'strip_tags'],
            ['duration', 'number'],

            [['cmd', 'type', 'user', 'phone', 'start', 'duration', 'callid', 'crm_token', 'status'], 'required', 'on' => self::SCENARIO_HISTORY],
            [['cmd', 'type', 'user', 'phone', 'callid', 'crm_token'], 'required', 'on' => self::SCENARIO_EVENT],
            [['cmd', 'phone', 'callid', 'crm_token'], 'required', 'on' => self::SCENARIO_CONTACT],

            ['typeLabel', 'default', 'value' => function ($model) {
                switch(strtolower($model->type)){
                    case 'in': return 'входящий звонок';
                    case 'out': return 'исходящий звонок';
                    default: return '';
                }
            }, 'on' => self::SCENARIO_HISTORY],
            ['typeLabel', 'default', 'value' => function ($model) {
                switch(strtolower($model->type)){
                    case 'incoming': return 'пришел входящий звонок';
                    case 'accepted': return 'звонок успешно принят';
                    case 'completed': return 'звонок успешно завершен';
                    case 'cancelled': return 'звонок сброшен';
                    case 'outgoing': return 'менеджерсовершает исходящий звонок';
                    default: return '';
                }
            }, 'on' => self::SCENARIO_EVENT],
            ['statusLabel', 'default', 'value' => function ($model) {
                switch(strtolower($model->status)){
                    case 'success': return 'успешный звонок';
                    case 'missed': return 'пропущенный входящий звонок';
                    case 'busy': return 'мы получили ответ Занято';
                    case 'notavailable': return 'мы получили ответ Абонент недоступен';
                    case 'notallowed': return 'мы получили ответ Звонки на это направление запрещены';
                    default: return '';
                }
            }, 'on' => self::SCENARIO_HISTORY],
        ];
    }

}