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

(_**Deprecated**_) To use optional validation against database of known constant symbols, 
validator needs `Filter` instance in the constructor and `filter` option in the `validate` methods call.

```php
<?php

use Czechphp\CzechBankAccount\ConstantSymbol\Filter\ArrayCacheFilter;
use Czechphp\CzechBankAccount\ConstantSymbol\Filter\Filter;
use Czechphp\CzechBankAccount\ConstantSymbol\Loader\ArrayRequireLoader;
use Czechphp\CzechBankAccount\Validator\ConstantSymbolValidator;

$filter = new ArrayCacheFilter(new Filter(new ArrayRequireLoader()));
$validator = new ConstantSymbolValidator($filter);
$violation = $validator->validate('0006', [
    'filter' => ['include' => ['all']],
]);

if ($violation === ConstantSymbolValidator::ERROR_NONE) {
    // valid
}
```

### Constant Symbol Component (_Deprecated_)

Loads list of known constant symbols and filters out specified categories and symbols.

In the provided database of constant symbols groups `all`, `public` and `restricted` are used.

* group `all` contains all symbols without exception
* group `public` contains symbols that are safe to use by public
* group `restricted` contains symbols that only banks or government is allowed to use

Note that provided database of known constant symbols may be incomplete or restriction groups may be incorrectly set.
The reason is that it is complicated to obtain up to date list of constant symbols and the fact that constant symbols are slowly deprecated by the government.
This database exist so that it is possible to restrict user from entering constant symbol that public is not allowed to use. 

#### ArrayRequireLoader

In default loads data bundled with library, but can be set to read from any file.

#### Filter

Filters loaded data.

Filter criteria is divided into filters include and exclude.
Both filters accept group names and individual constant symbol codes.

For example criteria `['include' => ['public', 'restricted'], 'exclude' => ['5']]` will return list of symbols that are part of of groups `public` and/or `restricted` while symbol `5` is excluded from the list.

#### ArrayCacheFilter

Caches result of latest criteria.

It is recommended to use at least array cache due to the size of the list of known constant symbols. Loading and filtering of the list can take up to tens of milliseconds.

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
