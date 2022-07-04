<?php

namespace NFSe\GeisWeb\Operations;

use NFSe\GeisWeb\Soap\SoapHandler;
use NFePHP\Common\Certificate;

class Operations
{
    /** @var string */
    protected $operation;

    /** @var string */
    protected $xml;

    /** @var object */
    protected $soap;

    /** @var object */
    protected $certificate;

    /**
     * Operations constructor.
     * @param object $certificate
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;

        $this->soap = new SoapHandler(
            $certificate,
            'https://geisweb.net.br/homologacao/modelo/webservice/GeisWebServiceImpl.php'
        );
    }    

    public function addCDATA($xml)
    {
        $xml = str_replace('<LinkConsulta>', '<LinkConsulta><![CDATA[', $xml);
        $xml = str_replace('</LinkConsulta>', ']]></LinkConsulta>', $xml);
        return $xml;
    }
    

    public function get()
    {
        try {
            $soap = new SoapNative($this->certificate);
            $this->response = $soap->send(
                $this->url,
                $this->function,
                '',
                '',
                $this->xml
            );
            return $this;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
