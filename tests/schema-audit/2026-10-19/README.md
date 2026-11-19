# Schema audit — 2026-10-19

Structured data (JSON-LD) audit of the Southern Destinations rebuild. It was run with the
LightSpeed `wp-schema-audit-agent` against schema.org release 30.1 and Google's rich-result
rules. It checks the schema fixes merged on 2026-10-19.

> Structured data is **plugin behaviour**: the Tour Operator core plugin and its extensions
> produce it, not this theme. These files are QA evidence for the rebuild; none of the fixes
> below belong in the theme.

## Result

| Site | Pages | Errors | Warnings | Notes |
| --- | --- | --- | --- | --- |
| beta.local, Yoast SEO active | 26 | 0 | 28 | 78 |
| beta.local, Yoast SEO inactive | 26 | 0 | 28 | 74 |
| dev (southerndestinations.lightspeedwp.dev) | 27 | 0 | 3 | 54 |

**Every plugin schema defect found on 2026-10-16 is fixed.** All the warnings that remain are
content, a site setting, or expected behaviour (see below).

## Fixes verified

| Linear | Fix | Seen on beta.local |
| --- | --- | --- |
| TO-137 (tour-operator #1391) | Priced tours typed as `TouristTrip` + `Product`, so `additionalProperty` is valid | `["TouristTrip","Product"]`; no `property-domain` warning. Also gone on dev. |
| TO-217 (to-specials #256) | `UnitPriceSpecification` for `unitText`; ended specials marked `Discontinued` | Giraffe Conservation Safari (ended 2026-09-01): `availability: Discontinued`, `priceSpecification` is a `UnitPriceSpecification`. No `expired-offer` warnings. |
| TO-216 (to-reviews #276) | Date of visit output only as `temporalCoverage` | `temporalCoverage: 2025-06-19/2025-09-05`; no `additionalProperty` on `Review`. |
| TO-171 (to-team #146) | Associated tours and accommodation as `knowsAbout` on `Person` | `knowsAbout` holds 9 `TouristTrip`/`LodgingBusiness` nodes; no `additionalProperty` on `Person`. |

Also confirmed again: no duplicate output with Yoast active; standalone output with Yoast
inactive; FAQ schema (`FAQPage`) from the Yoast FAQ block on tours, accommodation and
destinations.

## What remains

| Warning | Where | Cause | Owner |
| --- | --- | --- | --- |
| `description` is empty | beta.local, every page | Empty site tagline on beta | beta site setting; not on dev |
| Review needs `reviewRating` and `itemReviewed` | beta `/review/empty-review/` | Test post with no rating or linked items | Content |
| Review needs `reviewRating` | dev `/review/lion-sands-was-truly-a-little-slice-of-heaven/` | No rating on that post (the rating maps correctly where one is set) | Content |
| LocalBusiness needs `address` | dev `/accommodation/bagatelle/` | Location fields empty on that post | Content |
| Single redirects to `/specials/` | dev specials | Specials singles redirect on dev, so their `Offer` schema is never seen | Decision for the SD build: where `Offer` schema should appear |
| Page has no JSON-LD | beta, Yoast inactive: home, archives, posts | Expected: without Yoast only Tour Operator singles output schema | — |
| `mainEntityOfPage` points to an undefined `@id` | beta, Yoast inactive: every Tour Operator single | Standalone output references a `WebPage` node only Yoast provides | Tour Operator core and extensions; minor |

Notes (`info`) are mostly `TouristTrip`/`TouristDestination` being pending schema.org types,
which needs no action. HTML entities (`&#8211;`) also appear in tour names and breadcrumbs.

Not covered: Google's Rich Results Test, which needs a public URL, and extensions running on
core 2.1. On 2026-10-16, core 2.1.1 produced no extension schema, because the extensions
require core 2.2's `\lsx\schema\Helpers`.

## Environment

| | |
| --- | --- |
| beta.local | WordPress Studio, WordPress 7.1.3, Yoast SEO 28.7 |
| tour-operator | `develop` 422a3c7cd |
| to-reviews | `develop` 29a8853 |
| to-specials | `develop` f4b9bc7 |
| to-team | `develop` 145f4f3 |
| to-faq | `feat/faq-template-part-schema` 2db96d0 |
| dev | Whatever is deployed; versions not checked |

## Files

| File | What it is |
| --- | --- |
| `beta-report.md`, `dev-report.md` | Plain-English client reports (Yoast active) |
| `beta-findings.csv`, `dev-findings.csv` | One row per finding, for tracking fixes |
| `beta-yoast-on-details.md`, `beta-yoast-off-details.md`, `dev-details.md` | Developer detail: page table and every finding with its node path |
| `*.json` | Machine-readable runs, for comparing with later audits |
| `beta-urls.txt` | The beta.local page list: discovered pages plus hand-added variants (a rated review, a parent destination, an ended special, FAQ pages) |

The reports contain no personal data from the markup.

## Re-running

From a clone of `lightspeedwp/.github` (Node.js 20+):

```bash
node agents/wp-schema-audit-agent/lib/schema-tool.cjs validate --urls-file tests/schema-audit/2026-10-19/beta-urls.txt --details
node agents/wp-schema-audit-agent/lib/schema-tool.cjs audit https://southerndestinations.lightspeedwp.dev/ --format report --out dev-report.md --csv dev-findings.csv
```

For the Yoast-inactive run, deactivate Yoast SEO on beta.local first, then reactivate it.
