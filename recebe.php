<?php
		
	$para= "philipe@phillinks.com.br";
	$assunto= "Contato vindo do Site";
	$nome=$_POST["F_Nome"];
	$telefone=$_POST["F_Telefone"];
	$email=$_POST["F_Email"];
	//$servicos=$_POST["F_Novo,F_Hospedagem,F_Manutencao"];
	$veiculo=$_POST["radios"];
	$comentarios=$_POST["F_Comentarios"];
		

	$corpo = "<strong> Mensagem vinda do Site Phillinks </strong><br><br>";
	$corpo .= "<strong> Nome: </strong> $nome";
	$corpo .= "<br><strong> Telefone: </strong> $telefone";
	$corpo .= "<br><strong> Email: </strong> $email";
	$corpo .= "<br><strong> Veiculo: </strong> $veiculo";
	$corpo .= "<br><strong> Comentarios: </strong> $comentarios";
		
	$header = "Content-Type: text/html; charset= utf-8\n";
	$header .= "From: $email Reply-to: $email\n";
		
	@mail($para,$assunto,$corpo,$header);
	header("location:index.html?comentarios=enviado");

    //echo $nome."<br/>";
	//echo $telefone."<br/>";
	//echo $email."<br/>";
	//echo $servicos."<br/>";
	//echo $veiculo."<br/>";
	//echo $comentarios."<br/>";
?>