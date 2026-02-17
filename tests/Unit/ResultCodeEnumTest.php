<?php

namespace Paynet\Tests\Unit;

use Paynet\Enums\ResultCode;
use Paynet\Tests\TestCase;

class ResultCodeEnumTest extends TestCase
{
    public function test_successful_code_is_zero(): void
    {
        $this->assertEquals(0, ResultCode::Successful->value);
    }

    public function test_is_successful_returns_true_for_successful(): void
    {
        $this->assertTrue(ResultCode::Successful->isSuccessful());
        $this->assertTrue(ResultCode::OldSuccessful->isSuccessful());
    }

    public function test_is_successful_returns_false_for_unsuccessful(): void
    {
        $this->assertFalse(ResultCode::Unsuccessful->isSuccessful());
        $this->assertFalse(ResultCode::ServerError->isSuccessful());
    }

    public function test_description_returns_string(): void
    {
        $this->assertIsString(ResultCode::Successful->description());
        $this->assertEquals('İşlem başarılı', ResultCode::Successful->description());
    }

    public function test_from_code_returns_correct_enum(): void
    {
        $this->assertEquals(ResultCode::Successful, ResultCode::fromCode(0));
        $this->assertEquals(ResultCode::Unsuccessful, ResultCode::fromCode(1));
        $this->assertEquals(ResultCode::ServerError, ResultCode::fromCode(8));
    }

    public function test_from_code_returns_unsuccessful_for_unknown(): void
    {
        $this->assertEquals(ResultCode::Unsuccessful, ResultCode::fromCode(9999));
    }
}
