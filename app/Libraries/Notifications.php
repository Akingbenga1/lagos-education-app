<?php
/**
 * Created by PhpStorm.
 * User: noibilism
 * Date: 8/31/17
 * Time: 1:43 PM
 */

namespace App\Libraries;

use Illuminate\Http\Request;
use Mail;
use App\Mail\EmailNotification;

class Notifications
{

    public function printInvoice()
    {
        return view('emails.email_notification.print');
    }


    public function send(Request $request,
                         $email = "akinbami.gbenga@gmail.com", $subject = "Good morning: subject of the mail",
                         $message = "This is body of the message here, This is body of the message here This is body of the message here This is body of the message here")
    {
        //var_dump( $request->PDFdata);die();
        if ($request->PDFdata)
        {

            $path = $request->PDFdata->store('download');
        }

        \Illuminate\Support\Facades\Mail::to($email)->send(new EmailNotification($email, $subject, $message,   storage_path('app')."\\". $path));

        return response()->json(['message' => 'Request completed with mail sent successfully']);

    }

}