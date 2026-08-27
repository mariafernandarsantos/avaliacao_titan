<?php

namespace app\core;

class Mailer
{
    private string $fromAddress = 'noreply@jminformatica.com';
    private string $fromName    = 'JM Informática';

    /**
     * Notifica o usuário que seu serviço foi finalizado
     *
     * @param array $user    Dados do usuário 
     * @param array $service Dados do serviço 
     */
    public function sendServiceFinalized(array $user, array $service): bool
    {
        $to      = $user['email'];
        $subject = '=?UTF-8?B?' . base64_encode('Serviço Finalizado') . '?=';

        $preco    = number_format((float) $service['price'],           2, ',', '.');
        $comissao = number_format((float) $service['commission_user'], 2, ',', '.');
        $dataFim  = date('d/m/Y H:i', strtotime($service['finished_at']));

        $corpo  = "Olá, {$user['name']}!\n\n";
        $corpo .= "Gostaríamos de informar que o seguinte serviço foi concluído:\n\n";
        $corpo .= "  Descrição  : {$service['description']}\n";
        $corpo .= "  Valor      : R$ {$preco}\n";
        $corpo .= "  Comissão   : R$ {$comissao}\n";
        $corpo .= "  Finalizado : {$dataFim}\n\n";
        $corpo .= "Obrigado pelo seu trabalho!\n";
        $corpo .= "JM Informática";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "From: {$this->fromName} <{$this->fromAddress}>\r\n";
        $headers .= "Reply-To: {$this->fromAddress}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $resultado = mail($to, $subject, $corpo, $headers);

        if (!$resultado) {
            error_log("[Mailer] Falha ao enviar e-mail para {$to}");
        }

        // Log do email
        $logFile = APP_PATH . '/../emails_enviados.log';
        $logContent = "=================================================\n";
        $logContent .= "DATA: " . date('Y-m-d H:i:s') . "\n";
        $logContent .= "PARA: {$to}\n";
        $logContent .= "ASSUNTO: Serviço Finalizado\n";
        $logContent .= "CORPO:\n{$corpo}\n";
        $logContent .= "=================================================\n\n";
        file_put_contents($logFile, $logContent, FILE_APPEND);

        return $resultado;
    }
}