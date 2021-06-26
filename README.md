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
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\Carbon;

$chhankitek = new Chhankitek();
$now = Carbon::createFromDate(2021, 5, 19);

// ថ្ងៃពុធ ៨ កើត ខែជេស្ឋ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
return $chhankitek->khmerLunarDate($now)->toString();
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
