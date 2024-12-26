<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $curriculo = $_FILES["curriculo"];

    // Verifica se o arquivo foi enviado sem erros
    if ($curriculo["error"] === UPLOAD_ERR_OK) {
        $to = "ttfcarol@gmail.com"; // Seu e-mail
        $subject = "Novo Currículo: $nome";
        $message = "Você recebeu um novo currículo.\n\nNome: $nome\nE-mail: $email";
        $headers = "From: $email";

        // Lê o conteúdo do arquivo
        $file_tmp = $curriculo["tmp_name"];
        $file_name = $curriculo["name"];
        $file_data = file_get_contents($file_tmp);
        $file_base64 = chunk_split(base64_encode($file_data));

        // Define os cabeçalhos do e-mail com o anexo
        $boundary = md5(time());
        $headers .= "\r\nMIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n\r\n";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $body .= $file_base64 . "\r\n";
        $body .= "--$boundary--";

        // Envia o e-mail
        if (mail($to, $subject, $body, $headers)) {
            echo "<p class='message success'>Currículo enviado com sucesso!</p>";
        } else {
            echo "<p class='message error'>Erro ao enviar o currículo. Tente novamente.</p>";
        }
    } else {
        echo "<p class='message error'>Erro no envio do arquivo. Tente novamente.</p>";
    }
}
?>
