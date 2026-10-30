<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class UserEmailVerification extends Mailable
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
      return $this->view('email.userEmailVerification')
      ->from($address = 'hi@alicor.com.my', $name = 'Alicor')
      ->subject('Alicor: ' . \Lang::get('lang.emailVerification'))
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
