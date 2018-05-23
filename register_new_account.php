<?php
if (($_SERVER['REQUEST_METHOD'] != 'POST') || empty($_POST)) {
   echo '<meta http-equiv="refresh" content="0; url=./">';
}
else{
    header('Content-Type: text/html; charset=utf-8');
    require_once('./libs/PHPMailerAutoload.php');
    $mail = new PHPMailer(); // new mail's object


    //configuração do gmail
    $mail->Port = '587'; 
    $mail->Host = 'secret'; 
    $mail->Mailer = 'smtp'; 
    $mail->SMTPSecure = 'tls';

    $mail->SMTPAuth = true; 
    $mail->Username = 'secret';
    $mail->Password = 'secret'; 
    $mail->AddEmbeddedImage('secret');
    $mail->SingleTo = true; 
    $mail->CharSet = 'UTF-8';
    // configuração do email a ver enviado.
    $mail->From = "secret"; 
    $mail->FromName = "TrainEnergy - Store"; 


    //Configuração final

    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $nascimento_dia = $_REQUEST['nascimento-dia'];
    $nascimento_mes = $_REQUEST['nascimento-mes'];
    $nascimento_ano = $_REQUEST['nascimento-ano'];
    $user = $_REQUEST["register-user"];
    
    $date = date("Y-m-d",strtotime(str_replace('/','-', $nascimento_dia."/".$nascimento_mes."/".$nascimento_ano)));

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $code = '';
    $final_code = array();
    $string_code = '';

    for($i=0; $i < 14; $i++){
        $code = $characters[rand(0, $charactersLength - 1)];
        switch($i){
            case 4: array_push($final_code, "-");break;
            case 9: array_push($final_code, "-");break;
            default: array_push($final_code, $code);
        }
    }
    $string_code = '';
    for($i = 0; $i<=sizeof($final_code); $i++){
        $string_code .= $final_code[$i];
    }

    $string_code = str_replace(' ', '', $string_code);

    //---------
    $mail->addAddress($email);  

    $output = '';

    $output .= '
        <div style="text-align: center; background-color: #4A5F75; padding: 20px; min-height: 100vh;">
        <div style="text-align: center; font-size: 18px; color: #fff;">    
            <img src="cid:logo" width="200" height="200" class="d-inline-block" alt="" />
            <br />
            <h3>TrainEnergy - Store</h3>
            <p>Seja bem vindo ao nosso site, <b style="color: gold;">'.$user.'</b>.</p>
            <hr style="width: 45%;">
            <small>
                Para começar a usar os nossos serviços ative a sua conta clicando no botão a baixo:
            </small>
            <br />
            <br />
            <br />
            <small>Código de ativação: <b style="color: gold;">'.$string_code.'</b></small> <br /><br /><br />
            <a href="secret/?p=ac&email='.$email.'" style="background-color: #FF6400; color: #fff; padding: 20px; border-radius: 5px; border: px solid #fff;">
                Ativar a conta
            </a>
            <br />
            <br />
            <br />
            <br />
        </div>
        <small>Este email foi enviado atráves do nosso sistema, em caso de dúvidas faça um novo assunto para este contacto. Entre 24 a 48 horas o seu processo será aberto.</small>
        <br />
        <h6>Prova de aptidão profissional - Bruno Martins 3P2 ©</h6>
        <br />
        </div>  
    ';

    $mail->Subject = "Ativação necessária da conta: ".$email; 
    $mail->Body = $output;
    $mail->IsHTML(true); 

    $data_criacao_conta = date("Y/m/d");
         $data_criacao_conta = date("Y-m-d",strtotime(str_replace('/','-', $data_criacao_conta)));

try{
    $connect = $pdo->prepare("secret");
               
    if($connect->execute()){
        $mail->Send();
        echo '<meta http-equiv="refresh" content="0; url=./?p=ac&email='.$email.'">';
    }  
}
catch(Exception $ex){
    echo $ex->getMessage();
}         
}
?>
