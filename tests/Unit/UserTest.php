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

test('incrementViews increases the private view count', function () {
    $user = new User('carol');

    $viewCount = new ReflectionProperty($user, 'viewCount');
    $viewCount->setAccessible(true);

    expect($viewCount->getValue($user))->toBe(0);

    $user->incrementViews();
    expect($viewCount->getValue($user))->toBe(1);

    $user->incrementViews();
    $user->incrementViews();
    expect($viewCount->getValue($user))->toBe(3);
});

test('username property is readonly and cannot be reassigned', function () {
    $user = new User('erin');

    expect(fn() => $user->username = 'someone-else')->toThrow(Error::class);
});

test('role property is mutable after construction', function () {
    $user = new User('dave', 'student');

    $user->role = 'admin';

    expect($user->role)->toBe('admin');
});

test('constructor rejects a non-string username under strict types', function () {
    expect(fn() => new User(123))->toThrow(TypeError::class);
});

test('getViewCount method no longer exists on the User class', function () {
    expect(method_exists(User::class, 'getViewCount'))->toBeFalse();
});
