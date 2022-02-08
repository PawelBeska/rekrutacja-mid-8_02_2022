<?php

namespace App\Services;


use App\Models\Message;

class MessageService
{


    /**
     * @param \App\Models\Message $message
     */
    public function __construct(private Message $message = new Message())
    {
    }

    /**
     * @return \App\Models\Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function assignData(array $data): static
    {
        $this->message->title = $data['title'];
        $this->message->email = $data['email'];
        $this->message->content = $data['content'];
        $this->message->save();
        return $this;
    }

}
