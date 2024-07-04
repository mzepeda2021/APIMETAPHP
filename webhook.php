<?php
const TOKEN_TlaquepaqueEscolar = "TLAQUEPAQUEESCOLARPHPAPIMETA";
const WEBHOOK_URL = "https://tlaquepaqueescolar.com.mx/webhook.php";

// Verificar token para la suscripción del webhook
function verificarToken($req, $res) {
    try {
        $token = $req['hub_verify_token'];
        $challenge = $req['hub_challenge'];

        if (isset($challenge) && isset($token) && $token == TOKEN_TlaquepaqueEscolar) {
            $res->send($challenge);
        } else {
            $res->status(400)->send();
        }
    } catch (Exception $e) {
        $res->status(400)->send();
    }
}

// Función para recibir mensajes
function recibirMensajes($req, $res) {
    try {
        $entry = $req['entry'][0];
        $changes = $entry['changes'][0];
        $value = $changes['value'];
        $objetomensaje = $value['messages'];
        $mensaje = $objetomensaje[0];

        $comentario = $mensaje['text']['body'];
        $numero = $mensaje['from'];
        $numero = preg_replace('/1/', '', $numero, 1);  // Eliminar el primer '1' del número

        EnviarMensajeWhatsapp($comentario, $numero);

        $archivo = fopen("log.txt", "a");
        $texto = json_encode($numero);
        fwrite($archivo, $texto);
        fclose($archivo);

        $res->send("EVENT_RECEIVED");
    } catch (Exception $e) {
        $res->send("EVENT_RECEIVED");
    }
}


// Función para enviar mensajes a través de WhatsApp usando cURL
function EnviarMensajeWhatsapp($comentario, $numero) {
    $comentario = strtolower($comentario);

    if (strpos($comentario, 'hola') !== false) {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "Hola! Gracias por comunicarte con Tlaquepaque Escolar, Mi nombre es Viridiana, ¿Como puedo ayudarte?"
            ]
        ]);
    }else if ($comentario=='1') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "🚀Hola! has seleccionado cotizacion de lista escolar, favor de indicar lo siguiente: \n\n📌Articulos y cantidad (puede enviar foto de la lista, legible)\n\n📌Fecha maxima en la cual necesita su lista"
            ]
        ]);
    }else if ($comentario=='2') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "location",
            "location" => [
               "latitude" => "20.641704861488485", 
               "longitude" => "-103.31039366203734",
               "name" => "Tlaquepaque Escolar Tienda de mostrador",
               "address" => "Tlaquepaque, Centro"
            ]
        ]);
     } else if ($comentario=='3') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "🚀Hola! Para cotizar para servicio a oficinas te invitamos a contactarnos atravez de la linea:\n\n33-35-78-83-09\n\nDonde uno de nuestros especialistas estara encantado de atenderte."
            ]
        ]);
     } else if ($comentario=='4') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "🚀Hola! Para cotizar para servicio a mayoristas te invitamos a contactarnos atravez de la linea:\n\n33-31-90-88-06\n\nDonde uno de nuestros especialistas estara encantado de atenderte."
            ]
        ]);
     } else if ($comentario=='5') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "🚀Hola! Estas en la seccion de quejas y sugerencias, para levantar alguna incidencia relacionada con Tlaquepaque Escolar te recomendamos lo siguiente:\n\n Si es problema con mercancia, te sugerimos tener tu folio de compra a la mano\n\nSi el problema va orientado a la calidad de la atencion te pedimos describas tu problema en un mensaje, nos proporciones tu nombre y horario en el cual se te puede contactar para dar seguimiento a tu caso."
            ]
        ]);
     } else if ($comentario=='6') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "🚀Hola! Has seleccionado hablar con un asesor\nEn este momento todos nuestros asesores estan ocupados, pero te atenderan a la brevedad posible.\nTe sugerimos dejar un detalle de tu necesidad para que sea mas pronta la atención."
            ]
        ]);
     } else if ($comentario=='7') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "📅 Horarios de atencion:\n\nTienda de mostrador: \n\n🕜Lunes a Viernes de 9 Am a 6:30 Pm \n\n🕜Sabados de 9 Am a 2 Pm."
            ]
        ]);
    } else if (strpos($comentario, 'gracias') !== false) {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => "Gracias a ti por contactarme."
            )
        ]);
    } else if (strpos($comentario, 'adios') !== false || strpos($comentario, 'bye') !==false || strpos($comentario, 'nos vemos') !==false || strpos($comentario, 'hasta luego')!==false) {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => "Hasta luego."
            )
        ]);
     } else {
        $data = json_encode([            
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $numero,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body"=> "🚀 Hola, visita nuestro sitio web www.tlaquepaqueescolar.com.mx para más información.\n \n📌Por favor, ingresa un número #️⃣ para recibir información.\n \n1️⃣. Cotizacion Lista Escolar. ❔\n2️⃣. Ubicación de la tienda. 📍\n3️⃣. Cotizacion cliente de oficina. 📄\n4️⃣. Cotizacion cliente mayorista. 📄\n5️⃣. Quejas o sugerencias. 📄\n6️⃣. Hablar con un asesor. 🙋‍♂️\n7️⃣. Horario de Atención. 🕜"
                ]
        ]);
    }

    $ch = curl_init('https://graph.facebook.com/v19.0/300832309790377/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer EAALZCPZAxU428BO5d9yjYRoRoKoICwjWwySNijXz9TjtfHoR5AgnaORTXfj2cueaBzksx82P6rtAX3zIy2Bqni9OazUdfoMBV3Sbk1eBYYXaASfi9cueppVTkQ29NZCg901b3lI76XB1TsYLdOZBt5OtbrZCAbrP3I9nsTI4R9QMobhf6fMP5Hk6WHgEflKSK'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Añadir depuración para la respuesta
    if ($response === false) {
        echo "Error al enviar el mensaje: " . curl_error($ch) . "\n";
    } else {
        $responseData = json_decode($response, true);
        if (isset($responseData['error'])) {
            echo "Error en la respuesta de la API: " . $responseData['error']['message'] . "\n";
        } else {
            echo "Mensaje enviado correctamente\n";
        }
    }
    curl_close($ch);
}

// Manejo de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    recibirMensajes($data, http_response_code());
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['hub_mode']) && isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === TOKEN_TlaquepaqueEscolar) {
        echo $_GET['hub_challenge'];
    } else {
        http_response_code(403);
    }
}
?>
