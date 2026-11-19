# Contract: Band anchors

**Between**: this page (provides) and every other template, menu and piece of migrated
content that links to an offer (consumes).

**Why a contract**: the site links to offers as `/specials/#special-{slug}` rather than to a
page of their own — that is the whole consequence of Q1. If the rebuilt band derives its
anchor differently, every one of those links lands at the top of the page and nothing errors.
Risk R-2; SC-008 exists to catch it.

---

## The guarantee

Each offer band carries `id="special-{slug}"`, where `{slug}` is the offer's `post_name`
exactly as the live site derives it — unmodified, not re-slugged, not prefixed twice.

`/specials/#special-{slug}` therefore lands on that offer's band (FR-011).

## Consequences for the implementation

- The anchor is **per rendered post**, so it cannot be an authored `anchor` attribute in a
  pattern that renders once per offer inside a `post-template`. It is derived at render time
  from the queried post, which makes it behaviour: `sd-enhancements`, not the theme.
- The band must remain a real element with that `id` at every viewport width — it cannot be
  a wrapper that the responsive treatment replaces or unwraps.
- Pagination interacts with this: with four offers per page, an anchor for an offer on page 2
  does not resolve from page 1. This matches live, which has the same constraint, and is not
  changed here — but it means SC-008's verification must load the page each anchor is
  actually on, not just `/specials/`.

## Required before the band is authored

Enumerate the anchors in use — `grep -rn '#special-' patterns/ parts/ templates/` plus the
migrated navigation on the development site. That list is the SC-008 test set. Without it,
SC-008 is an assertion nobody can run.
