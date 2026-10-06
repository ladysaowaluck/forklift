<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    // Unit test to verify User::isAdmin helper returns true for admin and false for other roles
    public function test_user_is_admin_helper(): void
    {
        $admin = new User(['role' => 'admin']);
        $this->assertTrue($admin->isAdmin());

        $adminCaps = new User(['role' => 'Admin']);
        $this->assertTrue($adminCaps->isAdmin());

        $driver = new User(['role' => 'driver']);
        $this->assertFalse($driver->isAdmin());

        $user = new User(['role' => 'user']);
        $this->assertFalse($user->isAdmin());

        $nullRoleUser = new User();
        $this->assertFalse($nullRoleUser->isAdmin());
    }
}
