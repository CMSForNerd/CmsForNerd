<?php

declare(strict_types=1);

use CmsForNerd\User;

test('user class is instantiable and retains properties', function () {
    $user = new User('jules', 'admin');

    expect($user->username)->toBe('jules')
        ->and($user->role)->toBe('admin');
});

test('user class defaults role to student', function () {
    $user = new User('alice');

    expect($user->username)->toBe('alice')
        ->and($user->role)->toBe('student');
});

test('user incrementViews executes without errors and actually increases view count', function () {
    $user = new User('bob');

    $reflection = new ReflectionClass($user);
    $property = $reflection->getProperty('viewCount');
    $property->setAccessible(true);

    expect($property->getValue($user))->toBe(0);

    $user->incrementViews();

    expect($property->getValue($user))->toBe(1);
});
