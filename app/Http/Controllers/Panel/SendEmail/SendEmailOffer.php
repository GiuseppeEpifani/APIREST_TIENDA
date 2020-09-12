<?php

namespace App\Http\Controllers\Panel\SendEmail;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Mail\SendOfferts;
use App\Subscriber;
use Illuminate\Support\Facades\Mail;

class SendEmailOffer extends ApiController
{
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendEmail(Request $request)
    {
        $subscribers=Subscriber::all();

        $data = [
            'asunto' => $request->asunto,
            'body'    => $request->mensaje,
            'image' => $request->file('imagen'),
        ];

        foreach($subscribers as $subscriber){
            retry(5, function () use ($data,$subscriber) {
                Mail::to($subscriber->email)->send(new SendOfferts($data));
            }, 100);
        }
        

        return $this->showMesagge('Correos enviados.');
    }
}
