Yii2 SOAP Client
=========================

This extension is wrapper for default SoapClient in PHP.


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require nhkey/yii2-soap "*"
```

or add

```json
"nhkey/yii2-soap": "*"
```

to the require section of your composer.json.


Usage
-----

You need add this extension in your config file in the 'components' section
```
'components' => [
    'soapClient' => [
        'class' => \nhkey\soap\SoapClientWrapper::className(),
        'url' => '<SOAP_WSDL_URL>',
        // SoapClient options
        'options' => [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'debug' => true,
        ],
        // SopaClient headers, object or closure
        'headers' => function() {
            $headers = new stdClass();
            $headers->authDetails = new stdClass(); // This is node in SOAP Header where the login and password.
            $headers->authDetails->login = 'LOGIN';
            $headers->authDetails->password = 'PASSWORD';
            return $headers;
        }
    ],
    ...
]
```

Now you can use this extension, e.g.:
```
try {
    $soap = Yii::$app->soapClient;
    $result = $soap->makeSmb(['arg1' => 'foo', 'arg2' => 'bar']);
} catch (SoapClientWrapperException $e) {
    return ['request' => $soap->getLastRequest(), 'response' => $soap->getLastResponse()];
}

```

Credits
-------

Author: Mikhail Mikhalev

Email: mail@mikhailmikhalev.ru

