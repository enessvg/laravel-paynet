<?php

namespace Paynet\DTOs;

use Paynet\Enums\ResultCode;

class RatioResponse extends BaseResponse
{
    /**
     * @param Bank[] $banks
     */
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        public readonly array $banks = [],
    ) {
        parent::__construct($objectName, $code, $message);
    }

    public static function fromJson(object $json): static
    {
        $banks = [];
        
        if (isset($json->data) && is_array($json->data)) {
            foreach ($json->data as $bankData) {
                $banks[] = Bank::fromJson($bankData);
            }
        }

        return new static(
            objectName: $json->object_name ?? null,
            code: ResultCode::fromCode((int) ($json->code ?? 1)),
            message: $json->message ?? null,
            banks: $banks,
        );
    }

    /**
     * Taksit tablosunu HTML olarak döndürür
     */
    public function toHtmlTable(): string
    {
        $uniqueInstallments = [];
        
        foreach ($this->banks as $bank) {
            foreach ($bank->ratios as $ratio) {
                if (!in_array($ratio->instalment, $uniqueInstallments)) {
                    $uniqueInstallments[] = $ratio->instalment;
                }
            }
        }
        
        sort($uniqueInstallments);

        $html = '<table class="paynet-ratio-table">
            <thead>
                <tr><th></th>';
        
        foreach ($uniqueInstallments as $instalment) {
            $html .= "<th>{$instalment}</th>";
        }
        
        $html .= '</tr></thead><tbody>';
        
        foreach ($this->banks as $bank) {
            $html .= '<tr><td><img src="' . $bank->bankLogo . '" alt="' . $bank->bankName . '"></td>';
            
            foreach ($uniqueInstallments as $instalment) {
                $ratioItem = null;
                
                foreach ($bank->ratios as $ratio) {
                    if ($ratio->instalment === $instalment) {
                        $ratioItem = $ratio;
                        break;
                    }
                }
                
                if ($ratioItem === null) {
                    $html .= '<td>-</td>';
                } else {
                    $html .= '<td>' . number_format($ratioItem->ratio * 100, 2, '.', '') . '</td>';
                }
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }
}

class Bank
{
    /**
     * @param Ratio[] $ratios
     */
    public function __construct(
        public readonly ?int $bankId = null,
        public readonly ?string $bankLogo = null,
        public readonly ?string $bankName = null,
        public readonly array $ratios = [],
    ) {}

    public static function fromJson(object $json): self
    {
        $ratios = [];
        
        if (isset($json->ratio) && is_array($json->ratio)) {
            foreach ($json->ratio as $ratioData) {
                $ratios[] = Ratio::fromJson($ratioData);
            }
        }

        return new self(
            bankId: isset($json->bank_id) ? (int) $json->bank_id : null,
            bankLogo: $json->bank_logo ?? null,
            bankName: $json->bank_name ?? null,
            ratios: $ratios,
        );
    }
}

class Ratio
{
    public function __construct(
        public readonly ?float $ratio = null,
        public readonly ?string $instalmentKey = null,
        public readonly ?int $instalment = null,
        public readonly ?string $instalmentAmount = null,
        public readonly ?string $totalNetAmount = null,
        public readonly ?string $totalAmount = null,
        public readonly ?string $commission = null,
        public readonly ?string $commissionTax = null,
        public readonly ?string $desc = null,
        public readonly ?string $ratioCode = null,
    ) {}

    public static function fromJson(object $json): self
    {
        return new self(
            ratio: isset($json->ratio) ? (float) $json->ratio : null,
            instalmentKey: $json->instalment_key ?? null,
            instalment: isset($json->instalment) ? (int) $json->instalment : null,
            instalmentAmount: $json->instalment_amount ?? null,
            totalNetAmount: $json->total_net_amount ?? null,
            totalAmount: $json->total_amount ?? null,
            commission: $json->commision ?? null,
            commissionTax: $json->commision_tax ?? null,
            desc: $json->desc ?? null,
            ratioCode: $json->ratio_code ?? null,
        );
    }
}
