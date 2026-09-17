# Contracts: Specials Landing Page

The page is not a service and exposes no API. Its contracts are the **seams between this
feature and the three things it touches but does not own** — the `sd-enhancements` plugin,
the enquiry surface funded on lines 15/16, and the rest of the site that links into this
page. Each is written so both halves can be built and tested independently.

| Contract | Between | File |
|---|---|---|
| Query shaping | theme template ↔ `sd-enhancements` | [query-contract.md](./query-contract.md) |
| The enquiry trigger | this page ↔ the enquiry modal (line 16) | [enquiry-trigger-contract.md](./enquiry-trigger-contract.md) |
| Band anchors | this page ↔ every other template that links to an offer | [anchor-contract.md](./anchor-contract.md) |
| Individual offer URLs | `sd-enhancements` ↔ old links, and the sitemap owner | [redirect-contract.md](./redirect-contract.md) |
| Retiring an expired offer | `sd-enhancements` ↔ the editors maintaining offers | [retirement-contract.md](./retirement-contract.md) |

Nothing else crosses a boundary. The band, the meta row, the page furniture and the CTA are
entirely inside this feature.

The redirect and retirement contracts exist because the spec assigned both behaviours to the
platform and measurement showed the platform provides neither — see [research.md](../research.md)
R-06 and R-07. They record what is built instead.

Retirement is the only part of this feature that **writes to published content**. Its contract
is written around keeping it strictly independent of the read-time filter, so the page stays
correct even if retirement is disabled or wrong.
