<?php
/*echo "<pre>";
print_r($_POST);
echo"</pre>"; */

//importar arquivos da biblioteca PHPMailer

require "./PHPMailer/Exception.php";
require "./PHPMailer/OAuth.php";
require "./PHPMailer/PHPMailer.php";
require "./PHPMailer/POP3.php";
require "./PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//cria classe com os atributos
class Mensagem {
	private $para = null;
	private $assunto = null;
	private $mensagem = null;
	public $status = array('codigo_status' => null, 'descricao_status' => '');

//cria metodos publicos set e get
	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
	}

	public function mensagemValida(){
		//empty é funcao nativa para verificar se campo ta preenchido. se tiver vazio retorna false, se nao, true
		if (empty($this->para) || empty($this->assunto) || empty($this->mensagem) ) {
			return false;
		}
		return true;
	}
}

//cria a variavel e atribui instancia atraves do new
$mensagem = new Mensagem();

//setar as classes puxando valores da super global post com os names dados la

$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

//print pra verificar na tela as informacoes
//print_r($mensagem);

//recupera a variavel e da o comando da funcao criando condicao
if (!$mensagem->mensagemValida()) {
	echo "Mensagem não é válida";
	//die é funcao nativa que interrompe tudo se cair nessa condicao
	//die();
	header('Location: index.php'); //dessa forma, mesmo que tentem acessar de maneira direta a pagina processa_envio, vai ser redirecionado para a index
} 
 
 	//caso contrario a resposta é valida e damos andamento. o codigo a seguir foi copiado da biblioteca






	$mail = new PHPMailer(true);
	try{

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // SMTP SERVER DO GMAIL NO CASO 
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'curso.web.ju@gmail.com';// USUARIO DO EMAIL
$mail->Password = '0101web.';// SENHA DO EMAIL
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('curso.web.ju@gmail.com', 'email teste rementente'); //SETA O REMETENTE
$mail->addAddress($mensagem->__get('para'));// SETA O DESTINATARIO COM A VARIAVEL QUE CRIAMOS

// $mail->addReplyTo('info@example.com', 'Information'); //USA QUANDO QUER CONFIGURAR PARA AO RESPONDER UM EMAIL IR PARA UMA PESSOA AUTOMATICAMENTE
// $mail->addCC('cc@example.com'); //PARA CÓPIA
// $mail->addBCC('bcc@example.com'); // PARA CÓPIA OCULTA

//$mail->addAttachment('/var/tmp/file.tar.gz');// PARA ADICIONAR ANEXOS
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');// PARA ADICIONAR ANEXOS

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $mensagem->__get('assunto'); //SETA O assunto COM A VARIAVEL QUE CRIAMOS
$mail->Body    = $mensagem->__get('mensagem'); //SETA O mensagem COM A VARIAVEL QUE CRIAMOS
$mail->AltBody = 'é necessario utilizar um client que suporte HTML para ter acesso total a conteudo dessa mensagem';//alguns emails nao aceitam conteudo html, sao poucos

$mail->send();
    
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';

  //confirmaca de email enviado

}catch(Exception $e) {
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Não foi possível enviar este email. Por favor tente mais tarde'. $mail->ErrorInfo;
}
?>



<html> 
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

	<body>
		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">
					<? if ($mensagem->status['codigo_status'] == 1) { ?>
						
						<div class="container">
							<h1 class="display-4 text-success"> Sucesso </h1>
							<p> <?= $mensagem->status['descricao_status'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white"> Voltar </a>
							
						</div>
					<? } ?>

					<? if ($mensagem->status['codigo_status'] == 2) { ?>
							<div class="container">
							<h1 class="display-4 text-danger"> Ops! </h1>
							<p> <?= $mensagem->status['descricao_status'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white"> Voltar </a>
							
						</div>
					<? } ?>

				</div>
			</div>
			
		</div>
	</body>
</html>