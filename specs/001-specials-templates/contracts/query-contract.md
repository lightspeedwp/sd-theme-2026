# Contract: Specials archive query shaping

**Between**: `sd-theme-2026` (`patterns/template-archive-special.php`) and
`sd-enhancements-2026` (`modules/queries.php`).

**Why a contract**: the theme may not shape queries. Under the deactivation test
(AGENTS.md) per-page count, ordering and validity filtering are behaviour, and a page that
renders correctly only because a theme file filtered the query is exactly the coupling this
rebuild exists to remove.

---

## The theme's half

`templates/archive-special.html` delegates to `patterns/template-archive-special.php`, whose
`core/query` block sets:

```
"query": { "inherit": true, "postType": "special" }
```

`inherit: true` is the whole contract from this side. The template asserts no `perPage`, no
`order`, no `orderBy` and no meta query. A `perPage` left in the block is silently ignored
when inheriting and is removed rather than left to mislead a reader.

## The plugin's half

`SD\Enhancements\Queries::limit_specials_archive()` — the existing filter on `pre_get_posts`
at priority 200 (`modules/queries.php:177-187`), already guarded to the specials post-type
archive main query.

| Guarantee | Requirement | Status |
|---|---|---|
| `posts_per_page` = 4 | FR-005 | **Exists** (`SPECIALS_PER_PAGE`, `queries.php:32`) |
| Ordered by `booking_validity_start`, ascending | FR-007 | **To add** |
| Offers whose `booking_validity_end` has passed are excluded | FR-008, SC-001 | **To add** |
| Offers whose `booking_validity_start` is in the future are excluded | Spec edge case | **To add** |
| An absent bound means open — never a reason to exclude | Spec edge case | **To add** |

### Constraints on the implementation

- Both dates are CMB2 `text_date_timestamp` post meta, so comparisons are numeric, not
  `DATE`. An offer with an empty or missing value has **no meta row at all**, so any meta
  query must pair its comparison with a `NOT EXISTS` branch or open-ended offers vanish —
  which would fail SC-001 silently and in the direction nobody checks.
- Sorting by a meta value while including rows that do not have that meta requires care for
  the same reason. Offers with no start date need a defined position, not an accidental one.
- The filter runs on the **main query** only. It must not reach the specials carousels
  embedded on destination, accommodation and tour pages — those are separate `core/query`
  blocks owned by other issues, and `is_main_query()` already guards it.
- FacetWP runs at priority 999. This filter stays at 200, below it, consistent with the
  reasoning already recorded in `queries.php` for the brand archive.

### Explicitly not in this contract

No post status is changed and nothing is written. Tour Operator's `Post_Expiration` class —
which registers **no hooks at all**, has no scheduler behind it, and was only ever wired for
`tour` (research R-06, verified by execution) — is neither revived nor replaced here.
`tour-operator` is vendor code.

That makes this filter the **only** thing keeping expired offers off the page, so it carries
SC-001 by itself and is tested by itself. If retirement-to-draft is later added — a reasonable
thing to want, since it cleans the editor's list too — it belongs in `sd-enhancements` as a
scheduled task covering `special`, and expired offers would then fall out of this query a
second way with no conflict. It is not needed for any requirement in this spec.
