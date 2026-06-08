# Changelog

All notable changes to `chhankitek` will be documented in this file

## 2.0.4 - 2026-06-08

### Pure PHP support & swappable cache

- Make the package usable in pure PHP projects — Laravel is no longer required
- Replace the hard `illuminate/support` dependency with `nesbot/carbon`; `illuminate/support` is now an optional (suggested) dependency for Laravel integration
- Introduce a swappable cache via the `CacheRepository` interface, with bundled `ArrayCache` (framework-free, in-memory) and `LaravelCache` (Laravel cache facade) implementations
- `Chhankitek` now accepts an optional `CacheRepository` as its second constructor argument and auto-detects Laravel, falling back to `ArrayCache` when no application is bound
- Add an optional `CacheRepository` argument to the `toLunarDate()` helper and the `HasChhankitek::chhankitek()` trait method

### Laravel 13 & tooling

- Add support for Laravel 13 by widening the `illuminate/support` constraint to include `^13.0`
- Widen `orchestra/testbench` to `^11.0` and `phpunit/phpunit` to `^12.0`
- Migrate the test suite from PHPUnit to Pest 4 (the `composer test` script now runs `pest`)
- Split the test suite into `PurePhp` (framework-free) and `Laravel` (integration) suites and add cache tests

### Fixes & improvements

- Fix non-deterministic date handling by normalizing dates to start of day in the `Asia/Phnom_Penh` timezone, preventing day-of-week and lunar-date shifts based on the current time of day
- Replace the dependency on `mb_trim` with a new `mbTrimNumber` helper that also strips zero-width space (U+200B) and BOM (U+FEFF) characters
- Use `mbTrimNumber` in `convertToKhmerNumber` for Khmer numeral conversion
- Add tests for the `mbTrimNumber` helper and Khmer number conversion

## 1.0.0 - 2026-01-28

- Refactor color scheme with updated gradient backgrounds and button styles
- Enhance accessibility with scroll-to-top button and skip to main content link
- Improve mobile menu interactions with smooth scrolling and focus styles
- Add favicon and apple-touch-icon for better branding
- Enhance responsive design and navigation
- Add peace banner for Cambodia
- Update canonical and social media URLs for consistency
- Enhance About section with improved layout and animations
- Initial release
