<?php

use App\Livewire\ChatCenter;
use App\Models\User;
use Livewire\Livewire;

it('starts a thread and sends a message', function () {
    $driver = User::factory()->create(['profile_type' => 'driver']);
    $transp = User::factory()->create(['profile_type' => 'transportadora']);
    Livewire::actingAs($driver)
        ->test(ChatCenter::class)
        ->set('contactId', $transp->id)
        ->call('startThread')
        ->assertSet('activeThreadId', function ($value) {
            return $value !== null;
        })
        ->set('newMessage', 'Hello from driver')
        ->call('sendMessage')
        ->assertHasNoErrors()
        ->assertSet('newMessage', '');
});
