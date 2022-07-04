<?php

namespace NFSe\GeisWeb;

use NFePHP\Common\Signer;
use NFePHP\Common\Certificate;

class RPS
{
    protected $rps;
    protected $servico;
    protected $prestador;
    protected $tomador;
    protected $impostos;
    protected $xml;

    public function __construct()
    {
        $this->rps = new \stdClass();
        $this->servico = new \stdClass();
        $this->prestador = new \stdClass();
        $this->tomador = new \stdClass();
        $this->impostos = new \stdClass();
        $this->xml = new \stdClass();
    }

    public function setRPS($numero, $cod_municipio, $uf, $data = null)
    {
        $this->rps->numero        = $numero;
        $this->rps->cod_municipio = $cod_municipio;
        $this->rps->uf            = $uf;
        $this->rps->data          = $data ?? date('d/m/Y');
    }

    public function setServico($valor, $descricao, $codigo, $tipo)
    {
        $this->servico->valor     = $valor;
        $this->servico->descricao = $descricao;
        $this->servico->codigo    = $codigo;
        $this->servico->tipo      = $tipo;        
    }

    public function setPrestador($cnpj, $inscricao, $cidade, $regime)
    {
        $this->prestador->cnpj      = $cnpj;
        $this->prestador->inscricao = $inscricao;
        $this->prestador->cidade    = $cidade;
        $this->prestador->regime    = $regime;
    }

    public function setTomador($cnpj, $inscricao, $razao, $endereco, $numero, $bairro, $cidade, $estado, $cep)
    {
        $this->tomador->cnpj      = $cnpj;
        $this->tomador->inscricao = $inscricao;
        $this->tomador->razao     = $razao;
        $this->tomador->endereco  = $endereco;
        $this->tomador->numero    = $numero;
        $this->tomador->bairro    = $bairro;
        $this->tomador->cidade    = $cidade;
        $this->tomador->estado    = $estado;
        $this->tomador->cep       = $cep;
    }

    public function setImpostos($aliquota, $pis, $cofins, $csll, $irrf, $inss)
    {
        $this->impostos->aliquota = $aliquota;
        $this->impostos->pis      = $pis;
        $this->impostos->cofins   = $cofins;
        $this->impostos->csll     = $csll;
        $this->impostos->irrf     = $irrf;
        $this->impostos->inss     = $inss;
    }

    public function getXML()
    {
        $this->xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
        <EnviaLoteRps xmlns=\"http://www.geisweb.net.br/xsd/envio_lote_rps.xsd\">
            <CnpjCpf>{$this->prestador->cnpj}</CnpjCpf>
            <NumeroLote>{$this->rps->numero}</NumeroLote>
            <Rps>
                <IdentificacaoRps>
                    <NumeroRps>{$this->rps->numero}</NumeroRps>
                </IdentificacaoRps>
                <DataEmissao>{$this->rps->data}</DataEmissao>
                <Servico>
                    <Valores>
                        <ValorServicos>{$this->servico->valor}</ValorServicos>
                        <BaseCalculo>{$this->servico->valor}</BaseCalculo>
                        <Aliquota>{$this->impostos->aliquota}</Aliquota>
                    </Valores>
                    <CodigoServico>{$this->servico->codigo}</CodigoServico>
                    <TipoLancamento>{$this->servico->tipo}</TipoLancamento>
                    <Discriminacao>{$this->servico->descricao}</Discriminacao>
                    <MunicipioPrestacaoServico>{$this->prestador->cidade}</MunicipioPrestacaoServico>
                </Servico>
                <PrestadorServico>
                    <IdentificacaoPrestador>
                        <CnpjCpf>{$this->prestador->cnpj}</CnpjCpf>
                        <InscricaoMunicipal>{$this->prestador->inscricao}</InscricaoMunicipal>
                        <Regime>{$this->prestador->regime}</Regime>
                    </IdentificacaoPrestador>
                </PrestadorServico>
                <TomadorServico>
                    <IdentificacaoTomador>
                        <CnpjCpf>{$this->tomador->cnpj}</CnpjCpf>
                    </IdentificacaoTomador>
                    <RazaoSocial>{$this->tomador->razao}</RazaoSocial>
                    <Endereco>
                        <Rua>{$this->tomador->endereco}</Rua>
                        <Numero>{$this->tomador->numero}</Numero>
                        <Bairro>{$this->tomador->bairro}</Bairro>
                        <Cidade>{$this->tomador->cidade}</Cidade>
                        <Estado>{$this->tomador->estado}</Estado>
                        <Cep>{$this->tomador->cep}</Cep>
                    </Endereco>
                </TomadorServico>
                <OrgaoGerador>
                    <CodigoMunicipio>{$this->rps->cod_municipio}</CodigoMunicipio>
                    <Uf>{$this->rps->uf}</Uf>
                </OrgaoGerador>
                <OutrosImpostos>
                    <Pis>{$this->impostos->pis}</Pis>
                    <Cofins>{$this->impostos->cofins}</Cofins>
                    <Csll>{$this->impostos->csll}</Csll>
                    <Irrf>{$this->impostos->irrf}</Irrf>
                    <Inss>{$this->impostos->inss}</Inss>
                </OutrosImpostos>        
            </Rps>
        </EnviaLoteRps>
        ";

        return $this->xml;
    }

    public function getXMLSigned(Certificate $certificate)
    {

        $tagname = 'Rps'; //tag a ser assinada
        $mark = ''; //atributo de identificação da tag a ser assinada Ex. Id (OPCIONAL), em alguns casos
        $algorithm = OPENSSL_ALGO_SHA1; //algoritmo de encriptação a ser usado
        $canonical = [true, false, null, null]; //veja função C14n do PHP
        $rootname = 'Rps'; //este campo indica em qual node a assinatura deverá ser inclusa

        try {

            $xml = utf8_decode($this->getXML());
            
            return Signer::sign(
                $certificate,
                $xml,
                $tagname,
                $mark,
                $algorithm,
                $canonical,
                $rootname
            );

        } catch (\Exception $e) {
            //aqui você trata a exceção
            echo $e->getMessage();
        }

    }
}
