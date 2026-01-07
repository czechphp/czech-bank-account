# Czech Bank Account

Provides useful utilities for czech bank account validation and data manipulation.

## Installation

Install the latest version with

```
$ composer require czechphp/czech-bank-account
```

## Documentation

### Bank Account Number Validator

> Czech: Validátor českého národního čísla bankovního účtu

```php
<?php

use Czechphp\CzechBankAccount\Validator\BankAccountNumberValidator;

$validator = new BankAccountNumberValidator();
$violation = $validator->validate('19-19', [
    'type' => BankAccountNumberValidator::OPTION_TYPE_VARIABLE,
]);

if ($violation === BankAccountNumberValidator::ERROR_NONE) {
    // valid
}
```

### Bank Code Validator

> Czech: Validátor kódů platebního styku v ČR (kódy bank)

```php
<?php

use Czechphp\CzechBankAccount\Validator\BankCodeValidator;
use Czechphp\CzechBankAccount\Loader\BankCode\FilesystemLoader;

$validator = new BankCodeValidator(new FilesystemLoader());
$violation = $validator->validate('0100');

if ($violation === BankCodeValidator::ERROR_NONE) {
    // valid
}
```

### Variable Symbol Validator

> Czech: Validátor variabilního symbolu

```php
<?php

use Czechphp\CzechBankAccount\Validator\VariableSymbolValidator;

$validator = new VariableSymbolValidator();
$violation = $validator->validate('123');

if ($violation === VariableSymbolValidator::ERROR_NONE) {
    // valid
}
```

### Specific Symbol Validator

> Czech: Validátor specifického symbolu

```php
<?php

use Czechphp\CzechBankAccount\Validator\SpecificSymbolValidator;

$validator = new SpecificSymbolValidator();
$violation = $validator->validate('123');

if ($violation === SpecificSymbolValidator::ERROR_NONE) {
    // valid
}
```

### Constant Symbol Validator

> Czech: Validátor konstantního symbolu

In default validates only format of constant symbol.

```php
<?php

use Czechphp\CzechBankAccount\Validator\ConstantSymbolValidator;

$validator = new ConstantSymbolValidator();
$violation = $validator->validate('0006');

if ($violation === ConstantSymbolValidator::ERROR_NONE) {
    // valid
}
```

### Bank Code Component

Loads directory of payment system codes.

Loaded data is multidimensional array in following format:

```php
<?php

use Czechphp\CzechBankAccount\Loader\BankCode\FilesystemLoader;
use Czechphp\CzechBankAccount\Loader\BankCode\LoaderInterface;

$loader = new FilesystemLoader();

$data = $loader->load();

// first two rows of $data variable
[
    [
        LoaderInterface::CODE => '0100', // string
        LoaderInterface::NAME => 'Komerční banka, a.s.', // string
        LoaderInterface::BIC => 'KOMBCZPP', // string|null
        LoaderInterface::CERTIS => true, // bool
    ],
    [
        LoaderInterface::CODE => '0300',
        LoaderInterface::NAME => 'Československá obchodní banka, a. s.',
        LoaderInterface::BIC => 'CEKOCZPP',
        LoaderInterface::CERTIS => true,
    ],
];

```

#### FilesystemLoader

In default loads data bundled with library, but can be set to read from any file.

#### FileGetContentsLoader

Loads data from [official remote source](https://www.cnb.cz/en/payments/accounts-bank-codes/) using `file_get_contents`.

#### SymfonyHttpClientLoader

Loads data from [official remote source](https://www.cnb.cz/en/payments/accounts-bank-codes/) using http client implementing [symfony/http-client-implementation](https://packagist.org/providers/symfony/http-client-implementation).

##### SymfonyCachedLoader

Decorator loader caches result from parent loader using cache client implementing [symfony/cache-implementation](https://packagist.org/providers/symfony/cache-implementation).

#### ChainLoader

Chains loaders. If chained loader fails, then it calls next loader in line.

### Bank Account Number Format Converter

Converts bank account number format between common formats.

```php
<?php

use Czechphp\CzechBankAccount\Utils\BankAccountNumberFormatConverter;

BankAccountNumberFormatConverter::convertVariableToConstant('19-19'); // returns "0000190000000019"

BankAccountNumberFormatConverter::convertConstantToVariable('0000190000000019'); // returns "19-19"
```
