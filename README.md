# Navatar

Name + Avatar = 'Navatar'. An e107 CMS plugin that generates user avatars from the user's initials.

Based on the original plugin by [Arun S. Sekher](https://github.com/arunshekher/navatar), updated for PHP 8 and current e107 v2.

# Description

Navatar uses initials from usernames or real names of site users to automatically generate avatar images if they haven't uploaded their own. It wraps the [php-initial-avatar-generator](https://github.com/LasseRafn/php-initial-avatar-generator) library by Lasse Rafn.

Avatars are generated:

* automatically when a user logs in or activates their account (configurable in plugin preferences),
* in bulk for existing users via *Admin → Navatar → Generate Navatars*,
* on a schedule via the bundled cron job (*Admin → Scheduled Tasks → "Create Navatars via cron"*).

# Requirements

* PHP 7.4 – 8.4
* Fileinfo extension
* GD library (>= 2.0) or Imagick PHP extension

# Installation

All dependencies are bundled in the `vendor/` directory — **no composer needed on the server**. Upload the whole plugin directory (including `vendor/`) and install it via the e107 admin plugin manager.

# Troubleshooting

If avatars are not generated, check *Admin → Tools → System Logs* for entries named `Navatar Generation failed!` — every generation error is logged there, including the message, file and line.
