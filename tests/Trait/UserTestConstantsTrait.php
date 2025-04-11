<?php

namespace App\Tests\Trait;

trait UserTestConstantsTrait
{
    protected const CORRECT_TEST_EMAIL = 'test@example.com';
    protected const CORRECT_TEST_PASSWORD = 'password123';
    protected const INCORRECT_TEST_EMAIL = 'invalid-email';
    protected const INCORRECT_TEST_PASSWORD = 'wrongpassword';
    protected const TOO_SHORT_TEST_PASSWORD = 'short';
    protected const CORRECT_EDIT_EMAIL = 'edit@example.com';
    protected const CORRECT_EDIT_PASSWORD = 'newpassword123';
}