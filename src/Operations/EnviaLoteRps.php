<?php

namespace NFSe\GeisWeb\Operations;

use NFSe\GeisWeb\RPS;
use NFePHP\Common\Certificate;

class EnviaLoteRps extends Operations
{    
    
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
