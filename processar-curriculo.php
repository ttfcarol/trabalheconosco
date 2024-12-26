<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletando as informações do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];

    // Informações do arquivo (currículo)
    $arquivo_tmp = $_FILES['curriculo']['tmp_name'];
    $arquivo_nome = $_FILES['curriculo']['name'];

    // Conteúdo do e-mail
    $assunto = "Currículo de $nome para $cargo";
    $mensagem = "Nome: $nome\nE-mail: $email\nCargo Desejado: $cargo\n\nCurrículo em anexo.";

    // Configurações do e-mail
    $destinatario = "ttfcarol@gmail.com"; // Substitua pelo seu e-mail
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"boundary1\"" . "\r\n";
    $headers .= "From: no-reply@seusite.com" . "\r\n"; // Remetente
    
    // Corpo do e-mail
    $body = "--boundary1\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $mensagem . "\r\n";
    $body .= "--boundary1\r\n";
    $body .= "Content-Type: application/octet-stream; name=\"$arquivo_nome\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$arquivo_nome\"\r\n\r\n";

    // Lendo o arquivo e convertendo para base64
    $file = fopen($arquivo_tmp, "rb");
    $file_data = fread($file, filesize($arquivo_tmp));
    fclose($file);
    $body .= chunk_split(base64_encode($file_data)) . "\r\n";
    $body .= "--boundary1--";

    // Enviar o e-mail
    if (mail($destinatario, $assunto, $body, $headers)) {
        echo "Currículo enviado com sucesso!";
    } else {
        echo "Erro ao enviar o currículo. Tente novamente.";
    }
}
?>
