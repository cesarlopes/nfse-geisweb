<?php

namespace NFSe\GeisWeb\Operations;

use NFePHP\Common\Certificate;

class ConsultaNfse extends Operations
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
        
        $this->operation = 'ConsultaNfse';

        $this->xml  = ' <ConsultaNfse>
                        <CnpjCpf>' . $cnpj . '</CnpjCpf>
                        <Consulta>
                            <CnpjCpfPrestador>' . $cnpj . '</CnpjCpfPrestador>
                            <NumeroNfse>' . $numero . '</NumeroNfse>
                            <MultiplasNfse></MultiplasNfse>
                            <DtInicial></DtInicial>
                            <DtFinal></DtFinal>
                            <NumeroInicial></NumeroInicial>
                            <NumeroFinal></NumeroFinal>
                            <Pagina>1</Pagina>
                        </Consulta>
                    </ConsultaNfse>';
    }
    
    
}
