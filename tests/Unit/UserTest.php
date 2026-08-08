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

test('user incrementViews executes without errors', function () {
    $user = new User('bob');

    expect(fn() => $user->incrementViews())->not->toThrow(Throwable::class);
});
