<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class UserResetPassword extends Mailable
{
  use Queueable, SerializesModels;

  public $email, $id;
  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct($email, $id) {
      $this->email = $email;
      $this->id = $id;
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build() {

    try{
      if(isset($this->email)) {
      return $this->view('email.userResetPassword')
      ->from($address = 'hi@alicor.com.my', $name = 'Alicor')
      ->subject('Alicor: Reset Password')
      ->with([
        'id' => $this->id,
        'email' => $this->email,
    ]);
    }
    } catch(Exception $e) {
      \Log::error($e);
    }
  }
}
