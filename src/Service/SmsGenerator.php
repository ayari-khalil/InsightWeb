<?php
// src/Service/SmsGenerator.php

namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    public function sendSms(string $number, string $text)
    {
        // Récupérer les identifiants Twilio à partir des variables d'environnement
        $accountSid = getenv('TWILIO_ACCOUNT_SID');
        $authToken = getenv('TWILIO_AUTH_TOKEN');
        $fromNumber = getenv('TWILIO_PHONE_NUMBER');

        // Créer le message à envoyer
        $message = 'Vous a envoyé le message suivant : ' . $text;

        // Créer une instance du client Twilio pour l'envoi du SMS
        $client = new Client($accountSid, $authToken);
        
        // Envoyer le SMS avec Twilio
        $client->messages->create(
            $number, // Numéro de téléphone du destinataire
            [
                'from' => $fromNumber, // Numéro Twilio de l'expéditeur
                'body' => $message,    // Corps du message SMS
            ]
        );
    }
}
