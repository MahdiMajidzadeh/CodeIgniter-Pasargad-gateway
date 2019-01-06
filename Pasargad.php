<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * CodeIgniter ZarinPal getway library
 *
 * @author              Mahdi Majidzadeh (http://majidzadeh.ir)
 * @license             GNU Public License 2.0
 * @package             ZarinPal
 */

if (!class_exists('nusoap_client')) {
    require_once 'nusoap/nusoap.php';
}

class Zarinpal
{
    private $merchant;
    private $terminal;
    private $amount;
    private $call_back;
    private $invoice_number;
    private $timestamp;
    private $invoice_date;
    private $data;

    public function request($merchant, $terminal, $amount, $call_back, $invoice_number, $timestamp)
    {
        require_once 'RSAProcessor.class.php';
        $processor = new RSAProcessor('certificate.xml', RSAKeyType::XMLFile);

        $this->merchant = $merchant;
        $this->terminal = $terminal;
        $this->amount = $amount;
        $this->call_back = $call_back;
        $this->invoiceNumber = $invoice_number;
        $action = 1003;
        $this->timestamp = $timestamp;
        $this->invoice_date = $timestamp;

        $data = '#'.$merchant.'#'.$terminal.'#'.$invoice_number.'#'.$timestamp.'#'.$amount.'#'.$call_back.'#'.$action.'#'.$timestamp.'#';
        $data = sha1($data, true);
        $data = $processor->sign($data);
        $this->data = base64_encode($data);
    }

    public function redirect_form()
    {
        return <<<EOT
<!DOCTYPE>
<html>
  <head>
  </head>
  <body>
<form id='form' method='post' name="form" action='https://pep.shaparak.ir/gateway.aspx'>
	<input type='text' name='invoiceNumber' value='$this->invoice_number' /><br />
	<input type='text' name='invoiceDate' value='$this->invoice_date' /><br />
	<input type='text' name='amount' value='$this->iamount' /><br />
	<input type='text' name='terminalCode' value='$this->terminal' /><br />
	<input type='text' name='merchantCode' value='$this->merchant' /><br />
	<input type='text' name='redirectAddress' value='$this->call_back' /><br />
	<input type='text' name='timeStamp' value='$this->timeSstamp ' /><br />
	<input type='text' name='action' value='1003' /><br />
	<input type='text' name='sign' value='$this->data' /><br />
</form>
<script>
window.onload = function(){
  document.forms['form'].submit();
}
</script>
  </body>
</html>

EOT;
    }
}
