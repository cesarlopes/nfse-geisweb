<?php

namespace NFSe\GeisWeb\Operations;

class ConsultaNfse extends Operations
{
    /**
     * @param string $numero
     * @param string $cnpj
     * @return string|null
     */
    public function __construct($numero, $cnpj)
    {
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
