<?php

namespace Paynet\Tests\Unit;

use InvalidArgumentException;
use Paynet\DTOs\CardListParams;
use Paynet\DTOs\RatioDefinitionParams;
use Paynet\DTOs\PaymentParams;
use Paynet\DTOs\RatioParams;
use Paynet\DTOs\ReversedRequestParams;
use Paynet\DTOs\SaveCardParams;
use Paynet\DTOs\TdsInitialParams;
use Paynet\Enums\TransactionType;
use Paynet\Tests\TestCase;

class CleanV1ParamsTest extends TestCase
{
    public function test_payment_params_require_card_details_or_saved_card_hash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Kart bilgileri veya cardHash zorunludur.');

        (new PaymentParams(
            amount: '20,50',
            referenceNo: 'REF-1',
            domain: 'www.acme.com',
        ))->toArray();
    }

    public function test_payment_params_validate_save_card_requirements(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Kart saklama icin cardDesc ve cardOwnerId veya userUniqueId zorunludur.');

        (new PaymentParams(
            amount: '20,50',
            referenceNo: 'REF-1',
            domain: 'www.acme.com',
            pan: '5400617004770430',
            month: '12',
            year: '2030',
            cvc: '123',
            cardHolder: 'John Doe',
            saveCard: true,
        ))->toArray();
    }

    public function test_tds_initial_params_emit_documented_required_fields(): void
    {
        $payload = (new TdsInitialParams(
            amount: '20,50',
            referenceNo: 'REF-1',
            returnUrl: 'https://example.test/callback',
            domain: 'www.acme.com',
            cardHash: 'card-token',
            transactionType: TransactionType::PreAuth,
        ))->toArray();

        $this->assertSame('20,50', $payload['amount']);
        $this->assertSame('REF-1', $payload['reference_no']);
        $this->assertSame('https://example.test/callback', $payload['return_url']);
        $this->assertSame('www.acme.com', $payload['domain']);
        $this->assertSame('card-token', $payload['card_hash']);
        $this->assertSame(3, $payload['transaction_type']);
    }

    public function test_ratio_params_use_documented_field_names(): void
    {
        $payload = (new RatioParams(
            posType: 5,
            bin: '540061',
            amount: '100000',
            addCommissionToAmount: true,
            ratioCode: 'R1',
            agentId: '1001',
            cardType: 'cc',
        ))->toArray();

        $this->assertArrayHasKey('addcomission_to_amount', $payload);
        $this->assertArrayNotHasKey('addcommission_to_amount', $payload);
        $this->assertSame('1001', $payload['agent_id']);
        $this->assertSame('cc', $payload['card_type']);
    }

    public function test_transaction_type_matches_paynet_payment_documentation(): void
    {
        $this->assertSame(1, TransactionType::Sale->value);
        $this->assertSame(3, TransactionType::PreAuth->value);
    }

    public function test_payment_amount_must_use_comma_decimal_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('amount 123,45 formatinda olmalidir.');

        (new PaymentParams(
            amount: '20.50',
            referenceNo: 'REF-1',
            domain: 'www.acme.com',
            cardHash: 'card-token',
        ))->toArray();
    }

    public function test_payment_company_amount_must_use_comma_decimal_format_when_present(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('companyAmount 123,45 formatinda olmalidir.');

        (new PaymentParams(
            amount: '20,50',
            referenceNo: 'REF-1',
            domain: 'www.acme.com',
            cardHash: 'card-token',
            companyAmount: '10.00',
        ))->toArray();
    }

    public function test_tds_initial_amount_must_use_comma_decimal_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('amount 123,45 formatinda olmalidir.');

        (new TdsInitialParams(
            amount: '20.50',
            referenceNo: 'REF-1',
            returnUrl: 'https://example.test/callback',
            domain: 'www.acme.com',
            cardHash: 'card-token',
        ))->toArray();
    }

    public function test_reversal_amount_must_be_minor_unit_string_when_present(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('amount 100 ile carpilmis rakamlardan olusmalidir.');

        (new ReversedRequestParams(
            xactId: 'xk_1',
            amount: '10,00',
        ))->toArray();
    }

    public function test_ratio_amount_must_be_minor_unit_string_when_present(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('amount 100 ile carpilmis rakamlardan olusmalidir.');

        (new RatioParams(
            amount: '10,00',
        ))->toArray();
    }

    public function test_save_card_params_require_card_owner_or_user_unique_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cardOwnerId veya userUniqueId zorunludur.');

        (new SaveCardParams(
            cardDesc: 'Kartim',
            cardHolder: 'John Doe',
            cardNumber: '4355080000000000',
            expireMonth: '12',
            expireYear: '2030',
        ))->toArray();
    }

    public function test_card_list_params_reject_empty_owner_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cardOwnerId zorunludur.');

        (new CardListParams(cardOwnerId: ''))->toArray();
    }

    public function test_ratio_definition_params_require_banks_and_bank_ids(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('banks en az bir banka icermelidir.');

        (new RatioDefinitionParams(ratioCode: 'R1', banks: []))->toArray();
    }

    public function test_ratio_definition_params_validate_card_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cardType cc, bc veya dc olmalidir.');

        (new RatioDefinitionParams(
            ratioCode: 'R1',
            banks: [
                ['bank_id' => 'DENZ'],
            ],
            cardType: 'invalid',
        ))->toArray();
    }
}
