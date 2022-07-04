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

    /** @var object */
    protected $response;

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

    /**
     * @return boolean
     */
    public function send()
    {
        try {

            $responseXML = $this->soap->send($this->operation, $this->xml);

            $responseXML = $this->addCDATA($responseXML);

            $object = simplexml_load_string($responseXML, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($object !== false) {

                $this->response = $object;

                if (isset($object->Msg->Erro)) {
                    return false;
                }               

                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getResponse(){
        return $this->response;
    }
}
