# Chhankitek for Laravel

Convert from AD (Anno Domini) to Lunar (Chhankitek) format [see more](http://www.cam-cc.org/calendar/).

[![Latest Version on Packagist](https://img.shields.io/packagist/v/asorasoft/chhankitek.svg?style=flat-square)](https://packagist.org/packages/asorasoft/chhankitek)
[![Total Downloads](https://img.shields.io/packagist/dt/asorasoft/chhankitek.svg?style=flat-square)](https://packagist.org/packages/asorasoft/chhankitek)
## Installation

You can install the package via composer:

```bash
composer require asorasoft/chhankitek
```

## Usage

```php
// In Laravel controller, use this trait
use HasChhankitek;

// start call chhankitek method
$toLunarDate = $this->chhankiteck(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));
$toLunarDate->toString(); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
```

## Available methods

```php
// In Laravel controller, use this trait
use HasChhankitek;

$toLunarDate = $this->chhankiteck(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));

$toLunarDate->getDayOfWeek(); // អាទិត្យ, ច័ន្ទ...
$toLunarDate->getLunarDay(); // ១កើត, ២កើត...
$toLunarDate->getLunarMonth(); // ចេត្រ...
$toLunarDate->getLunarZodiac(); // ជូត, ឆ្លូវ...
$toLunarDate->getLunarEra(); // ត្រីស័ក...
$toLunarDate->getLunarYear(); // ២៥៦៥, ២៥៦៦..
```

Or we can use `toLunarDate` helper function.

```php 
toLunarDate(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mabhelitc@gmail.com instead of using the issue tracker.

## Support

If you like this package and want to support me, you can [buy me a coffee ☕](https://www.buymeacoffee.com/helmab)

## Credits

-   [Mab Hel](https://github.com/asorasoft)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Authors and acknowledgment

This library might not exist without hardwork of these people:
1. Base on algorithm of `Mr.Phylypo Tum` from [Cam-CC](https://www.cam-cc.org/calendar/)
2. Porting from [momentkh](https://github.com/ThyrithSor/momentkh) by `ThyrithSor` into `Java`
3. [Khmer New Year Time Calculation](http://www.dahlina.com/education/khmer_new_year_time.html)
4. Porting from [MetheaX/khmer-chhankitek-calendar](https://github.com/MetheaX/khmer-chhankitek-calendar) by `MetheaX` into `Laravel Package`
