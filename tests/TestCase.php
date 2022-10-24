<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function user(){
        //return User::factory()->create();  
        return User::factory()->suspended()->create();     
    }
}
