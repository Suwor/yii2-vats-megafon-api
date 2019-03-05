REST API Виртуальной АТС МегаФон
================================
Расширение для упрощения интеграции с API Виртуальной АТС МегаФон

## Установка расширения

Для установки расширения используйте Composer. Запустите в консоли

```
php composer.phar require --prefer-dist suwor/yii2-vats-megafon-api "*"
```

или добавьте

```
"suwor/yii2-vats-megafon-api": "*"
```

в `require` секцию вашего composer.json.


## Примеры использования


[[\yii\httpclient\Vats]] расширяет [[\yii\base\Component]] и, таким образом, его можно настроить на уровне [[\yii\di\Container]]: 
в качестве компонента приложения. Например:

```php
return [
    // ...
    'components' => [
        // ...
        'vats' => [
            'class' => 'suwor\VatsMegafonApi\Vats',
            'apiUrl' => 'https://domain/sys/crm_api.wcgp', // Адрес Облачной АТС
            'crmToken' => 'xxx-xxx', // Ключ для авторизации в вашей CRM
            'token' => 'xxx-xxx', // Ключ для авторизации в Облачной АТС
        ],
    ],
];
    
// ...
// Инициализация исходящего звонка
    
$data = Yii::$app->vats->send(['cmd' => 'makeCall', 'phone' => '+79999999999', 'user' => 'login_in_ats']);
    
// $data - возвращенные API данные
```

Другой вариант использования
```php
use suwor\VatsMegafonApi\Vats;
    
// ...
    
public function actionApiConnection()
{
    $vats = new Vats();
    $vats->apiUrl = 'https://domain/sys/crm_api.wcgp'; // Адрес Облачной АТС
    $vats->crmToken = 'xxx-xxx'; // Ключ для авторизации в вашей CRM
    
    // Получение и обработка данных от API, переданных методом POST
    $data = $vats->process();
    
    // ... Работа с данными
}
```
    
Подробнее: https://vats.megafon.ru/rest_api