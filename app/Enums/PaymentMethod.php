<?php

namespace App\Enums;

abstract class PaymentMethod
{
    const PIX = 'P';
    const CREDIT_CARD = 'C';
    const DEBIT_CARD = 'D';
}