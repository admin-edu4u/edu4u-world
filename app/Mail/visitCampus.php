<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class visitCampus extends Mailable
{
  use Queueable, SerializesModels;

  public $email;
  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct($email,$data) {
      $this->email = $email;
      $this->data = $data;
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build() {

    try{
      if(isset($this->email)) {
      return $this->view('email.visitCampus')
      ->from($address = 'jacob@edu4u.world', $name = 'Edu4u')
      ->subject('Edu4u: Visit Campus')
      ->with([
        'email' => $this->email,
        'data' => $this->data,
    ]);
    }
    } catch(Exception $e) {
      \Log::error($e);
    }
  }
}
