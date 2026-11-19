# Quickstart: Validating the Specials Landing Page

**Feature**: 001-specials-templates | **Date**: 2026-09-17

How to prove this feature works, end to end, against real data. Every check maps to a success
criterion in [spec.md](./spec.md). This is a validation guide — the implementation itself
lives in `tasks.md`.

---

## Where to run each check

Per AGENTS.md, and Assumption 9:

| Environment | Use for | Why |
|---|---|---|
| **Dev** — southerndestinations.lightspeedwp.dev | **Every content and behaviour check below.** | The only environment with migrated specials — 5 published, 37 expired |
| **Local** — localhost:8903 | `phpcs`, PHP syntax, pattern registration, editor load | Holds no specials at all; a green local run proves nothing about the list |
| **Live** — southerndestinations.com | The side-by-side comparison only (SC-012) | Design and behaviour reference |

Code is proved locally and deployed by pull — the dev MCP endpoint is content-only and cannot
write theme files.

## Prerequisites

- Tour Operator + `to-specials` 2.2 active; post type `special` registered; `/specials/`
  resolving.
- `sd-enhancements-2026` active — the query contract and the anchor both live there.
- Migrated specials present, with connections and validity dates populated.
- `sd-enhancements` carrying the single-offer redirect (see check 7). Do **not** enable
  `lsx_to_settings['special_disable_single']` — it is inert for this post type (research R-07).
- Pattern and style caches cleared after any pattern or `styles/**` change, or new files do
  not register:

```bash
php -d memory_limit=1024M $(which wp) transient delete --all --network
```

---

## Gate 0 — It loads at all

```bash
# From the theme root, on local.
phpcs --standard=.phpcs.xml.dist .
find patterns templates parts -name '*.php' -exec php -l {} \;
php -d memory_limit=1024M $(which wp) theme list --status=active
```

Expected: `phpcs` clean, no syntax errors, theme active with no fatals in
`wp-content/debug.log`.

---

## Check 1 — The list (SC-001, FR-005 to FR-008)

Load `/specials/` on dev.

| Expect | Fails if |
|---|---|
| Exactly 4 offers on page 1 | the `core/query` is not inheriting, or the plugin filter did not run |
| Pagination reaching every valid offer | — |
| **Zero** expired offers anywhere in the set | the validity filter is missing. Test this **before** retirement exists (check 8a), so the page is proved correct independently of post status |
| Offers ordered by booking-validity start date | ordering was not added to the query filter |
| An offer with **no** end date still listed | the meta query has no `NOT EXISTS` branch — the failure mode the query contract warns about |
| An offer with a **future** start date absent | — |

Count against the data rather than by eye. `wp db query` does not work on this SQLite
install and never will — use `wp eval` or `wp eval-file`, and write the counter as a
throwaway in the scratchpad rather than committing it to the theme:

```bash
php -d memory_limit=1024M $(which wp) eval-file /tmp/count-valid-specials.php 2>/dev/null
```

Remember that a top-level variable in an `eval-file` script is not a global — use `$GLOBALS`
if the script spans scopes. Then confirm the rendered count matches. SC-001 is a comparison
of two numbers, not an impression.

---

## Check 2 — The band (SC-003, FR-009 to FR-012)

For **every** offer in the migrated set, not a sample:

- Banner image fills the band as its ground.
- Offer name, centred.
- Meta row.
- **Complete** description — compare against the post's `post_content`, not against a
  paragraph count. No excerpt, no ellipsis, no "read more".
- Enquire button.

Zero bands missing an element that has data behind it. An offer with no banner image falls
back to a legible treatment, not a transparent or collapsed band.

If the band renders unstyled, the pattern's class names do not match
`styles/sections/cards/special-card.json` — that style targets `.special-card__media` and
`.special-card__body` specifically.

---

## Check 3 — The meta row (SC-004, FR-014 to FR-017)

Across the full set:

1. `Accommodation:`, `Travel Style:` and `Destinations:` labels present only where there is
   something under them.
2. **Zero** empty labels, orphaned separators or placeholder regions.
3. An offer with no connections of any kind renders **no** meta row.
4. A connection pointing at an unpublished or deleted item is omitted silently — no dead
   link, no empty list item.

Checks 3 and 4 test the **binding source's** behaviour, not the template's. Verify them
before the band's markup is finalised; if `lsx/post-connection` does not omit cleanly, the
omission has to be handled in `sd-enhancements` and that changes the shape of the band.

To construct case 4 deliberately, unpublish one connected accommodation on dev and reload.

---

## Check 4 — The links behind the meta row (FR-015)

Activate each meta link on an offer with connections:

- Accommodation → that accommodation's overlay.
- Destination → that destination's overlay.
- Travel style → that term's archive.

If the overlays from line 16 are not yet present, the links degrade to permalinks. Record
that as deferred verification, not as a pass.

---

## Check 5 — The enquiry trigger (SC-007, FR-018)

Not a spot check: on a page showing four offers, activate **each** band's button in turn.

- The enquiry surface opens each time.
- It identifies **that** offer — not the first band, not the last rendered.
- The button is reachable and operable by keyboard alone, with a visible focus indicator.

The first-or-last failure is the one this check exists for; testing only band 1 cannot
detect it. See [contracts/enquiry-trigger-contract.md](./contracts/enquiry-trigger-contract.md).

---

## Check 6 — Anchors (SC-008, FR-011)

```bash
grep -rn '#special-' patterns/ parts/ templates/
```

That list, plus the migrated navigation on dev, is the test set. Follow each
`/specials/#special-{slug}` and confirm it lands on that offer's band. Remember an anchor for
an offer on page 2 does not resolve from page 1 — load the page the offer is actually on.

Zero broken anchors.

---

## Check 7 — Individual offer URLs (SC-005, FR-024)

**Read [research R-07](./research.md) and
[contracts/redirect-contract.md](./contracts/redirect-contract.md) first.** The behaviour the
spec assigned to Tour Operator's "Disable Single" setting does not exist: the setting is inert
for `special`, and where it does apply it serves the homepage at 200. The redirect is ours,
authored in `sd-enhancements`. This check tests *our* redirect.

```bash
for slug in $(php -d memory_limit=1024M $(which wp) post list --post_type=special \
  --post_status=any --field=post_name 2>/dev/null); do
  printf '%s ' "$slug"
  curl -sS -o /dev/null -w '%{http_code} %{redirect_url}\n' "https://<host>/special/$slug/"
done
```

| Expect | Fails if |
|---|---|
| `301` with `redirect_url` = the specials archive, every URL | the redirect is not registered, or not firing for drafts |
| Exactly one hop (`curl -sSL … %{num_redirects}` = 1) | the target itself redirects |
| Zero URLs returning `200` while showing different content | the soft-404 behaviour slipped back in |
| Draft/expired offers redirect too, not error | the guard is scoped to published only |
| `templates/single-special.html` no longer exists | FR-024 |
| No redirect rule in the **theme** | `grep -rn 'template_redirect\|wp_redirect' patterns/ inc/ functions.php` → nothing |

Also confirm an editor can still open and preview an offer in wp-admin — the redirect must be
front-end only.

**Do not enable `special_disable_single`.** It does nothing for this post type, and a setting
that implies a mechanism nobody is running is worse than one left off.

**SC-006 (sitemap)** is verified here but implemented on the deployment/redirects line: fetch
`special-sitemap.xml` and confirm zero individual offer URLs. Live still advertises all five
despite its own 301 (verified 2026-09-17), so it will not fix itself.

---

## Check 8 — Empty state (FR-013)

Temporarily close the validity window on every published offer on dev, or filter the query to
an empty set:

- Banner, title and introduction still render.
- A clear message replaces the list.
- No broken pagination controls under an empty list.
- A page number past the end of the set gives a defined response, not an empty list under
  working controls.

Realistic rather than theoretical: 37 of 42 offers are currently expired.

---

## Check 8a — Retirement to draft (SC-006a, FR-026a)

**Test the write, not the schedule.** A scheduled action that exists is not evidence that it
fires correctly — and this is the only part of the feature that writes to published content
(spec Risk R-4).

On dev, take one published offer and close its window:

1. Set its `booking_validity_end` to yesterday. Reload `/specials/` — it must be **gone
   immediately**, from the read-time filter, before any scheduler has run. If it is still
   listed, the filter is broken and retirement is irrelevant; stop and fix check 1.
2. Run the sweep (or wait a cycle). The offer's status is now `draft`.
3. Confirm **nothing else changed** — description, connections, taxonomies, validity dates,
   featured and banner images all intact:

```bash
php -d memory_limit=1024M $(which wp) post get <id> --field=post_status 2>/dev/null
php -d memory_limit=1024M $(which wp) post meta list <id> 2>/dev/null | head -20
```

4. Extend the end date and republish. The offer returns to the page with its anchor and meta
   row exactly as before. This round trip exercises most of the contract in one pass.

Then the cases that are easy to get wrong:

| Case | Expect |
|---|---|
| An offer with **no** end date | Never retired, still listed |
| Running the sweep twice | No-op the second time, no error |
| An offer's end date moved forward after retirement | Republishes cleanly; no stale retirement fires against the new date |
| Scheduler has not run for days | **Every** offer that expired in the gap retires on the next run, not just the most recent |
| An editor has the offer open while it retires | The edit is not clobbered, and saving does not resurrect it |

Restore dev's data afterwards — this check deliberately mutates real content.

Note the dormant platform mechanism must stay dormant (FR-026b): `tour-operator` is vendor
code and is not patched. Confirm no hooks were added to it:

```bash
git -C ../../../../plugins/tour-operator status --short
```

---

## Check 9 — Access and responsiveness (SC-009, SC-010, FR-028 to FR-030)

- Automated accessibility scan: **no critical or serious violations**.
- Full keyboard operation; visible focus on every interactive element.
- Band text meets the project's contrast standard over its background image **at every
  viewport width**, including offers with light images — this is Risk R-3 and the reason the
  band's style carries a scrim that live does not have.
- No horizontal scrolling of the page body at **320px**.
- Single column at phone widths; every control reachable by touch.
- One `<main>` landmark. Heading hierarchy descends without skipping.

```bash
grep -c '<main\|"tagName":"main"' templates/archive-special.html patterns/template-archive-special.php
```

Exactly one across the two files combined — the landmark moves into the pattern with the
composition, and shipping both is the specific mistake this check catches.

---

## Check 10 — The editor (SC-011)

Open the Specials archive template in the Site Editor.

- No invalid-content or missing-content warnings.
- Bindings show their **authored fallback** text, not live data — that is correct and
  expected, not a defect (`sd-enhancements-2026/docs/blocks-and-bindings.md` §1, rule 2).
  Check the front end for real values.
- No DB override shadowing the theme file. Anything edited in the Site Editor lives in the
  database and wins over the theme file — clear overrides before deploy.

---

## Check 11 — Side by side against live (SC-012)

Open live `/specials/` and the rebuild at desktop and phone widths. Structure, order and
content match. Differences limited to the agreed light refresh of token values.

**Visual comparison is Zared's**, per AGENTS.md — do not drive it through an automated
fix-verify loop.

---

## Check 12 — Token discipline (FR-031, Constitution I–IV)

```bash
grep -rnE '#[0-9a-fA-F]{3,8}|rgb\(|font-family:\s*["A-Za-z]' \
  patterns/template-archive-special.php styles/sections/cards/special-card.json
```

Expected: nothing. Then, after any token change:

- Run the `theme-orphaned-refs` skill.
- Confirm no `var:custom|…` shorthand appears in a `style` object on a **dynamic** block —
  `core/post-title`, `core/post-terms`, `core/post-excerpt`, `core/post-content`. It is
  silently dropped there with no notice and no fallback. Write `var(--wp--custom--…)`
  instead, or let the section style carry it (Constitution III).

---

## Hand-over

Not done until: `phpcs` clean, both `CHANGELOG.md` files updated with the Linear issue tag,
the pattern transient cleared, checks 1–4, 6, 8a and 8–12 passing on dev, and checks 5 and 7
either passing or recorded with their reason and their owner. Dev content restored after check
8a. Report any slip the week it happens.
