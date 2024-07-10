<?php

namespace App\Helper;

class Helper
{



    public static function parseRawEmail($rawEmail)
    {
        $rawEmail = self::ensureUtf8($rawEmail);
        // Split the raw email content into lines
        $lines = explode("\n", $rawEmail);

        // Initialize variables to capture the email parts
        $headers = [];
        $body = '';
        $isBody = false;

        // Iterate through each line to separate headers from the body
        foreach ($lines as $line) {
            // Check if the line is the boundary between headers and body
            if (empty(trim($line))) {
                $isBody = true;
                continue;
            }

            if ($isBody) {
                $body .= $line . "\n";
            } else {
                $headers[] = $line;
            }
        }

        // Extract the plain text part from the body
        $plainText = self::extractPlainText($body);

        return [
            'headers' => $headers,
            'body' => $body,
            'plainText' => $plainText
        ];
    }

    public static function extractPlainText2($body)
    {

        // Normalize line endings
        $body = str_replace("\r\n", "\n", $body);

        // Split the body into lines
        $lines = explode("\n", $body);

        $plainText = '';
        $isPlainTextSection = false;

        // Iterate through lines and extract plain text
        foreach ($lines as $line) {
            if (preg_match('/Content-Type:\s*text\/plain/i', $line)) {
                $isPlainTextSection = true;
                continue;
            }

            if ($isPlainTextSection) {
                if (preg_match('/^Content-Type:/i', $line) || preg_match('/^--/i', $line)) {
                    $isPlainTextSection = false;
                    continue;
                }

                // Skip unwanted lines
                if (preg_match('/; charset=/i', $line) || preg_match('/Content-Transfer-Encoding:/i', $line) || preg_match('/Mime-Version:/i', $line)) {
                    continue;
                }

                // Append the line to the plain text content
                $plainText .= $line . "\n";
            }
        }

        $plainText = quoted_printable_decode($plainText);
        $plainText = self::removeUnwantedLines($plainText);

        return self::cleanMalformedUtf8($plainText);
    }

    public static function extractPlainText($body)
    {
        // Normalize line endings
        $body = str_replace("\r\n", "\n", $body);

        // Split the body into lines
        $lines = explode("\n", $body);

        $plainText = '';
        $isPlainTextSection = false;

        // Iterate through lines and extract plain text
        foreach ($lines as $line) {
            if (preg_match('/Content-Type:\s*text\/plain/i', $line)) {
                $isPlainTextSection = true;
                continue;
            }

            if ($isPlainTextSection) {
                if (preg_match('/^Content-Type:/i', $line) || preg_match('/^--/i', $line)) {
                    $isPlainTextSection = false;
                    continue;
                }

                // Skip unwanted lines
                if (preg_match('/; charset=/i', $line) || preg_match('/Content-Transfer-Encoding:/i', $line) || preg_match('/Mime-Version:/i', $line)) {
                    continue;
                }

                // Skip lines containing email addresses and URLs
                if (preg_match('/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/i', $line) || preg_match('/\bhttps?:\/\/\S+/i', $line)) {
                    continue;
                }

                // Append the line to the plain text content
                $plainText .= $line . "\n";
            }
        }

        $plainText = quoted_printable_decode($plainText);
        $plainText = self::removeUnwantedLines($plainText);

        return self::cleanMalformedUtf8($plainText);
    }

    public static function ensureUtf8($string)
    {
        if (!mb_check_encoding($string, 'UTF-8') || !preg_match('//u', $string)) {
            // If the string is not valid UTF-8, convert it
            $string = utf8_encode($string);
        }
        return $string;
    }

    public static function cleanMalformedUtf8($string)
    {
        // Convert any non-UTF-8 characters to a safe character
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    public static function removeUnwantedLines($string)
    {
        // Remove specific unwanted lines
        $patterns = [
            '/; charset\s*=\s*"UTF-8"/i',
            '/Content-Transfer-Encoding:\s*QUOTED-PRINTABLE/i',
            '/Mime-Version:\s*1.0/i',
            '/^\s+$/m',  // Remove lines that contain only whitespace
        ];

        foreach ($patterns as $pattern) {
            $string = preg_replace($pattern, '', $string);
        }

        

        // Remove extra spaces and empty lines
        $string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $string);
        $string = preg_replace('/\n{2,}/', "\n", $string);

        return $string;
    }
}
