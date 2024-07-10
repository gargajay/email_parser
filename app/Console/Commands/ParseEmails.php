<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use Illuminate\Console\Command;
use App\Models\SuccessfulEmail;
use Html2Text\Html2Text;
use Illuminate\Support\Facades\Log;

class ParseEmails extends Command
{
    protected $signature = 'emails:parse';
    protected $description = 'Parse raw email content and save the plain text body content';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $emails = SuccessfulEmail::where('raw_text','')->get();


        foreach ($emails as $email) {
            try {
                $parse = Helper::parseRawEmail($email->email);
                $email->raw_text = $parse['plainText'] ?? 'No plan text';
                $email->save();
            } catch (\Exception $e) {
                // Log the exception or handle the error as needed
                Log::error('Error parsing email ID ' . $email->id . ': ' . $e->getMessage());
            }
        }

    }
}


