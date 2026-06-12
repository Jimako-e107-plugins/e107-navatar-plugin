# Changelog

## 2.0 (2026-06-12)

Update for PHP 7.4 – 8.4 and current e107 v2. Based on Navatar 1.1.2 by Arun S. Sekher.

### Fixed
- Avatar generation failed silently on PHP 8: `\Error` was not caught, nothing reached the logs — generation errors are now caught (`\Throwable`) and written to the e107 system log (`NAVATAR_01`)
- `File::removeById()` used a wrong filename (missing `_navatar` suffix) — generated files were never deleted by the tidy-up tool
- Missing/empty plugin prefs no longer break generation — sane defaults are used
- `user_image IS NULL` is treated the same as an empty value
- User ids cast to int in all DB queries; int/float casts for size, quality, font size and character length prefs

### Added
- Admin page *Tools → Generate Navatars* — one-click bulk generation for all existing users without an avatar
- Working cron job "Create Navatars via cron" (was an empty stub) — generates avatars in batches via Scheduled Tasks
- Avatar upload directory is created automatically when missing
- `changelog.md` (this file)

### Changed
- Bundled libraries updated: lasserafn/php-initial-avatar-generator 3.0.1 → 4.5, intervention/image 2.4.2 → 2.7.2, guzzlehttp/psr7 1.5.2 → 2.11 (+ new transitive deps: meyfa/php-svg, overtrue/pinyin, symfony/polyfill-php80, symfony/deprecation-contracts)
- intervention/image patched for explicit nullable parameter types — no deprecation notices up to PHP 8.4
- symfony/deprecation-contracts kept at 2.5.x so the dependency tree supports PHP 7.4 (3.x would require PHP 8.1)
- `plugin.xml`: version 2.0, PHP minimum 7.4
- README rewritten — dependencies are bundled, no composer needed on the server

### Removed
- All developer files: `composer.json`/`composer.lock`, per-package composer/phpunit/travis/styleci/coveralls configs, package READMEs and changelogs, tests, demo files
- External donation/widget scripts (ko-fi, bitcoin, GitHub buttons) from the admin *Project Info* panel
