# End-to-end tests — sd-theme-2026

Rendering, accessibility, responsive and visual checks for the block theme, plus a
signed-in check for Site Editor overrides and an opt-in parity comparison with live.
Plugin behaviour lives in the `sd-enhancements-2026` repo; the split follows the
deactivation test in `AGENTS.md` — if turning the theme off would not break it,
it is tested there, not here.

## Running

```bash
npm install
npm run test:install        # one-off: Chromium, Firefox and WebKit

npm run test:e2e            # every default project, against dev
npm run test:smoke          # @smoke on desktop — the fastest useful signal
npm run test:templates      # desktop, templates/ only
npm run test:parts          # desktop, parts/ only
npm run test:forms          # desktop, forms/ only
npm run test:responsive     # responsive + tablet + mobile
npm run test:a11y           # axe, WCAG 2.2 AA, plus keyboard and structure
npm run test:editor         # signed in: Site Editor overrides (needs credentials)
npm run test:parity         # opt-in: compare with live (reads production)
npm run test:visual         # screenshot comparison — no baselines yet
npm run test:report         # open the last HTML report
```

Two commands are **deliberate, separate actions**, never a fix for a red run:

- `npm run test:a11y:baseline` records the accessibility baseline.
- `npm run test:visual:update` records visual baselines.

Both lock in whatever the site renders at that moment. Run them only once the
known failures below are fixed, or they bake the broken state in.

## Choosing a target

`WP_BASE_URL` selects the environment. It is read from `.env` at the theme root,
and defaults to **dev**, which is the only environment with real migrated content.

```bash
cp .env.example .env        # then fill in — .env is gitignored

npm run test:e2e                                       # dev
WP_BASE_URL=http://localhost:8903 npm run test:e2e     # local Studio
```

`.env` holds three values; `.env.example` documents them:

| Variable | Used by |
| --- | --- |
| `WP_BASE_URL` | Everything. Unset means dev. |
| `WP_ADMIN_USER`, `WP_ADMIN_PASS` | `setup` and `editor` only. Leave them empty and both projects drop out of the run entirely. |

`utils/env.js` loads `.env` without a dependency. A variable already set in the
shell wins over the file, so a one-off `WP_BASE_URL=… npm run …` still works.

Local holds six tours and nothing else, so most archive and taxonomy specs skip
there — that is expected, not a failure. Pointing `WP_BASE_URL` at
`southerndestinations.com` makes the config throw: production is not a test
target. The `parity` project reads live on purpose, but never uses it as
`baseURL`.

## Projects

Breakpoints follow the org standard (`lightspeedwp/.github`,
`agents/testing-agent/CROSS_BROWSER_AND_RESPONSIVE_TESTING.md`). Every project
is Chromium unless it says otherwise.

| Project | Runs | Viewport | For |
| --- | --- | --- | --- |
| `desktop` | `templates/`, `parts/`, `forms/` | 1280×800 | The full functional sweep. |
| `tablet` | the same, `@responsive` only | 768×1024 | Responsive adaptation. |
| `mobile` | the same, `@responsive` only | Pixel 7 profile at 375×667 | Responsive adaptation, touch emulation. |
| `wide` | the same, `@responsive` only | 1920×1080 | Opt-in: `SD_RUN_WIDE=true`. |
| `firefox` | the same, `@smoke` only | 1280×800, Gecko | Engine coverage. |
| `webkit` | the same, `@smoke` only | 1280×800, WebKit | Engine coverage. |
| `responsive` | `responsive/` | drives its own | Horizontal overflow at all four widths, 44×44 header touch targets, 120% and 150% text scaling. |
| `a11y` | `a11y/` | 1280×800 | axe scans of every template, keyboard paths, heading and landmark structure. |
| `visual` | `visual/` | 1280×800 | Screenshot comparison. No baselines committed; excluded from CI. |
| `setup` | `auth.setup.js` | — | Signs in once, saves the session to `.auth/admin.json`. Only exists when credentials are set. |
| `editor` | `editor/` | 1280×800 | Signed in. Lists every theme template and part that a Site Editor database override is shadowing. **Depends on `setup`**, so Playwright signs in first. Only exists when credentials are set. |
| `parity` | `parity/` | 1280×800 | Opt-in: `SD_RUN_PARITY=true`. One worker, read-only. Checks that live's navigation routes resolve on the rebuild, that sampled pages keep their `h1` and their substance, and lists live navigation labels the rebuild lacks. |

`firefox` and `webkit` run `@smoke` rather than everything: a full pass per
engine triples the load on a shared dev host for very little signal.

## How it is put together

| Path | What it does |
| --- | --- |
| `global-setup.js` | Resolves one live permalink per post type and taxonomy, and one page per custom page template in use, from the REST API — so no spec hardcodes a slug. Caches to `.resolved-routes.json`, which is gitignored. |
| `auth.setup.js` | Signs in for `editor`. The saved state in `.auth/` is a credential and is gitignored. |
| `fixtures/routes.js` | The route map. Each entry names the template it exercises — this is how you tell which templates are covered. |
| `fixtures/base.js` | `test` with a PHP-error guard, a console guard, and the resolved routes. Import `test` from here, never from `@playwright/test`. |
| `utils/env.js` | Loads `.env`; `hasAdminCredentials()` decides whether `setup` and `editor` exist. |
| `utils/page-contract.js` | The structural invariants every page must satisfy, in one place. |
| `utils/dynamic-regions.js` | What is masked in screenshots and excluded from axe, each with its reason. |
| `utils/axe.js` | The axe wrapper, the baseline comparison and its failure formatting. |
| `utils/parity.js` | Live-side helpers for `parity`: path normalisation, the navigation sample, the content contract, the pause between production requests. |

## The accessibility baseline

The gate compares each page against `a11y/a11y-baseline.json` rather than
demanding zero violations — the org rule, because an absolute gate fails on day
one against existing debt and then gets switched off.

- **No baseline file:** the scans **report and do not fail.** Each page's
  violations go to stderr with a ⏸ marker. This is the state today. It matches
  the `sd-enhancements-2026` gate, so CI is not red before a baseline has ever
  been recorded.
- **Baseline present:** a rule the baseline does not list, or more nodes than it
  records, fails the test. Fewer nodes passes and prints ✅, so a fix is visible.
- **Recording it:** `npm run test:a11y:baseline` writes `a11y-baseline.json`.
  That file is **committed**, like the plugin's. It is keyed by route for static
  pages and by post type or taxonomy for resolved ones, so it does not go stale
  when the newest tour changes.

The structural tests in `templates.a11y.spec.js` (heading hierarchy, landmarks)
and everything in `keyboard.spec.js` are absolute assertions and fail regardless.

## Things worth knowing before you change a spec

**Scope result counts to `main`.** The header's mega menus contain their own
query loops, so a page-wide `.wp-block-post` count returns eighteen items on a
search that matched nothing. Use `results( page )` from `page-contract.js`. It
also skips FacetWP's `li.facetwp-no-results`, which sits inside the post
template.

**Scope "is there a way to do X" checks to `main` too.** The header navigation
carries "Contact Us" on every page, so a page-wide search for an enquiry control
can never fail.

**The mega menu opens on hover, and from the keyboard.** Dev's Ollie build opens
a toggle on Enter; the local build opens on focus, where Enter would close it
again. The keyboard specs focus first and press Enter only if the menu is still
closed. A synthetic *click* moves the pointer first, so hover opens the menu and
the click immediately toggles it shut — don't assert on `click()`.

**The header search input is hidden until its toggle is clicked** — it carries
`aria-hidden="true"` and `tabindex="-1"` until then.

**Gravity Forms validates over AJAX.** The contact form is `novalidate` and posts
through a hidden iframe, so validation markup arrives after the click. Poll for
it. `forms/enquiry.spec.js` **never submits valid data** — an empty submission
fails validation before any feed runs, so nothing reaches Salesforce. Keep it
that way.

**The REST API names block page templates without an extension**
(`page-no-title`) and legacy PHP templates by filename (`brands.php`).
`routes.pageTemplate()` accepts either.

**Do not add `extraHTTPHeaders`.** Playwright applies them to every request the
page makes, including cross-origin ones, which breaks CORS preflight on the WETU
itinerary iframe and Google Fonts and produces console errors on pages that are
fine.

**Visual baselines are platform- and environment-specific.** Snapshots are keyed
by project and platform. A baseline taken on macOS against dev will not match
Linux CI against local.

## CI

`.github/workflows/e2e.yml` is **`workflow_dispatch` only** until the full test
pass — restore `pull_request` and `push` then (the workflow comment says so). It
runs `desktop`, `tablet`, `mobile`, `responsive` and `a11y` as a matrix, plus an
`editor` job that needs the `WP_ADMIN_USER` / `WP_ADMIN_PASS` repository secrets
and only runs for same-repository branches. Without the secrets, the job skips
with a notice. As of 2026-09-23 the repository has **no secrets set**.

## Known failures

From the 2026-09-23 shakedown against dev. These are defects in the product,
not in the tests. Each is written up, grouped by template, in
`.github/reports/sd-theme-e2e-shakedown-2026-09-23.md` (workspace root).

| Spec | Project | Finding |
| --- | --- | --- |
| `single review renders` | desktop, tablet, mobile | `templates/single-review.html` has no header or footer part — review singles render with no site chrome. |
| `tour archive lists results` | desktop | `/tours/` renders no listing inside `main`. |
| `accommodation archive lists results` | desktop | `/accommodation/` renders no listing inside `main`. |
| `front page / accommodation archive does not scroll horizontally` (1280px) | responsive | The slider-frame "next" arrow sits 20px outside the viewport — `assets/styles/core-group.css`. |
| `interactive controls in the header meet the minimum target size` | responsive | At 375px: two Trustpilot links (90×24, 120×24), the search toggle (40×50), and "Open menu" (36×36). |
| `single tour heading hierarchy has no skipped levels` | a11y | `h2 → h4`: the compact card patterns hardcode `level: 4`. |
| `landmarks are present and navigations are distinguishable` | a11y | The footer's terms navigation has no accessible name. It exists only in dev's database copy of the footer part. |
| `no template is overridden in the database` | editor | 8 templates shadowed on dev. |
| `no template part is overridden in the database` | editor | 5 parts shadowed on dev, including `header` and `footer`. |
| `the front page resolves to a template the theme provides` | editor | `front-page` renders from the database override. |
| `sampled pages keep their heading and their substance` | parity | `/tours` and the four `/search/tours/<style>` routes have different `h1`s from live. |
| every axe scan | a11y | 13 rules across 29 pages, led by `color-contrast`. **Reported, not failing**, until a baseline is recorded. |

Fix the defect rather than loosening the assertion. If something must be
deferred, say so in the report and leave the spec red.
