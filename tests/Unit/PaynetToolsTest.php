<?php

namespace Paynet\Tests\Unit;

use Paynet\Support\PaynetTools;
use Paynet\Tests\TestCase;

class PaynetToolsTest extends TestCase
{
    public function test_format_with_decimal_separator(): void
    {
        $this->assertEquals('123,45', PaynetTools::formatWithDecimalSeparator(123.45));
        $this->assertEquals('100,00', PaynetTools::formatWithDecimalSeparator(100));
        $this->assertEquals('0,99', PaynetTools::formatWithDecimalSeparator(0.99));
    }

    public function test_format_without_decimal_separator(): void
    {
        $this->assertEquals(12345, PaynetTools::formatWithoutDecimalSeparator(123.45));
        $this->assertEquals(10000, PaynetTools::formatWithoutDecimalSeparator(100));
        $this->assertEquals(99, PaynetTools::formatWithoutDecimalSeparator(0.99));
    }

    public function test_validate_card_number_with_valid_card(): void
    {
        // Test Mastercard
        $this->assertTrue(PaynetTools::validateCardNumber('5400617004770430'));
        // Test Visa
        $this->assertTrue(PaynetTools::validateCardNumber('4355084355084358'));
    }

    public function test_validate_card_number_with_invalid_card(): void
    {
        $this->assertFalse(PaynetTools::validateCardNumber('1234567890123456'));
        $this->assertFalse(PaynetTools::validateCardNumber('123'));
    }

    public function test_mask_card_number(): void
    {
        $masked = PaynetTools::maskCardNumber('5400617004770430');
        $this->assertEquals('540061******0430', $masked);
    }

    public function test_get_card_bin(): void
    {
        $bin = PaynetTools::getCardBin('5400617004770430');
        $this->assertEquals('540061', $bin);
    }

    public function test_validate_expiry_date_valid(): void
    {
        // Gelecek tarih
        $this->assertTrue(PaynetTools::validateExpiryDate('12', '30'));
        $this->assertTrue(PaynetTools::validateExpiryDate('06', '2030'));
    }

    public function test_validate_expiry_date_invalid(): void
    {
        // Geçmiş tarih
        $this->assertFalse(PaynetTools::validateExpiryDate('01', '20'));
        // Geçersiz ay
        $this->assertFalse(PaynetTools::validateExpiryDate('13', '30'));
        $this->assertFalse(PaynetTools::validateExpiryDate('0', '30'));
    }

    public function test_validate_cvc(): void
    {
        $this->assertTrue(PaynetTools::validateCvc('123'));
        $this->assertTrue(PaynetTools::validateCvc('1234'));
        $this->assertFalse(PaynetTools::validateCvc('12'));
        $this->assertFalse(PaynetTools::validateCvc('12345'));
    }

    public function test_generate_reference_no(): void
    {
        $refNo = PaynetTools::generateReferenceNo('ORD-');
        $this->assertStringStartsWith('ORD-', $refNo);
        $this->assertGreaterThan(15, strlen($refNo));
    }
}
