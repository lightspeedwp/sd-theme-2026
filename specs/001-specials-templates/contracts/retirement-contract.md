# Contract: Retiring an expired offer

**Between**: `sd-enhancements-2026` (implements) and the editors who maintain offers.

**Why a contract**: this is the one piece of the feature that **writes to published content**,
on a schedule, with nobody watching. Everything else here renders or filters. That difference
is the whole reason it gets its own contract and its own risk (spec R-4).

Requirements: **FR-026a** (retirement), **FR-026b** (do not revive the platform's), **SC-006a**.
Measurement behind it: [research.md](../research.md) R-06.

---

## The relationship to the read-time filter

These are **two independent mechanisms** and must stay that way.

| | [Read-time exclusion](./query-contract.md) — FR-026 | Retirement — FR-026a |
|---|---|---|
| What it does | Filters the archive query | Sets `post_status` to `draft` |
| Writes | Nothing | Content |
| Carries | FR-008, SC-001 — the visitor-facing guarantee | SC-006a — the editorial one |
| If it breaks | The page shows wrong offers | The editor's list drifts; **the page stays correct** |

The page must be correct from the filter **alone**. Retirement is never load-bearing for what
a visitor sees. That is what makes it safe to build, safe to disable, and safe to fix later —
and it is the mitigation for R-4. Do not "optimise" by dropping the filter once retirement
works: a retired offer is a draft, and the filter is what keeps a *published* offer whose
window has closed off the page in the window before the scheduler next runs.

## What the platform provides

Nothing. Verified by execution on 2026-09-17:

```
save_post_tour         has_action=false
save_post_special      has_action=false
lsx_to_expire_tour     has_action=false
Post_Expiration class exists: true
instantiated in classes[]: post_expiration
Action Scheduler available: false
```

`lsx\admin\Post_Expiration` is instantiated but registers no hooks — both `add_action()` calls
are commented out (`class-post-expiration.php:26-27`). Action Scheduler is not available. And
its hooks are `save_post_tour` / `lsx_to_expire_tour`: **tours only**, with no offer
equivalent. `wp cron event list` shows no expiry event. `tour-operator` is vendor code and is
not patched (FR-026b).

## The guarantee

| | |
|---|---|
| Trigger | An offer's `booking_validity_end` has passed |
| Effect | `post_status` becomes `draft`. Nothing else changes |
| Preserved | Description, connections, taxonomies, validity dates, featured image, banner image — **all of it** |
| Reversible | Extending the end date and republishing restores the offer exactly, with its anchor and connections intact |
| No end date | **Never retired.** An absent bound means open-ended |
| Idempotent | Retiring an already-draft offer is a no-op, not an error |
| Rescheduling | Changing an offer's end date reschedules; a stale scheduled retirement must not fire against the new date |
| Backlog | If the scheduler has not run for days, **every** offer that expired in the gap retires on the next run — not just the most recent |
| Editor safety | Must not clobber an edit in progress, and must not resurrect an offer on save |

## Scheduling

Action Scheduler is **not available** on this install, so the implementation uses WP-Cron or
whatever `sd-enhancements` already relies on for scheduled work — decided at task time against
what the module actually has, not assumed here. Whichever it is:

- A single recurring sweep is preferable to one scheduled action per offer. It is simpler,
  has no per-post state to leak, survives a missed run by design, and handles the backlog case
  for free. Per-offer scheduling is what the dormant platform code attempted and it needs
  `to_expiration_id` bookkeeping that can desynchronise.
- The sweep must be cheap: it queries offers with a passed end date, and there are 42 of them.
- Timezone is the site's, and the comparison must match the one the read-time filter uses, or
  an offer can be filtered off the page on one boundary and retired on another.

## Verification

Tested by **closing a real validity window on a test offer** and observing the status change,
not by inspecting the schedule — a scheduled action that exists is not evidence that it fires
correctly. See [quickstart.md](../quickstart.md) check 8a. Republish that offer afterwards and
confirm it returns intact; that single round trip exercises most of the table above.
