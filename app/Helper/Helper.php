<?php
    namespace App\Helper;

    class Helper{



       public static function parseRawEmail($rawEmail)
       {
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
   
       public static function extractPlainText($body)
       {
        $pattern = '/Content-Type: text\/plain; charset=UTF-8\n\n(.*?)\n--/s';
        preg_match_all($pattern, $body, $matches);
    
        // If no matches found, try another approach
        if (empty($matches[1])) {
            $pattern = '/Content-Type: text\/plain; charset=UTF-8\n(.*?)\n--/s';
            preg_match_all($pattern, $body, $matches);
        }
    
        // Additional pattern to match the case without double newlines
        if (empty($matches[1])) {
            $pattern = '/Content-Type: text\/plain; charset=UTF-8\n(.*?)--/s';
            preg_match_all($pattern, $body, $matches);
        }
    
        // Concatenate all found plain text parts
        $plainTextParts = array_map('trim', $matches[1]);
        return implode("\n", $plainTextParts);
       }
    }
