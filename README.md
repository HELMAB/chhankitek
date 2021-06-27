# Chhankitek for Laravel

Cambodians use two types of calendars: the international 
calendar for civil purposes and the traditional
calendar for religious purposes. Although called Chhankitek,
which means lunar calendar, Khmer traditional calendar is a 
lunisolar calendar similar to some of the Hindu calendars and the Chinese calendar [see more](http://www.cam-cc.org/calendar/).

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
$formatKhmerDate = $this->chhankiteck(Carbon\Carbon::now());
$formatKhmerDate->toString(); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
```

## Available methods

```php
// In Laravel controller, use this trait
use HasChhankitek;

$formatKhmerDate = $this->chhankiteck(Carbon\Carbon::now());

$formatKhmerDate->getDayOfWeek(); // អាទិត្យ, ច័ន្ទ...
$formatKhmerDate->getLunarDay(); // ១កើត, ២កើត...
$formatKhmerDate->getLunarMonth(); // ចេត្រ...
$formatKhmerDate->getLunarZodiac(); // ជូត, ឆ្លូវ...
$formatKhmerDate->getLunarEra(); // ត្រីស័ក...
$formatKhmerDate->getLunarYear(); // ២៥៦៥, ២៥៦៦..
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
