# Changelog

All notable changes to `chhankitek` will be documented in this file

<<<<<<< HEAD
## 2.1.0 - 2026-06-08

- Migrate the test suite from PHPUnit to Pest 3
- Add tests for the `mbTrimNumber` helper
- Widen `orchestra/testbench` constraint and let Pest manage `phpunit/phpunit` to fix `composer install` dependency resolution
=======
## 2.1.0 - 2026-06-07

- Add support for Laravel 13 by widening the `illuminate/support` constraint to include `^13.0`
- Widen `orchestra/testbench` to `^11.0` and `phpunit/phpunit` to `^12.0` for Laravel 13 testing
- Migrate the test suite from PHPUnit to Pest 4 (the `composer test` script now runs `pest`)

## 2.0.4 - 2026-06-07

- Fix non-deterministic date handling by normalizing dates to start of day in the `Asia/Phnom_Penh` timezone, preventing day-of-week and lunar-date shifts based on the current time of day
- Replace the dependency on `mb_trim` with a new `mbTrimNumber` helper that also strips zero-width space (U+200B) and BOM (U+FEFF) characters
- Use `mbTrimNumber` in `convertToKhmerNumber` for Khmer numeral conversion
- Widen `orchestra/testbench` and `phpunit/phpunit` constraints to support Laravel 10, 11, and 12
- Add tests for the `mbTrimNumber` helper and Khmer number conversion
>>>>>>> 97a00310c5ae7b316cfb052e6891ed353e6c7282

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
