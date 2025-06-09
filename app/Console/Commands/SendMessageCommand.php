<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7\Message;
use Illuminate\Console\Command;
use App\Events\MessageSent;
use function Laravel\Prompts\text;

class SendMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send to a chat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text(
            label: 'what is your name?',
            required: true,
        );

        $text = text(
            label: 'what do you want to say?',
            required: true,
        );

        MessageSent::dispatch($name, $text);
    }
}
