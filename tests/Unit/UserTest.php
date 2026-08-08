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

    $getViewCount = function () {
        /** @var User $this */
        // @phpstan-ignore-next-line
        return $this->viewCount;
    };

    expect($getViewCount->call($user))->toBe(0);

    $user->incrementViews();

    expect($getViewCount->call($user))->toBe(1);
});
