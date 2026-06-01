* Specification *

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in RFC 2119.

* Introduction *

<https://www.linuxmalaysia.com/>
<https://github.com/CMSForNerd/CmsForNerd>

CmsForNerd is for small websites with static content. It is NOT intended for large-scale dynamic applications.

* Requirements *

1. PHP VERSION: You MUST use PHP 8.4 or higher.
2. EXTENSIONS: Your server MUST support cURL and OpenSSL for security features.
3. COMPOSER: You SHOULD run `composer install` to initialize the project dependencies.

* Installation *

Download the repository and expand it inside your web server's public directory. The directory MUST be writable if you intend to add content via web-based tools.

* Configuration *

1. THEME: You MUST configure your theme in `includes/global-control.inc.php`.
2. PAGES: To create a page (e.g., `about.php`), you MUST also create `contents/about-body.inc`.
3. SECURITY: You SHOULD configure your Cloudflare Turnstile keys in `includes/turnstile.php` if you use forms.

* Maintenance *

It is RECOMMENDED to use Laravel Herd for local development and Firefox Developer Tools for CSS Grid debugging.
