<?php


use App\Models\Shop\ApplicationRequest;
use Illuminate\Database\Seeder;

class ApplicationRequestTableSeeder extends Seeder
{
    public function run()
    {
        $requests = [
            [
                'name' => 'Иванов Иван Иванович',
                'email' => 'ivanov@gmail.com',
                'phone' => '+947843212',
            ],
            [
                'name' => 'Петров Петр Петрович',
                'email' => 'petrov@gmail.com',
                'phone' => '+984443345',
            ],
            [
                'name' => 'Алексеев Алексей Алексеевич',
                'email' => 'ivanov@gmail.com',
                'phone' => '+931112223',
            ],
        ];

        foreach ($requests as $request)
            ApplicationRequest::query()->updateOrCreate($request);

    }
}
