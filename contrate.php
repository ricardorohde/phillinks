<?php
		
	$para= "philipe@phillinks.com.br";
	$assunto= "Contato vindo do Site";
	$nome=$_POST["F_Nome"];
	$telefone=$_POST["F_Telefone"];
	$email=$_POST["F_Email"];
	$servicos=$_POST["F_Servicos"];
	$veiculo=$_POST["F_Veiculo"];
	$comentarios=$_POST["F_Comentarios"];
		

	$corpo = "<strong> Mensagem vinda do Contrate </strong><br><br>";
	$corpo .= "<strong> Nome: </strong> $nome";
	$corpo .= "<br><strong> Telefone: </strong> $telefone";
	$corpo .= "<br><strong> Email: </strong> $email";
	$corpo .= "<br><strong> Servicos: </strong> $servicos";
	$corpo .= "<br><strong> Plano: </strong> $veiculo";
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