# CLAUDE.md

This theme's agent guidance lives in **[AGENTS.md](AGENTS.md)** — read it first.

@AGENTS.md

Key companions:

- **[DESIGN.md](DESIGN.md)** — design sources and the token → `theme.json` pipeline.
- **[CONTRIBUTING.md](CONTRIBUTING.md)** — workflow, git topology, quality bar.
- **[PATTERNS.md](PATTERNS.md)** — the pattern library, written for editors.
- **[inc/README.md](inc/README.md)** — what may and may not live in `inc/`.

Four things to internalise before any work:

1. **Theme = design, plugin = behaviour.** If deactivating the theme would break it, it
   belongs in the companion Southern Destinations block plugin — even when it surfaces
   visually. The whole point of the rebuild is unwelding logic from the theme.
2. **This is a rebuild, not a redesign.** The existing design is preserved. The **live site**
   is the design source of truth; Figma is a rudimentary token layer that is silent more
   often than not. Don't invent visual decisions.
3. **There is no WooCommerce.** This is a tour operator site. The theme was derived from
   `kwv-theme-2026`, which is a wine-and-spirits commerce theme — if you find commerce markup
   anywhere, it is a leftover to delete, not a feature to extend.
4. **Scope is a ceiling.** Estimate 3164 funds 22 line items. Out-of-scope work goes to the
   Change-Control Register, not into the build.

Tokens over hardcoding: reference `theme.json` presets by **numeric slug**. Never paste raw
hex or font names into authored files. Run `theme-orphaned-refs` after any token change.

WP-CLI on this install needs a raised memory limit and has no MySQL (SQLite):

```bash
php -d memory_limit=1024M $(which wp) <command>
```
