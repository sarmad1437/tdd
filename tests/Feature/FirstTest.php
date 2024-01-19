<?php


use App\Http\Helpers\Calculator;

test('add two numbers', function () {
    $calculator = new Calculator();

    $result = $calculator->add(2,3);

    expect($result)->toBe(5);
});
