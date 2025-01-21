<?php

namespace App\Console\Commands;

use App\Mail\HolidaysPending;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find(1); // Usuario al que se enviará el correo

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'day' => now()->format('Y-m-d'), // Puedes cambiar esto según el contenido necesario
        ];

        Mail::to($user)->send(new HolidaysPending($data));

        $this->info('Correo enviado correctamente a ' . $user->email);
    }

}
