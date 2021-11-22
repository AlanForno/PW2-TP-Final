<?php
use PHPMailer\PHPMailer\PHPMailer;


class Mailer {

    public function __construct()
    {
        require 'phpmailer/Exception.php';
        require 'phpmailer/PHPMailer.php';
        require 'phpmailer/SMTP.php';

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
        $mail->Username   = "gauchorockettest@gmail.com";
        //Introducimos nuestra contraseña de gmail
        $mail->Password   = "11gauchorocket";
        //Definimos el remitente (dirección y, opcionalmente, nombre)
        $mail->SetFrom('gauchorockettest@gmail.com', 'Gaucho Rocket');
        
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->AltBody = 'Gauchorocket';
        $this->mail = $mail;
    }



    public function EnviarMail ($emailUsuario, $asunto, $mensaje, $nombreUsuario){

        $this->mail->AddAddress($emailUsuario, $nombreUsuario);
        //Definimos el tema del email
        
        $this->mail->Subject = $asunto;
        //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
        //$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
        //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
        $this->mail->Body = $mensaje;

        //Enviamos el correo
        //$this->mail->Send();
        if(!$this->mail->Send()) {
            if(isset($_SESSION["debug"])){
                echo "Ocurrio un error";
            }
        }
        
    }
    public function EnviarMailConArchivo ($emailUsuario, $asunto, $mensaje, $nombreUsuario, $attachment, $nombreArchivo){

        $this->mail->AddAddress($emailUsuario, $nombreUsuario);        
        $this->mail->Subject = $asunto;
        $this->mail->Body = $mensaje;

        $this->mail->addStringAttachment($attachment,$nombreArchivo);
        
        if(!$this->mail->Send()) {
            if(isset($_SESSION["debug"])){
                echo "Ocurrio un error";
            }
        }
        
    }


}