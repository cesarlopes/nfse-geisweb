<?php

namespace NFSe\GeisWeb\Operations;

use NFePHP\Common\Certificate;

class EmailNFSeTomador extends Operations
{

    /**
     * @param object $certificate
     * @param string $numero
     * @param string $cnpj
     * @return string|null
     */
    public function __construct(Certificate $certificate, $emails, $numero, $cnpj)
    {
        parent::__construct($certificate);
        
        $this->operation = 'EmailNFSeTomador';

        $this->xml  = '<EmailNFSeTomador>
                        <CnpjCpf>'.$cnpj.'</CnpjCpf>
                            <Envia>
                                <NumeroNfse>'.$numero.'</NumeroNfse>
                                <EmailTomador>'.$emails.'</EmailTomador>
                            </Envia>
                        </EmailNFSeTomador>';
    }
    
    
}
