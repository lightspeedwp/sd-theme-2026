# AGENTS.md — sd-theme-2026

> Orchestration guide for AI agents and developers working in this theme. **Read this first.**
> Companions in this repo: [DESIGN.md](DESIGN.md) (tokens and the design pipeline),
> [CONTRIBUTING.md](CONTRIBUTING.md) (workflow, git, quality bar),
> [PATTERNS.md](PATTERNS.md) (the pattern library, for editors).
>
> This repo is a **portable copy** of the project's operating guidance so the theme can be
> worked on standalone. The workspace originals live one level up at
> `../../../AGENTS.md`, `../../../DESIGN.md`, `../../../CONTRIBUTING.md` — if you have the
> full workspace checked out, those are authoritative and win on any conflict.

---

## What this is

**Southern Destinations 2026** — the custom WordPress block theme for
[southerndestinations.com](https://www.southerndestinations.com/), a South African inbound
tour operator. This directory is its own git repository
([lightspeedwp/sd-theme-2026](https://github.com/lightspeedwp/sd-theme-2026)); commit theme
work here, not in the workspace root.

| | |
|---|---|
| **Live site** | https://www.southerndestinations.com/ — **the design source of truth** |
| **Dev site** | https://southerndestinations.lightspeedwp.dev/ |
| **Local site** | http://localhost:8903 (WordPress Studio, WP 7.0.x, PHP 8.3, SQLite) |
| **Derived from** | [lightspeedwp/kwv-theme-2026](https://github.com/lightspeedwp/kwv-theme-2026) — architecture only, no brand values |
| **Sibling reference** | [lightspeedwp/ati-theme-2026](https://github.com/lightspeedwp/ati-theme-2026) — another Tour Operator block theme |
| **Design system** | [Southern Destinations Design System (Figma)](https://www.figma.com/design/z4Nr33MGjtdqPBqxitobSD/Southern-Destinations-Design-System) — rudimentary, a light token layer only |

The theme is one of four deliverables in the *Website Rebuild 2026* project (Estimate 3164).
The others — a custom block plugin, the Tour Operator plugin upgrade, and JSON-LD structured
data — are **not** in this repo.

---

## The one rule that shapes everything

> **If deactivating the theme would break it, it belongs in the companion block plugin.**

This rebuild exists to unweld business logic from the theme. The previous stack
(`lsx` + `sd-lsx-child`) welded them together; do not recreate the coupling.

**Theme = design.** `theme.json`, `styles/**`, `patterns/`, `parts/`, `templates/`, and the
minimum `functions.php` needed to register those.

**Plugin = behaviour.** Post-type registration, post expiration, WETU import, form handlers,
breadcrumb filters, search integration, Salesforce/CRM routing, sticky-header and mobile-menu
filters, Instagram feed, conditional-CTA rules. All of these surface visually — that does not
make them theme work.

`inc/` exists for design-only concerns (styling third-party plugin markup). It is **not** a
back door for logic. See [inc/README.md](inc/README.md).

---

## Scope discipline

The approved **Estimate 3164** is a **ceiling**, not a starting point. R295,650 ex VAT across
22 line items, launch **2026-09-30**.

**The existing design is preserved.** Current templates and styling are translated into
blocks — a rebuild with a light visual refresh, **not a redesign**.

**Non-goals — do not build these:**

- **No redesign.** New layouts, components or interactions need separate approval.
- **No bespoke mobile/tablet designs** — responsive adaptations only.
- **No sitewide copy rewrite.** Content is migrated, not rewritten.
- **No commerce.** There is no WooCommerce on this site and no shop templates.
- **No booking engine.** Enquiry-led forms only, as today.
- **No chatbot** and no AI features beyond the separately-quoted structured-data line.
- **No full performance engineering** — a baseline pass only.

If you discover work outside the 22 line items: **do not build it.** Record it in the
Change-Control Register for separate estimation and flag it. "It would be nice", "it's almost
free" and "the design implies it" are **not** authorisation. Expect creep during page
conversion — every "while you're in there…" is a register entry.

When in doubt about scope, **stop and ask.**

---

## Source-of-truth precedence

When sources conflict, later entries lose:

1. **Estimate 3164** and its line items
2. The rebuild spec — *Block Theme and Plugin Specification*, §I–VI
3. **The live site** — structure, content model, and design intent
4. The 2019 spec, for functional scope replication
5. The Design System Figma file — *rudimentary*; a light token layer, not a full system
6. Clearly-labelled assumptions

> **Design motivation comes from the live site, not from Figma.** Where Figma is silent —
> which is often — the live site decides. Don't invent visual decisions.

---

## Layout

```
sd-theme-2026/
├── theme.json            # Global Styles + settings. Numeric token scales. Schema wp/6.9.
├── style.css             # Theme header + CSS reset/base. Tokens only, no raw values.
├── functions.php         # Setup, block styles, pattern categories.
│                         #   PHP namespace `SdTheme2026`, text domain `sd-theme-2026`.
├── inc/                  # Design-only modules (third-party markup styling). Usually empty.
├── templates/            # Core WP templates + Tour Operator archives/singles/taxonomies.
├── parts/                # header, footer, sidebar, mega-menu, dropdown-menu, mobile-menu,
│                         #   single-hero.
├── patterns/             # Block patterns (.php). Slugs namespaced `sd-theme-2026/*`.
├── styles/               # Block & section style variations. Scanned recursively.
│   ├── blocks/<block>/   #   Variations scoped to one block type.
│   └── sections/         #   `core/group` "section" compositions (+ cards/ subfolder).
├── assets/styles/        # Per-block CSS, lazy-enqueued by filename (core-*.css → core/*).
└── .claude/              # Ported skills + agent personas — see "Agent assets" below.
```

> **`styles/` layout rule:** a variation scoped to a single block type lives in
> `styles/blocks/<block>/`; a `core/group` "section" composition lives in `styles/sections/`.
> Registration is by file (slug + blockTypes + title), scanned **recursively** — folders are
> organisational only, but **basenames must stay unique** across the tree (core dedupes
> parent/child variations by basename).

---

## Conventions

### theme.json & tokens

- `theme.json` is **generated from the token map** by the extractor skills — don't hand-edit
  token values; re-extract. See [DESIGN.md](DESIGN.md).
- Colour is referenced **directly by palette slug** (`var:preset|color|brand-500`,
  `…|contrast`, `…|neutral-700`). The optional semantic-token layer
  (`settings.custom.color`) + a `styles/dark.json` mirror is **not adopted** — only introduce
  it (via `theme-color-token-enforcer`) if dark mode is approved. It is not funded here.
- Schema is `https://schemas.wp.org/wp/6.9/theme.json`, `version: 3`. Tokens use **numeric
  slugs** (`100`/`200`/…), not named sizes.

### Authored files (patterns / templates / parts / styles)

- Reference **preset tokens by numeric slug**: `var:preset|color|<slug>`,
  `var:preset|spacing|<slug>`, `var:preset|font-size|<slug>`,
  `var:preset|font-family|heading|body`, `var:custom|font-weight|…`,
  `var:custom|line-height|…`.
- **No** raw `#hex` / `rgb()` / font-family / raw font-weight literals. In raw `css` strings
  use `var(--wp--preset--…)` / `var(--wp--custom--…)`.
- ⚠️ **On a *dynamic* block, `var:custom|…` is silently dropped — write
  `var(--wp--custom--…)` instead.** A static block's `style` object is resolved by the editor
  at save time, so the shorthand becomes an inline style in the saved markup. A dynamic block
  (`core/post-title`, `core/term-name`, `core/query-title`, `core/post-excerpt`,
  `core/post-terms`, `core/post-author-name`, `core/navigation`) has no saved markup: the
  server-side style engine resolves it, and that only expands `var:preset|…`, and only for
  properties declaring `css_vars` — `fontWeight`, `lineHeight`, `fontStyle`, `textTransform`
  and `letterSpacing` declare none
  (`wp-includes/style-engine/class-wp-style-engine.php:292-370`). Measured on local
  2026-08-28: `wp_style_engine_get_styles()` given `fontWeight: var:custom|font-weight|semi-bold`,
  `lineHeight: var:custom|line-height|heading` and `fontSize: var:preset|font-size|500`
  returns **only** `font-size:var(--wp--preset--font-size--500);`. No notice, no fallback —
  the declaration is simply absent and the block inherits. `var:preset|color|…` and
  `var:preset|font-size|…` are safe on dynamic blocks; those two do declare `css_vars`.
  The `var(--wp--custom--…)` form is still a token reference, so the rule above holds.
  → `patterns/safari-expert.php` (and the note in
  `patterns/card-media-overlay-term.php`, which no longer needs the workaround
  itself — its label weight moved to the card section style)
- Semantic HTML `tagName`s; correct heading hierarchy; keep templates/parts lean (no inline
  styles).
- **Every template must have exactly one `<main>` landmark.** The sibling ATI theme shipped
  without one on its Tour Operator templates and had to retrofit it — don't repeat that.
- **Never hardcode a `ref` ID on a `wp:navigation` block** or a Gravity Forms `formId` with
  inline colours. Those are per-install values that no deploy step can fix; they were the
  single largest source of breakage inherited from the KWV base. **Attachment IDs are the
  same** — `"id":52466` and `wp-image-52466` survive only because dev is deployed to live
  wholesale.
- **Uploads URLs are the exception to that**, and they are written out literally as core
  writes asset URLs. The go-live deployment runs a find-and-replace over the dev host by
  convention, so a `$sd_uploads`-style variable buys nothing — it was still a hardcoded dev
  host, one indirection away.

### Styling lives in JSON (block & section styles)

**Rule:** every style belongs in a block-style or section-style JSON partial under
`styles/**` — including its `css` field for selector-level rules. Author CSS in
`assets/styles/*.css` **only** for the parts a JSON style genuinely cannot express. This
keeps one source of truth, renders the style in the editor, and keeps the cascade clean.

What goes where:

- **Structured props** (color, border, radius, typography, spacing) and **native
  pseudo-states** (`:hover`/`:focus` as `styles` keys) → the JSON `styles` object. These
  render in the editor.
- **Selector-level rules** that aren't structured props (descendant selectors,
  layout/`display:flex` on generated wrappers, resting overrides that must out-specify a
  plugin) → the JSON style's **`css` field** (`&` = variation root). The `css` field loads in
  the editor too.
- **`assets/styles/*.css`** → last resort, only for what the `css` field provably can't hold.
  The `css` field is **`:where()`-zero-specificity** (use `!important` to win), **strips
  `:hover`/`:first-child`**, **mangles `content:""`**, and **drops comma `&` selectors**. So
  `:hover`/`:focus` flips and `::after`/`::before` icons (which need `content`) belong in
  enqueued CSS — nothing else should.

When you must put something in a `.css` file, add a comment saying which limit forced it, and
link back to this rule. The `wp-blockstyle-css-field` skill has the full matrix.

> ⚠️ **`blockGap` is the exception: it never goes in a variation JSON.** Put it on the block
> markup — `"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}` in the pattern, part or
> template. Measured on WP 7.1, 2026-08-27:
>
> - **`styles.spacing.blockGap` on the variation's own wrapper is emitted nowhere** — not in
>   the editor and not on the front end. Core's variation stylesheet generator skips it, and
>   the element gets no `wp-container-*` class, so the declaration is inert. Eighteen of this
>   theme's nineteen variations carried one; all eighteen were dead.
> - **`styles.blocks.<block>.spacing.blockGap` (a nested block) is emitted on the front end
>   only.** The front end writes the full layout set — `…-is-layout-flex{gap}`,
>   `…-is-layout-flow > *{margin-block-start}`, `> :first-child`, `> :last-child`,
>   `…-is-layout-grid{gap}`. The editor writes **none of it** (`grep -c 'is-layout-'` over the
>   editor's variation CSS returns 0), so the canvas silently falls back to theme.json's
>   `:root :where(.is-layout-flex){gap:var(--wp--preset--spacing--60)}`. That is what made
>   editor spacing disagree with the front end.
>
> Two related asymmetries to know about, both inherent to core and neither worth working
> around: the variation selector is `.is-style-X--N` (0,2,0) on the front end but
> `.wp-block-group.is-style-X-‹uuid›` (0,3,0) in the editor, and `style.css` sits *after*
> global styles on the front end but *before* them in the editor. Don't write authored CSS
> that depends on either winning.

> ⚠️ **`spacing.margin` belongs to page-section styles only — top and bottom, and only
> where it is genuinely necessary.** Never declare a margin on a block that sits inside a
> parent carrying a `blockGap`. Core's layout contract is *the parent owns the spacing and
> its children have zero margins*; a child margin is fighting that contract, and it wins in
> the editor while losing on the front end. Measured on dev, WP 7.1, 2026-08-27:
>
> - An explicit `blockGap` compiles to `.wp-container-‹hash› > * { margin-block: 0 }` in
>   **`core-block-supports-inline-css`**, and a variation's margin compiles to
>   `:root :where(.wp-block-heading.is-style-X--N)`. Both sit at **(0,1,0)** — `:root`
>   contributes (0,1,0), `:where()` contributes nothing — so **source order alone decides
>   the winner, and WordPress inverts it between the two environments**:
>
>   | | Container rule | Variation rule | Winner | Child `margin-bottom` |
>   |---|---|---|---|---|
>   | Front end | **55** | 50 | container | `0px` |
>   | Editor | 125 | **142** | variation | `16.814px` |
>
> - So the child's margin **survives in the editor and collapses** with the next sibling's
>   `margin-block-start`. The visible gap becomes `max(gap, childMargin)` instead of `gap`:
>   every gap at or below the child's margin renders identically, and the control appears
>   dead. With `margin-bottom: spacing|20` on a heading, `None`/`XXS`/`XS`/`S` all render at
>   16.8px in the canvas and only `M` upwards begins to move — while the front end is exact.
>   Where the parent's gap is *smaller* than the child's margin (e.g. `spacing|10` in
>   `parts/mega-menu-tours.html`) the gap is swallowed whole and the control does nothing at
>   all.
> - The margin is **already inert on the front end** wherever the parent sets a `blockGap` —
>   `core-block-supports` zeroes it. So these declarations buy nothing on the front end and
>   cost correctness in the editor.
> - `margin: 0` is the same hazard pointing the other way: it *kills* a parent's gap in the
>   editor rather than flooring it. The theme's six `top: 0` section styles only escape
>   because every parent that holds them sets `blockGap: 0`, so both environments agree.
>   Give any of those parents a real gap and it will die in the canvas.
>
> **The rule:** express spacing as `blockGap` on the parent. Reach for `spacing.margin` only
> on a page-section style, only `top`/`bottom`, and only where no parent gap can express it —
> and say in the style's `description` why.

> ⚠️ **Structure belongs in markup, not in a `css` field.** A section style's `css` field is
> for what blocks genuinely cannot express — absolute overlays, `::before`/`::after` content,
> `overflow`, `!important` overrides of core block CSS. It is **not** the place for layout.
> If you find yourself writing a hand-rolled `__`-suffixed class in a pattern so a `css` rule
> can target it, the rule almost certainly has a block attribute. Measured 2026-08-27:
>
> - **Image crops are `aspectRatio`, never CSS.** `core/post-featured-image` and `core/image`
>   serialise `aspectRatio`, `height` and `scale` as *inline* styles on the `<img>`
>   (`wp-includes/blocks/post-featured-image.php:46-65`). A `css`-field `height` compiles to
>   `:root :where(…)` at (0,1,0) and **loses** to the block library's
>   `.wp-block-image img{height:auto;width:auto}` at (0,2,0) — which is why this theme once
>   carried ~100 lines of (0,3,0) selectors to win a fight it never needed to have.
> - **A fixed-ratio row is `core/columns`.** `core/column`'s `width` attribute compiles to
>   `flex-basis`, so column percentages are markup. Core stacks columns below 782px by
>   itself — don't hand-write the media query.
> - **`core/group` has no `typography.textAlign` support.** Set alignment on each child
>   block; the post-`*` blocks all support it. Padding, gap, colour, font size, font style,
>   line height and borders are all block attributes.
> - **`@media` is silently unwrapped in a `css` field** — the query is dropped and its rules
>   are promoted to unconditional ones. A rule containing `content:` is dropped whole.
>   Responsive and pseudo-element rules therefore live in `assets/styles/`, keyed off core's
>   own classes (`.wp-block-read-more`, `.wp-block-post-terms.taxonomy-post_tag`), never off
>   a hand-written helper class.

### PHP (`functions.php`, `inc/`, `patterns/*.php`)

- Escape **all** output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`) and include the
  text domain `sd-theme-2026` in every translation call.
- **Patterns follow core's form exactly.** Twenty Twenty-Four and Twenty Twenty-Five were
  measured for this (155 pattern files); match them, not a house style:
  - Wrap every literal copy string, inline at the point of use — `esc_html_e()`,
    `esc_attr_e()`, and `esc_html_x()` / `esc_attr_x()` when the string is short or its role
    is not obvious to a translator. Core uses the `_x` forms heavily; in TT4 they outnumber
    plain `esc_html_e()` 110 to 31.
  - **Never `echo esc_html__()`.** Core does this zero times; the `_e` form is the whole
    reason it exists.
  - **No top-level variables holding literals** — copy, URLs, SVGs, config strings. Core's
    patterns declare none. Write the value where it is used.
  - **No loops and no computed markup.** Core's patterns contain not one `foreach`. Write the
    repetitions out; a pattern is block markup that happens to live in a `.php` file. If the
    markup genuinely must be built per request, it is not a pattern — it is a Query Loop, a
    block binding, or plugin work.
  - **No `phpcs:ignore`.** An escaping suppression means the value should have been a literal
    in the markup instead. Inline SVGs are written out, not echoed from a variable, even when
    that means repeating them — add a ⚠️ comment when copies must stay in step.
  - A **guarded** runtime lookup with a fallback is the one thing that may hold a variable,
    because it needs a conditional: `get_post_type_archive_link()`, `get_option()`. An
    *unguarded* single call is inlined like core inlines `get_template_directory_uri()`.
  - Include `@package sd-theme-2026` in the header docblock, then exactly one blank line.
  - `phpcs --standard=WordPress patterns/` must be silent.
- Keep `functions.php`/`inc/` minimal — no plugin-like features in the theme.
- Block styles are registered in `functions.php`; per-block CSS in
  `assets/styles/core-<block>.css` is auto-enqueued only when the block is used — **don't
  break that filename → block-name convention.**
- Enqueue with `SdTheme2026\asset_version( $path )`, never a hardcoded version string and
  never the theme `Version` header (it isn't bumped per asset edit, so it goes stale).

### Patterns

- Register categories in `functions.php` (`sd-theme-2026/*`). Slugs are namespaced
  `sd-theme-2026/<name>`.
- Use the `pattern-extractor` skill to turn Figma sections or live-site pages into patterns.
- Build only patterns on the approved list — see [DESIGN.md](DESIGN.md).

---

## Agent assets

Ported into `.claude/` so this repo works standalone. In the full workspace these come from
`.agents/` instead; keep the workspace copy as the master and re-sync rather than diverging.

**Personas** (`.claude/agents/`): `theme-architect`, `themejson-completer`,
`wordpress-theme-styling-auditor`.

**Skills** (`.claude/skills/`):

| Skill | Use it for |
|---|---|
| `themejson-extractor-orchestrator` | Sequences the whole token extraction, in dependency order |
| `figma-themejson-{palette,spacing,typography,radius,shadow,style-variations}` | Individual token extractors |
| `theme-orphaned-refs` | After **any** token change → target 0 orphans |
| `themejson-completion` | Find Global Styles coverage gaps |
| `theme-color-token-enforcer` | Semantic-token / dark-mode layer — **opt-in, not funded** |
| `block-theme-audit` | Structure, a11y and escaping pass before a milestone |
| `pattern-extractor` | Figma section or live page → block pattern |
| `wp-blockstyle-css-field` | What the block-style `css` field can and cannot express |
| `wp-pattern-runtime-pitfalls` | Patterns that render wrong at runtime |
| `wp-thirdparty-markup-styling` | Styling FacetWP / Gravity Forms / SearchWP markup |
| `wp-db-override-reconciliation` | Site Editor DB overrides shadowing theme files |
| `wp-mcp-wpcli-ops` | WP-CLI and MCP operations against the sites |
| `agency-scope-change-control` | Logging out-of-scope discoveries to the register |

> Skills and personas are discovered **at startup**. After adding any, start a fresh session.

---

## Environment

### WP-CLI

The default memory limit OOMs on this install. Always run:

```bash
php -d memory_limit=1024M $(which wp) <command>
```

- **No MySQL exists.** SQLite install — `wp db query` fails with
  `env: mysql: No such file or directory` and always will. Use `wp eval` / `wp eval-file`.
- LSX plugin deprecation notices go to **stderr**, so they don't corrupt piped output.
  Redirect with `2>/dev/null` for clean parsing.
- **DB overrides shadow theme files.** Anything edited in the Site Editor lives in the
  database and wins over the theme file. Clear overrides before deploy, or the site renders
  the DB version. → `wp-db-override-reconciliation`
- New pattern/style files don't register until the pattern transient is cleared:
  `wp transient delete --all --network`.

### Plugin dependencies

The **LSX Tour Operator** plugin supplies the `accommodation`, `destination` and `tour` post
types and the `travel-style`, `accommodation-type`, `accommodation-brand`, `facility` and
`continent` taxonomies. TO Reviews and TO Specials supply `review` and `special`. Templates
for post types whose plugin is inactive are inert, not broken.

Also expected in the build environment: SearchWP Pro, FacetWP, Gravity Forms (+ Salesforce),
Soliloquy Slider, and Yoast SEO Premium (required for the structured-data line).

**No WooCommerce.** If you find yourself writing commerce markup, you have taken a wrong turn.

---

## Working agreements

1. **Respect scope.** Out-of-scope → Change-Control Register, not the build.
2. **Read before you write.** Read the live page before converting it.
3. **Theme = design, plugin = behaviour.** Apply the deactivation test.
4. **Tokens over hardcoding.** Reference `theme.json` presets by numeric slug. Never paste
   raw hex or font names into authored files. Run `theme-orphaned-refs` after token changes.
5. **Security & a11y are non-negotiable.** Escape all PHP output with the text domain;
   semantic `tagName`s; correct heading hierarchy; keyboard support and focus traps on every
   modal and overlay.
6. **Small, reasoned diffs.** No new build tooling (Webpack/Vite/Docker/Storybook) or
   npm/Composer deps without explicit justification.
7. **Never commit secrets.** Not `.mcp.json`, not `wp-config.php`, not Bearer tokens — in any
   repo, zip, or doc.
8. **Verify, then claim.** If something is untested or partial, say so. Name the check you
   ran. Don't report "done" without evidence.
9. **Don't commit or push unless asked.** If on the default branch, branch first.

---

## Quick reference

```bash
# WP-CLI (always with the memory flag)
php -d memory_limit=1024M $(which wp) theme list
php -d memory_limit=1024M $(which wp) transient delete --all --network   # re-register patterns

# Lint (phpcs + WPCS installed globally via composer)
phpcs --standard=WordPress .
phpcbf --standard=WordPress .

# Syntax check every PHP file
find . -name '*.php' -not -path './.claude/*' -exec php -l {} \;
```
