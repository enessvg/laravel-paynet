<?php

namespace Paynet\DTOs;

class MailOrderParams
{
    public function __construct(
        public string $amount,
        public string $nameSurname,
        public string $userName,
        public int $posType = 5,
        public bool $addCommissionToAmount = true,
        public ?string $agentId = null,
        public ?string $email = null,
        public bool $sendMail = false,
        public ?string $phone = null,
        public bool $sendSms = false,
        public int $expireDate = 24,
        public ?string $note = null,
        public ?string $agentNote = null,
        public ?string $referenceNo = null,
        public ?string $succeedUrl = null,
        public ?string $errorUrl = null,
        public ?string $confirmationUrl = null,
        public bool $sendConfirmationMail = true,
        public bool $multiPayment = true,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'pos_type' => $this->posType,
            'addcomission_to_amount' => $this->addCommissionToAmount,
            'agent_id' => $this->agentId,
            'name_surname' => $this->nameSurname,
            'user_name' => $this->userName,
            'amount' => $this->amount,
            'email' => $this->email,
            'send_mail' => $this->sendMail,
            'phone' => $this->phone,
            'send_sms' => $this->sendSms,
            'expire_date' => $this->expireDate,
            'note' => $this->note,
            'agent_note' => $this->agentNote,
            'reference_no' => $this->referenceNo,
            'succeed_url' => $this->succeedUrl,
            'error_url' => $this->errorUrl,
            'confirmation_url' => $this->confirmationUrl,
            'send_confirmation_mail' => $this->sendConfirmationMail,
            'multi_payment' => $this->multiPayment,
        ], fn($value) => $value !== null && $value !== '');
    }
}
