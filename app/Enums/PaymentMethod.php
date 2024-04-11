<?php

namespace App\Enums;

/**
 * Class PaymentMethod
 * Enumeration class for payment methods.
 *
 * @package App\Enums
 */
abstract class PaymentMethod
{
    const PIX = 'P';
    const CREDIT_CARD = 'C';
    const DEBIT_CARD = 'D';
}