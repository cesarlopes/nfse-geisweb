<?php

namespace NFSe\GeisWeb\Soap;

use NFSe\GeisWeb\Soap\SoapNative;
use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\SoapException;
use SoapFault;

class SoapHandler
{
    /**
     * @var SoapNative
     */
    protected $soapConnection;

    /**
     * @var string
     */
    protected $soapUrl;
    
    /**
     * Constructor
     * @param Certificate $certificate
     */
    public function __construct(Certificate $certificate,$url)
    {
        $this->soapConnection = new SoapNative($certificate);
        $this->soapUrl = $url;
    }

    /**
     * Send soap message to url
     
     * @param string $operation
     * @param string $xml
     * @return string
     * @throws SoapException
     */
    public function send($operation,$xml){
        try {
            $response = $this->soapConnection->send(
                $this->soapUrl,
                $operation,
                '',
                '',
                $xml
            );
        } catch (SoapFault $e) {
            throw SoapException::soapFault($e->getMessage());
        } catch (\Exception $e) {
            throw SoapException::soapFault($e->getMessage());
        }
        return $response;
    }

    
}
