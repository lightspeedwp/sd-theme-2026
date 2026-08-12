# `inc/`

Design-only PHP modules, loaded from `functions.php`.

**This directory is not a back door for business logic.** Apply the boundary test:

> If deactivating the theme would break it, it belongs in the companion
> Southern Destinations block plugin — not here.

## What may live here

Styling and presentation glue for third-party plugin markup that a `theme.json`
or `styles/**` partial genuinely cannot reach — FacetWP facets, Gravity Forms
fields, SearchWP result markup, Soliloquy sliders. See the
`wp-thirdparty-markup-styling` skill before adding one.

Each module should:

- be namespaced `SdTheme2026`,
- register its own assets with `SdTheme2026\asset_version()`,
- be `require_once`'d from `functions.php` with a one-line comment saying why it
  cannot be expressed in JSON.

## What may not

Post-type or taxonomy registration · post expiration · WETU import · form
handlers and submission routing · breadcrumb filters · search integration ·
Salesforce/CRM routing · sticky-header, mobile-menu and banner behaviour ·
the Instagram feed · the footer's conditional-CTA rule.

All of the above surface visually. That does not make them theme work.

---

The base theme this was derived from shipped eight modules here (age gate, term
banners, mega-menu rendering, search integration, carousel styling, Gravity Forms
styling, user job titles, WooCommerce). **All were removed during de-branding** —
they were either commerce-specific or plugin-side behaviour.
