<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $wallet;
    public $amount;
    public $type;
    public $details;
    public $balance;

    public function __construct($wallet,$amount,$type,$details,$balance)
    {
        //
        $this->wallet=$wallet;
        $this->amount=$amount;
        $this->type=$type;
        $this->details=$details;
        $this->balance=$balance;
    }


}
