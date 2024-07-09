<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use Illuminate\Console\Command;
use App\Models\SuccessfulEmail;
use Html2Text\Html2Text;

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
        $emails = SuccessfulEmail::where('email','!=','done')->get();

        foreach ($emails as $email) {
            $parse =  Helper::parseRawEmail($email->email);

            $email->raw_text = $parse['plainText'];
            $email->email = 'done';
            $email->save();

        }

    }
}


