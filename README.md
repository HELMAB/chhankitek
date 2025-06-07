# Chhankitek for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/asorasoft/chhankitek.svg?style=flat-square)](https://packagist.org/packages/asorasoft/chhankitek)
[![Total Downloads](https://img.shields.io/packagist/dt/asorasoft/chhankitek.svg?style=flat-square)](https://packagist.org/packages/asorasoft/chhankitek)

A Laravel package to convert dates to Lunar (Chhankitek) format. [Learn more about Khmer calendar](https://khmer-calendar.tovnah.com/calendar).

## Installation

You can install the package via composer:

```bash
composer require asorasoft/chhankitek
```

## Usage

```php
// In your Laravel controller, use this trait
use HasChhankitek;

// Convert a date to lunar format
$toLunarDate = $this->chhankiteck(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));
$toLunarDate->toString(); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
```

## Available Methods

```php
// In your Laravel controller, use this trait
use HasChhankitek;

$toLunarDate = $this->chhankiteck(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));

// Get specific lunar date components
$toLunarDate->getDayOfWeek(); // អាទិត្យ, ច័ន្ទ...
$toLunarDate->getLunarDay(); // ១កើត, ២កើត...
$toLunarDate->getLunarMonth(); // ចេត្រ...
$toLunarDate->getLunarZodiac(); // ជូត, ឆ្លូវ...
$toLunarDate->getLunarEra(); // ត្រីស័ក...
$toLunarDate->getLunarYear(); // ២៥៦៥, ២៥៦៦..
```

Alternatively, you can use the `toLunarDate` helper function:

```php 
toLunarDate(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh')); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

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

## Authors and Acknowledgment

This library would not exist without the hard work of these people:
1. Based on the algorithm by `Mr. Phylypo Tum` from [Khmer Calendar](https://khmer-calendar.tovnah.com/calendar/chhankitek.php)
2. Ported from [momentkh](https://github.com/ThyrithSor/momentkh) by `ThyrithSor` into `Java`
3. [Khmer New Year Time Calculation](http://www.dahlina.com/education/khmer_new_year_time.html)
4. Ported from [MetheaX/khmer-chhankitek-calendar](https://github.com/MetheaX/khmer-chhankitek-calendar) by `MetheaX` into a `Laravel Package`
