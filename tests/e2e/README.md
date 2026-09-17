# End-to-end tests — sd-theme-2026

Rendering, accessibility and visual baselines for the block theme. Plugin
behaviour lives in the `sd-enhancements-2026` repo; the split follows the
deactivation test in `AGENTS.md` — if turning the theme off would not break it,
it is tested there, not here.

## Running

```bash
npm install
npm run test:install        # one-off: downloads Chromium

npm run test:e2e            # everything, against dev
npm run test:smoke          # templates only — the fastest useful signal
npm run test:a11y           # axe, WCAG 2.1 AA
npm run test:visual         # screenshot comparison
npm run test:report         # open the last HTML report
```

## Choosing a target

`WP_BASE_URL` selects the environment; it defaults to **dev**, which is the only
one with real migrated content.

```bash
npm run test:e2e                                       # dev
WP_BASE_URL=http://localhost:8903 npm run test:e2e     # local Studio
```

Local holds six tours and nothing else, so most archive and taxonomy specs skip
there — that is expected, not a failure. Pointing `WP_BASE_URL` at
`southerndestinations.com` makes the config throw: production is not a test
target.

## How it is put together

| Path | What it does |
|---|---|
| `global-setup.js` | Resolves one live permalink per post type and taxonomy from the REST API, so no spec hardcodes a slug. Caches to `.resolved-routes.json`. |
| `fixtures/routes.js` | The route map. Each entry names the template it exercises — this is how you tell which of the 31 templates are covered. |
| `fixtures/base.js` | `test` with a PHP-error guard, a console guard, and the resolved routes. Import `test` from here, never from `@playwright/test`. |
| `utils/page-contract.js` | The structural invariants every page must satisfy, in one place. |
| `utils/dynamic-regions.js` | What is masked in screenshots and excluded from axe, each with its reason. |
| `utils/axe.js` | The axe wrapper and its failure formatting. |

Four projects: `desktop`, `mobile` (specs tagged `@responsive`), `a11y`, and
`visual`.

## Things worth knowing before you change a spec

**Scope result counts to `main`.** The header's mega menus contain their own
query loops, so a page-wide `.wp-block-post` count returns eighteen items on a
search that matched nothing. Use `results( page )` from `page-contract.js`.

**The mega menu opens on hover and on focus, not on click.** A synthetic click
moves the pointer first, so hover opens the menu and the click immediately
toggles it shut. Assert on `hover()` and `focus()`.

**The header search input is hidden until its toggle is clicked** — it carries
`aria-hidden="true"` and `tabindex="-1"` until then.

**Do not add `extraHTTPHeaders`.** Playwright applies them to every request the
page makes, including cross-origin ones, which breaks CORS preflight on the WETU
itinerary iframe and Google Fonts and produces console errors on pages that are
fine.

**Visual baselines are platform- and environment-specific.** Snapshots are keyed
by project and platform. A baseline taken on macOS against dev will not match
Linux CI against local.

## Known failures

These are real defects the suite found, not harness problems. They are expected
to be red until fixed, and each is written up in
`.github/reports/playwright-harness-2026-09-17.md`.

| Spec | Finding |
|---|---|
| `front page renders` | Dev's front page renders no `main` landmark, although `templates/front-page.html` has one — a Site Editor override in the database is shadowing the theme file. |
| `single review renders` | `templates/single-review.html` has no header or footer template part, so review singles render with no site chrome at all. |
| `tour archive lists results` | `/tours/` renders no listing inside `main`. |
| `accommodation archive lists results` | `/accommodation/` renders no listing inside `main`. |
| most of the `a11y` project | 12 distinct axe rules across the template set, led by colour contrast. |

Fix the defect rather than loosening the assertion. If something must be
deferred, say so in the report and leave the spec red.
