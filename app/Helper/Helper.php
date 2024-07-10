<?php

namespace App\Helper;

use ZBateson\MailMimeParser\MailMimeParser;

class Helper
{

    public static function parseRawEmail($rawEmail)
    {
        $parser = new MailMimeParser();

        $message = $parser->parse($rawEmail, true);
        $txtStream = $message->getTextStream();
        if ($txtStream) {

            $txtStream->getContents();
            $plainText =  $message->getTextContent();
        } else {
            $htmlStream = $message->getHtmlStream();
            $htmlStream->getContents();
            $htmlContent = $message->getHtmlContent();

            // Remove content within <style> tags
            $htmlContent = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $htmlContent);

            // Remove HTML tags
            $plainText = strip_tags($htmlContent);

            // Decode HTML entities
            $plainText = html_entity_decode($plainText);
        }

        $plainText = preg_replace('/\s+/', ' ', $plainText);
        $plainText = trim($plainText);

        return [
            'plainText' => $plainText
        ];
    }
}
