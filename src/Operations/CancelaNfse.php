<?php

namespace NFSe\GeisWeb\Operations;

use NFePHP\Common\Certificate;

class CancelaNfse extends Operations
{

    /**
     * @param object $certificate
     * @param string $numero
     * @param string $cnpj
     * @return string|null
     */
    public function __construct(Certificate $certificate, $numero, $cnpj)
    {
        parent::__construct($certificate);
        
        $this->operation = 'CancelaNfse';

        $this->xml  = '<CancelaNfse>
                        <CnpjCpf>' . $cnpj . '</CnpjCpf>
                            <Cancela>
                                <CnpjCpfPrestador>' . $cnpj . '</CnpjCpfPrestador>
                                <NumeroNfse>' . $numero . '</NumeroNfse>
                            </Cancela>
                        </CancelaNfse>';
    }
    
    
}
