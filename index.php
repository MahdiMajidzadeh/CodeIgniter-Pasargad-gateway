<?php 
require_once("RSAProcessor.class.php"); 

$processor = new RSAProcessor("certificate.xml",RSAKeyType::XMLFile);
$merchantCode = 111111; // كد پذيرنده
$terminalCode = 111111; // كد ترمينال
$amount = 1; // مبلغ فاكتور
$redirectAddress = "http://???????/PHPSample/getresult.php"; 

$invoiceNumber = 16525; //شماره فاكتور
$timeStamp = date("Y/m/d H:i:s");
$invoiceDate = date("Y/m/d H:i:s"); //تاريخ فاكتور
$action = "1003"; 	// 1003 : براي درخواست خريد 
$data = "#". $merchantCode ."#". $terminalCode ."#". $invoiceNumber ."#". $invoiceDate ."#". $amount ."#". $redirectAddress ."#". $action ."#". $timeStamp ."#";
$data = sha1($data,true);
$data =  $processor->sign($data); // امضاي ديجيتال 
$result =  base64_encode($data); // base64_encode 
?>


<!DOCTYPE>
<html>
  <head>
  </head>
  <body>

<form id='form2' Method='post' name="form2" Action='https://pep.shaparak.ir/gateway.aspx'>
	invoiceNumber<input type='text' name='invoiceNumber' value='<?= $invoiceNumber ?>' /><br />
	invoiceDate<input type='text' name='invoiceDate' value='<?= $invoiceDate ?>' /><br />
	amount<input type='text' name='amount' value='<?= $amount ?>' /><br />
	terminalCode<input type='text' name='terminalCode' value='<?= $terminalCode ?>' /><br />
	merchantCode<input type='text' name='merchantCode' value='<?= $merchantCode ?>' /><br />
	redirectAddress<input type='text' name='redirectAddress' value='<?= $redirectAddress ?>' /><br />
	timeStamp<input type='text' name='timeStamp' value='<?= $timeStamp ?>' /><br />
	action<input type='text' name='action' value='<?= $action ?>' /><br />
	sign<input type='text' name='sign' value='<?= $result ?>' /><br />
</form>
<script>
window.onload = function(){
  document.forms['form2'].submit();
}
</script>
  </body>
</html>
