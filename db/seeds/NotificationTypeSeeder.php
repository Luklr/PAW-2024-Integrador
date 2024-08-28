<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class NotificationTypeSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'PREPARING',
                'description' => 'Your order is being prepared'
            ],
            [
                'name' => 'DISPATCHED',
                'description' => 'Your order is being transported'
            ],
            [
                'name' => 'READY_FOR_PICKUP',
                'description' => 'Your order is ready to be picked up'
            ],
            [
                'name' => 'DELIVERED',
                'description' => 'Your order was delivered'
            ],
        ];

        $table = $this->table('notification_type');
        $table->insert($data)->saveData();
    }
}