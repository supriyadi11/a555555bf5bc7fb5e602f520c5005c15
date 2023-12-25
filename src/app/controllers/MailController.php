<?php
namespace App\Controllers;

use App\Auth;
use App\Models\Mail;
use App\Queue;
use App\Queues\SendEmail;
use App\Request;
use App\Resources\MailResource;
use App\Response;

class MailController extends Controller {

    public function __construct() {
        parent::__construct();        
    }

    public function index(Request $request)
    {
        $req = $request->request['body'];
        $perPage = $req['per_page'] ?? 5;
        $page = $req['page'] ?? 1;

        $mails = Mail::thisUser()->paginate(
            perPage: $perPage,
            page: $page
        )->toArray();        

        return $this->response(
            Response::HTTP_OK, 
            (new MailResource($mails))->mapping()
        );
    }

    public function show(Request $request)
    {
        $req = $request->request['body'];
        $mail = Mail::thisUser()->where('id', $req['id'])->first()?->toArray() ?? [];
        return $this->response(
            Response::HTTP_OK, 
            (new MailResource($mail))->mapping()
        );
    }

    public function create(Request $request)
    {
        $req = $request->request['body'];

        $mail = Mail::create([
            'user_id' => Auth::user()->id,
            'to' => $req['to'],
            'cc' => $req['cc'] ?? null,
            'bcc' => $req['bcc'] ?? null,
            'subject' => $req['subject'],
            'body' => $req['body'],
            'status' => 'send',
            'send_at' => $req['status'] == 'send' ? now() : null,
        ]);
        
        if (!$mail) {
            return $this->response(
                Response::HTTP_INTERNAL_SERVER_ERROR, 
                ['message' => 'Something wnt wrong!']
            );
        }

        Queue::push(SendEmail::class, $mail->toArray());

        return $this->response(
            Response::HTTP_OK, 
            (new MailResource($mail->toArray()))->mapping()
        );
    }
}