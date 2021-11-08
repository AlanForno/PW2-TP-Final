<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

class Mailer {

    public function enviarMail(){
        echo "AAAAAAAAAAAAAAAAAAAAAAAAAA";
    }

    public function enviarEmail($data){
        $mailOrigen = "gauchorockettest@gmail.com";
        $password = "11gauchorocket";

        //Crear una instancia de PHPMailer
        $mail = new PHPMailer();
        //Definir que vamos a usar SMTP
        $mail->IsSMTP();
        //Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
        // 0 = off (producción)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug  = 0;
        //Ahora definimos gmail como servidor que aloja nuestro SMTP
        $mail->Host       = 'smtp.gmail.com';
        //El puerto será el 587 ya que usamos encriptación TLS
        $mail->Port       = 587;
        //Definmos la seguridad como TLS
        $mail->SMTPSecure = 'tls';
        //Tenemos que usar gmail autenticados, así que esto a TRUE
        $mail->SMTPAuth   = true;
        //Definimos la cuenta que vamos a usar. Dirección completa de la misma
        $mail->Username   = $mailOrigen;
        //Introducimos nuestra contraseña de gmail
        $mail->Password   = $password;
        //Definimos el remitente (dirección y, opcionalmente, nombre)
        $mail->SetFrom($data[0]["email"], 'Gaucho Rocket');
        //Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
        $mail->AddAddress('gauchorockettest@gmail.com', 'El Destinatario');
        //Definimos el tema del email
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Validacion y resultado de su turno medico";
        //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
        //$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
        //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
        $mail->Body = 'Buenos dias, '.$data[0]["usuario"].'<br>Su turno es esta programado para el dia '. $data[0]['fecha'] .'<br> En el hospital: '.$data[0]['hospital']. '<br> Su resultado es: '.$data[0]['resultado'];
        $mail->AltBody = 'This is a plain-text message body';
        //Enviamos el correo
        if(!$mail->Send()) {
        echo "Error: " . $mail->ErrorInfo;
        } else {
        echo "Email enviado!";
        }

            }



}