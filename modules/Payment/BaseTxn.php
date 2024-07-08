<?php
namespace Payment\Transaction;

class BaseTxn
{
    protected float $amount;
    protected float $final_amount;
    protected string $currency;
    protected string $txn;
    protected string $siteName;
    protected string $firstName;
    protected string $lastName;
    protected string $userName;
    protected string $userEmail;
    protected string $userCountryCode;
    protected string $userCity;
    protected string $userAddress;
    protected string $userPhone;
    protected int $userId;

    public function __construct($txnInfo)
    {
        $this->amount = $txnInfo->pay_amount;
        $this->final_amount = $txnInfo->final_amount;
        $this->currency = $txnInfo->pay_currency;
        $this->txn = $txnInfo->tnx;
        $this->siteName = setting('site_title', 'global') ?? '';
        $this->firstName = $txnInfo->user->first_name ?? '';
        $this->lastName = $txnInfo->user->last_name ?? '';
        $this->userName = $txnInfo->user->full_name ?? '';
        $this->userEmail = $txnInfo->user->email ?? '';
        $this->userCountryCode = getCountryCode($txnInfo->user->country) ?? 'AE';
        $this->userCity = $txnInfo->user->city ?? '';
        $this->userAddress = $txnInfo->user->address ?? '';
        $this->userPhone = $txnInfo->user->phone ?? '';
        $this->userId = $txnInfo->user->id ?? 0;
    }
}
