# Contributing — sd-theme-2026

> Workflow, git topology and the quality bar for the Southern Destinations 2026 block theme.
> Read [AGENTS.md](AGENTS.md) first; the token pipeline is in [DESIGN.md](DESIGN.md).
>
> Portable copy of the workspace `../../../CONTRIBUTING.md`. If you have the full workspace
> checked out, that original is authoritative and the OpenSpec flow lives there.

---

## 1. Scope control (the project's defining rule)

The approved **Estimate 3164** is a **ceiling**, not a starting point. Each Linear issue
carries its line value so the budget stays reconcilable.

Before building anything, ask: *is this on the 22 line items?* If not, it does not get built —
it gets recorded in the **Change-Control Register** for separate estimation, and flagged.

The `agency-scope-change-control` skill covers the register format and the escalation path.

**Expect creep during page conversion.** Every "while you're in there…" is a register entry.

---

## 2. Theme and plugin development

### The boundary that matters most

> **If deactivating the theme would break it, it belongs in the custom block plugin.**

The theme carries `theme.json`, styles, patterns, template parts and templates — **design
only**. The plugin carries the six custom blocks and nine PHP modules — all proprietary
logic. The rebuild exists to unweld these; don't recreate the coupling.

Concretely, these are **plugin** work even though they surface visually: post-type
registration and expiration, WETU import, form handlers, breadcrumb filters, search
integration, Salesforce/CRM routing, sticky-header and mobile-menu behaviour, the Instagram
feed, and the footer's conditional-CTA rule.

### Lint

```bash
phpcs --standard=WordPress .
phpcbf --standard=WordPress .

# Syntax check every PHP file
find . -name '*.php' -not -path './.claude/*' -exec php -l {} \;
```

No `package.json` or `composer.json` ships yet. Don't add build tooling
(Webpack/Vite/Docker/Storybook) or npm/Composer dependencies without explicit justification —
this is a block theme, and the editor is the build system.

---

## 3. Git

- This directory is **its own git repository**
  ([lightspeedwp/sd-theme-2026](https://github.com/lightspeedwp/sd-theme-2026)). The
  workspace root is *not* a git repo. Commit theme work here.
- **Don't commit or push unless asked.** If on the default branch, branch first.
- **Never commit secrets** — `.mcp.json` (live Bearer token), `wp-config.php`, app passwords.
  Never copy them into this repo, a zip, or a doc.
- Keep [CHANGELOG.md](CHANGELOG.md) current.
- `.claude/skills/` and `.claude/agents/` here are **copies** of the workspace `.agents/`
  bundle. Fix skills in the master bundle and re-sync; don't diverge them here.

---

## 4. Quality bar

Priority order: **security → accessibility → correctness → maintainability → performance.**

- Escape all PHP output with the text domain `sd-theme-2026`; semantic `tagName`s; correct
  heading hierarchy.
- **Exactly one `<main>` landmark per template.**
- **Keyboard support and focus traps on every modal and overlay** — a modal you can't escape
  by keyboard is a defect.
- Prefer `theme.json` presets by numeric slug over inline styles or PHP.
  `theme-orphaned-refs` → 0 orphans.
- Styling belongs in `styles/**` JSON partials; `assets/styles/*.css` is a last resort with a
  comment saying which limitation forced it.
- No hardcoded `ref` IDs on navigation blocks, uploads URLs on images, or form IDs with
  inline colours — those are per-install values.
- Small, reasoned diffs over large rewrites.
- **Verify before claiming "done."** Name the check you ran.

---

## 5. Environment gotchas

- WP-CLI needs `php -d memory_limit=1024M $(which wp) …` — the default OOMs.
- **No MySQL.** SQLite install; `wp db query` can never work. Use `wp eval` / `wp eval-file`.
- LSX plugin deprecation notices go to **stderr** — harmless, redirect with `2>/dev/null`.
- New pattern and style files don't register until the pattern transient is cleared:
  `wp transient delete --all --network`.
- **Site Editor edits live in the database and shadow theme files.** If editing a template
  file has no visible effect, that's why → `wp-db-override-reconciliation`.
