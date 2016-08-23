<?php

namespace nhkey\soap;

use SoapClient;
use SoapFault;
use yii\base\Component;

class SoapClientWrapper extends Component
{
    /**
     * @var string $url the URL of the WSDL file.
     */
    public $url;
    /**
     * @var array the array of SOAP client options.
     */
    public $options = [];

    /**
     * @var object|callable headers of the SOAP client instance
     */
    public $headers = null;

    /**
     * @var SoapClient the SOAP client instance
     */
    protected $_client;

    /**
     * @inheritdoc
     * @throws SoapClientWrapperException
     */
    public function init()
    {
        parent::init();
        if ($this->url === null) {
            throw new SoapClientWrapperException('The "url" property is empty');
        }
        try {
            $this->_client = new SoapClient($this->url, $this->options);
            if ($this->headers !== null){
                if (!is_object($this->headers))
                    throw new SoapClientWrapperException('Invalid format "header" property1');

                if (is_callable($this->headers))
                    $this->headers = call_user_func($this->headers);

                $headerName = array_keys(get_object_vars($this->headers));
                if (count($headerName) !== 1)
                    throw new SoapClientWrapperException('Invalid format "header" property2');

                $header = new \SoapHeader($this->url, $headerName[0], $this->headers->$headerName[0]);
                $this->_client->__setSoapHeaders($header);
            }

        } catch (SoapFault $e) {
            throw new SoapClientWrapperException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }


    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws SoapClientWrapperException
     */
    public function __call($name, $arguments)
    {
        try {
            return $this->_client->__soapCall($name, $arguments);
        } catch (SoapFault $e) {
            throw new SoapClientWrapperException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return string
     */
    public function getLastResponse()
    {
        return $this->_client->__getLastResponse();
    }

    /**
     * @return string
     */
    public function getLastRequest()
    {
        return $this->_client->__getLastRequest();
    }

    /**
     * @return string
     */
    public function getLastResponseHeaders()
    {
        return $this->_client->__getLastResponseHeaders();
    }

    /**
     * @return string
     */
    public function getLastRequestHeaders()
    {
        return $this->_client->__getLastRequestHeaders();
    }
}
