<?php

namespace NFSe\GeisWeb\Operations;

use NFSe\GeisWeb\RPS;
use NFePHP\Common\Certificate;

class EnviaLoteRps extends Operations
{
    
    protected $response;
    
    /**
     * @param object $certificate
     * @param object $rps     
     */
    public function __construct(Certificate $certificate, RPS $rps)
    {
        parent::__construct($certificate);

        $this->operation = 'EnviaLoteRps';

        $this->xml  = $rps->getXMLSigned($certificate);
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
    
    public function getNumeroNFSe(){
        return $this->response->Nfse->IdentificacaoNfse->NumeroNfse;
        
    }

    public function getCodigoVerificacao(){
        return $this->response->Nfse->IdentificacaoNfse->CodigoVerificacao;
    }

    public function getLinkConsulta(){
        return $this->response->Nfse->LinkConsulta;
    }

    public function getError(){
        return $this->response->Msg->Status;
    }
}
