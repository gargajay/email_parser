<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\SuccessfulEmail;
use eXorus\PhpMimeMailParser\Parser;
use Html2Text\Html2Text;
use Illuminate\Support\Facades\Validator;

class SuccessfulEmailController extends Controller
{
    // Default response structure
    public $response = ['success' => false, 'message' => 'Something went wrong'];

    /**
     * Store a new email.
     */
    public function store(Request $request)
    {

        // Validation rules
        $rules = [
            'affiliate_id' => ['nullable', 'integer'],
            'envelope' => ['string'],
            'from' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'dkim' => ['string'],
            'SPF' => ['string'],
            'spam_score' => ['numeric'],
            'email' => ['required', 'string'],
            'sender_ip' => ['ip'],
            'to' => ['string'],
            'timestamp' => ['required'],
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $this->response['message'] = $validator->errors()->first();
            return response()->json($this->response, UNPROCESSABLE_ENTITY); // Unprocessable Entity
        }

        // Create new email record
        $email = new SuccessfulEmail();
        $email->affiliate_id = $request->affiliate_id;
        $email->envelope = $request->envelope;
        $email->from = $request->from;
        $email->subject = $request->subject;
        $email->dkim = $request->dkim;
        $email->SPF = $request->SPF;
        $email->spam_score = $request->spam_score;
        $email->email = $request->email;
        $email->sender_ip = $request->sender_ip;
        $email->to = $request->to;
        $email->timestamp = microtime(true);

        $parse =  Helper::parseRawEmail($request->email);

        // $email->raw_text = $parse['plainText'];
         $email->raw_text = 'pending';
        

        $email->save();

        $this->response['success'] = true;
        $this->response['message'] = 'Email stored successfully.';
        $this->response['data'] = $email;

        return response()->json($this->response, 201); // Created
    }


    /**
     * Show a specific email.
     */
    public function show($id)
    {
        $email = SuccessfulEmail::findOrFail($id);

        $this->response['success'] = true;
        $this->response['message'] = 'Email fetched successfully.';
        $this->response['data'] = $email;

        return response()->json($this->response, 200); // OK
    }

    /**
     * Update a specific email.
     */
    public function update(Request $request, $id)
    {
        // Validation rules
        $rules = [
            'id' => ['required', 'exists:successful_emails,id'],
            'affiliate_id' => ['sometimes', 'integer'],
            'envelope' => ['sometimes', 'string'],
            'from' => ['sometimes', 'string'],
            'subject' => ['sometimes', 'string'],
            'dkim' => ['sometimes', 'string'],
            'SPF' => ['sometimes', 'string'],
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $this->response['message'] = $validator->errors()->first();
            return response()->json($this->response, UNPROCESSABLE_ENTITY); // Unprocessable Entity
        }

        $email = SuccessfulEmail::findOrFail($id);
        $email->update($request->all());

        $this->response['success'] = true;
        $this->response['message'] = 'Email updated successfully.';
        $this->response['data'] = $email;

        return response()->json($this->response, STATUS_OK); // OK
    }

    /**
     * Get a list of emails.
     */
    public function index()
    {
        $emails = SuccessfulEmail::simplePaginate();

        $this->response['success'] = true;
        $this->response['message'] = 'Emails fetched successfully.';
        $this->response['data'] = $emails;

        return response()->json($this->response, STATUS_OK); // OK
    }

    /**
     * Delete a specific email.
     */
    public function destroy($id)
    {
        $email = SuccessfulEmail::findOrFail($id);
        $email->delete();
        $this->response['success'] = true;
        $this->response['message'] = 'Email deleted successfully.';
        return response()->json($this->response, STATUS_OK); // OK
    }
}
