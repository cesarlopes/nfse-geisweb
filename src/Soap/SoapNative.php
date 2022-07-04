<?php

/**
 * SoapClient based in native PHP SoapClient class
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Soap\SoapNative
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-common for the canonical source repository
 */

namespace NFSe\GeisWeb\Soap;

use NFePHP\Common\Soap\SoapClientExtended;
use NFePHP\Common\Soap\SoapBase;
use NFePHP\Common\Soap\SoapInterface;
use NFePHP\Common\Exception\SoapException;
use Exception;
use SoapHeader;
use SoapFault;
use NFePHP\Common\Certificate;

class SoapNative extends SoapBase implements SoapInterface
{
    /**
     * @var SoapClientExtended
     */
    protected $connection;

    /**
     * Constructor
     * @param Certificate $certificate
     */
    public function __construct(Certificate $certificate = null)
    {
        parent::__construct($certificate);
    }

    /**
     * Send soap message to url
     * @param string $url
     * @param string $operation
     * @param string $action
     * @param int $soapver
     * @param array $parameters
     * @param array $namespaces
     * @param string $request
     * @param \SoapHeader $soapheader
     * @return string
     * @throws SoapException
     */
    public function send(
        $url,
        $operation = '',
        $action = '',
        $soapver = SOAP_1_2,
        $parameters = [],
        $namespaces = [],
        $request = '',
        $soapheader = null
    ) {
        $this->prepare($url, $soapver);
        try {
            if (!empty($soapheader)) {
                $this->connection->__setSoapHeaders(array($soapheader));
            }
            $responseXML = $this->connection->$operation($parameters);
            $this->requestHead = $this->connection->__getLastRequestHeaders();
            $this->requestBody = $this->connection->__getLastRequest();
            $this->responseHead = $this->connection->__getLastResponseHeaders();
            $this->responseBody = $this->connection->__getLastResponse();
            $this->saveDebugFiles(
                $operation,
                $this->requestHead . "\n" . $this->requestBody,
                $this->responseHead . "\n" . $this->responseBody
            );
        } catch (SoapFault $e) {
            throw SoapException::soapFault("[$url] " . $e->getMessage());
        } catch (\Exception $e) {
            throw SoapException::soapFault("[$url] " . $e->getMessage());
        }
        return $responseXML;
    }

    /**
     * Prepare connection
     * @param string $url
     * @param int $soapver
     * @throws SoapException
     */
    protected function prepare($url, $soapver = SOAP_1_2)
    {
        $wsdl = "$url?wsdl";
        $verifypeer = true;
        $verifyhost = true;
        if ($this->disablesec) {
            $verifypeer = false;
            $verifyhost = false;
        }
        $this->saveTemporarilyKeyFiles();
        $params = [
            'local_cert' => $this->tempdir . $this->certfile,
            'connection_timeout' => $this->soaptimeout,
            'encoding' => 'UTF-8',
            'verifypeer' => $verifypeer,
            'verifyhost' => $verifyhost,
            'soap_version' => $soapver,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];
        if (!empty($this->temppass)) {
            $params['passphrase'] = $this->temppass;
        }
        try {
            $this->connection = new SoapClientExtended($wsdl, $params);
        } catch (SoapFault $e) {
            throw SoapException::soapFault($e->getMessage());
        } catch (\Exception $e) {
            throw SoapException::soapFault($e->getMessage());
        }
    }

    
}
