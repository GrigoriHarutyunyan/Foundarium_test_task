<?php

namespace Tests\Feature;

use Tests\TestCase;

class CarControllerTest extends TestCase
{


    public function test_create_car()
    {
        $data = [
            'brand' => 'BMW',
            'model' => 'E46'
        ];
        $this->postJson('/api/car/store', $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_index()
    {
        $this->withoutExceptionHandling();
        $this->getJson('/api/car/')
            ->assertStatus(200);

    }

    public function test_update_car()
    {
        $data = [
            'brand' => 'Volvo',
            'model' => 'XC40'
        ];
        $this->postJson('/api/car/update/1', $data)
            ->assertStatus(200)
            ->assertJson(['message' => "Автомобиль с id - 1 обновлён."]);
    }

    public function test_destroy_car(){
        $this->deleteJson('/api/car/destroy/1')
            ->assertStatus(200)
            ->assertJson(['message' => "Автомобиль с id - 1 удален."]);
    }
}
