<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;

interface LoaderInterface
{
    public const DATA_LOCAL_JSON = __DIR__ . '/../../../data/bank-codes.json';

    /**
     * UTF-8 encoded CSV file
     *
     * @see https://www.cnb.cz/cs/platebni-styk/ucty-kody-bank/
     */
    public const DATA_REMOTE_CSV = 'https://www.cnb.cz/cs/platebni-styk/.galleries/ucty_kody_bank/download/kody_bank_CR.csv';

    /**
     * Bank code
     */
    public const CODE = 0;

    /**
     * Bank name
     */
    public const NAME = 1;

    /**
     * Bic
     */
    public const BIC = 2;

    /**
     * CERTIS system
     */
    public const CERTIS = 3;

    /**
     * @return array
     *
     * @throws LogicException
     */
    public function load(): array;
}
