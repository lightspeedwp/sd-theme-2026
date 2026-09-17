# Contract: Individual offer URLs

**Between**: `sd-enhancements-2026` (implements) and this page, the sitemap configuration on
the deployment/redirects line, and every old link, bookmark and search result pointing at
`/special/{slug}/` (depend on it).

**Why a contract**: the spec assigned this to the platform. Measurement says the platform does
not do it — see [research.md](../research.md) R-07. This file records what we build instead,
and what in the spec it contradicts.

---

## What the platform actually does

Verified by execution, 2026-09-17:

- `lsx_to_settings['special_disable_single']` is consumed on the filter
  `content_model_post_type_args`, which fires only for `accommodation`, `destination` and
  `tour`. **`special` never reaches it** — it is registered by a direct `register_post_type()`
  call that bypasses the content-models runtime. With the setting on, `special`'s
  `publicly_queryable` stays `true` and `/special/{slug}/` still serves the offer at 200.
- On a post type the filter *does* reach, the setting sets `publicly_queryable = false`, which
  serves the **homepage at HTTP 200 with zero redirects** — not a 404, not a redirect.

So enabling the setting achieves nothing here, and would be actively wrong if it worked.

## The guarantee we implement

A `template_redirect` in `sd-enhancements`:

| | |
|---|---|
| Trigger | A front-end request resolving to a single `special` |
| Response | `301` to the specials archive |
| Hops | Exactly one — the target is the archive URL itself, never a URL that redirects again |
| Status of the offer | Applies to published **and** draft/expired offers — an old link to an offer that has since been retired still lands on the archive rather than erroring |
| Admin, REST, previews, feeds | Unaffected. An editor must still be able to open and preview an offer |

The archive URL is derived from the post type's own archive link, not hardcoded, so it
survives a permalink change.

## What this contradicts

- **FR-021** — "achieved by the platform's Disable Single setting, which MUST be enabled".
  The setting is inert for `special`; enabling it is a no-op. Recommend leaving it off so the
  site's settings do not imply a mechanism that is not running.
- **FR-022** — "MUST NOT author a redirect rule … for these URLs". Written on the belief the
  platform already did it. It does not, so one is authored — in the plugin, which is where the
  deactivation test puts it, and never in the theme.
- **Assumption 12** — "The redirect is the platform's … Confirmed 2026-09-17." The 301 on live
  is real but is not produced by these plugins. Its source is still unidentified.

**SC-005 is unchanged and remains the target**: a single permanent redirect per URL, zero
URLs returning success while displaying different content, zero chains longer than one hop.
Only the clause about zero redirect rules authored on our side is wrong, and it is wrong
because the premise behind it was.

All three go to LS-2033 as corrections against F-03, which already owns the sitemap half.

## What stays with someone else

**FR-023 / SC-006 — the sitemap.** Individual offer URLs must not appear in
`special-sitemap.xml`. That is search-plugin configuration on the deployment/redirects line.
This feature records the requirement and verifies it at hand-over; it does not implement it.
Live still advertises all five URLs despite its own 301, so it will not fix itself.

**Locating the live redirect.** Worth doing before launch so the rebuild does not stack two
rules, and so the deployment line knows whether it is inheriting one. A check, not a blocker.
