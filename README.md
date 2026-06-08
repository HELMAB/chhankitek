<div align="center">

# Chhankitek for Laravel

<p align="center">
    <a href="https://packagist.org/packages/asorasoft/chhankitek"><img src="https://img.shields.io/packagist/v/asorasoft/chhankitek.svg?style=flat-square" alt="Latest Version on Packagist"></a>
    <a href="https://packagist.org/packages/asorasoft/chhankitek"><img src="https://img.shields.io/packagist/dt/asorasoft/chhankitek.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/asorasoft/chhankitek.svg?style=flat-square" alt="License"></a>
</p>

<p align="center">A PHP package to convert dates to Lunar (Chhankitek) format — works with or without Laravel. <a href="https://khmer-calendar.tovnah.com/calendar">Learn more about Khmer calendar</a>.</p>

</div>

---

<div align="center">

## 🇰🇭 Stand with Cambodia • កម្ពុជា

### 🕊️ **Cambodia Needs Peace** 🕊️

We stand in solidarity with our brave soldiers defending Cambodia's sovereignty and territorial integrity. Our hearts are with those protecting our homeland during these challenging times. We call upon the international community to support peaceful resolution and respect for Cambodia's borders.

**🙏 កម្ពុជាត្រូវការសន្តិភាព • Together we stand for peace and sovereignty**

</div>

---

## Documentation

For detailed documentation, please visit [https://chhankitek.netlify.app](https://chhankitek.netlify.app)

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
$toLunarDate = $this->chhankitek(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));
$toLunarDate->toString(); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
```

## Available Methods

```php
// In your Laravel controller, use this trait
use HasChhankitek;

$toLunarDate = $this->chhankitek(Carbon\CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));

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

## Pure PHP Usage (without Laravel)

The package works in any PHP project — Laravel is **not** required. The only runtime dependency is [`nesbot/carbon`](https://github.com/briannesbitt/Carbon).

```bash
composer require asorasoft/chhankitek
```

Instantiate `Chhankitek` directly and read the lunar date from the `formatKhmerDate` property:

```php
require 'vendor/autoload.php';

use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

$target = CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh');

$toLunarDate = (new Chhankitek($target))->formatKhmerDate;

$toLunarDate->toString();      // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
$toLunarDate->getLunarMonth(); // ចេត្រ...
$toLunarDate->getLunarYear();  // ២៥៦៥...
```

The `toLunarDate()` helper is also available outside Laravel:

```php
toLunarDate(CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh'));
```

## Khmer Numerals

Convert Arabic numerals to their Khmer representation with the `HasKhmerNumberConversion` trait:

```php
use Asorasoft\Chhankitek\Traits\HasKhmerNumberConversion;

class SomeController
{
    use HasKhmerNumberConversion;

    public function index()
    {
        $this->convertToKhmerNumber(2569); // ២៥៦៩
        $this->convertToKhmerNumber('2025-05-11'); // ២០២៥-០៥-១១
    }
}
```

## Caching

The package caches converted dates for one year (365 days) to minimize computational overhead for frequently accessed dates. The cache is **swappable** via the `CacheRepository` interface, so it works the same whether or not you use Laravel.

### Default behavior

- **Inside Laravel** — automatically uses Laravel's cache system (`LaravelCache`), so it respects whatever cache driver your application is configured with. No setup required.
- **Pure PHP** — falls back to an in-memory cache (`ArrayCache`) that de-duplicates lookups within a single request or script run.

### Providing your own cache

Pass any implementation of `Asorasoft\Chhankitek\Cache\CacheRepository` as the second constructor argument:

```php
use Asorasoft\Chhankitek\Cache\ArrayCache;
use Asorasoft\Chhankitek\Cache\CacheRepository;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

// Use the bundled in-memory cache explicitly
$toLunarDate = (new Chhankitek($target, new ArrayCache))->formatKhmerDate;

// Or wire up your own (e.g. a PSR-16 adapter)
final class Psr16Cache implements CacheRepository
{
    public function __construct(private \Psr\SimpleCache\CacheInterface $cache) {}

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $value = $callback();
        $this->cache->set($key, $value, $ttl);

        return $value;
    }
}

$toLunarDate = (new Chhankitek($target, new Psr16Cache($yourCache)))->formatKhmerDate;
```

## Testing

```bash
composer test
```

The test suite is split into two groups — run them individually if needed:

```bash
vendor/bin/pest --testsuite=PurePhp  # framework-free tests (no Laravel)
vendor/bin/pest --testsuite=Laravel  # Laravel integration tests
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

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
