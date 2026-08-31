# The pattern library

A guide to the 43 block patterns in **Southern Destinations 2026**, written for the
people who build pages in the editor rather than for developers. It says what each
pattern is, where it belongs, and which ones fill themselves in from the site's content.

Developers: the reasoning behind each pattern is in the comment block at the top of its
own file in [`patterns/`](patterns/). This document does not repeat it.

> **Task 4.10 of [LS-2014](https://linear.app/lightspeedwp/issue/LS-2014/4-theme-structure).**
> Written 2026-08-26 against the patterns then in the theme. When you add a pattern, add
> a row here in the same pass — a list that is only mostly true is worse than no list.

---

## How to insert one

In the editor, open the inserter (**+** top left) and choose the **Patterns** tab. The
patterns are filed under nine categories, listed below. You can also type `/` in an
empty paragraph and start typing the pattern's name.

**Patterns are copies, not links.** Once you insert one, it becomes ordinary blocks on
that page and stops tracking the original. Editing the pattern in the theme afterwards
will not change pages you already built from it. (Template patterns — the ones marked
*Not in the inserter* below — behave differently: those are referenced by the template
and do update everywhere.)

---

## The categories

| Category | What is filed there |
|---|---|
| **Hero** | Page and homepage banners |
| **Cards** | The repeating tiles used inside listings |
| **Call To Action** | Enquiry prompts and contact panels |
| **Features** | Homepage and landing-page content bands |
| **Menu** | Header and navigation compositions |
| **Pages** | Whole-section building blocks for page bodies |
| **Posts** | Blog listings, post cards, single-post layouts |
| **Testimonials** | Trustpilot and review displays |
| **Tour Operator** | Anything tied to tours, accommodation, destinations or brands |

Core's own patterns are switched off, so everything you see in the inserter is ours.

---

## Three things worth knowing before you start

**1. Some patterns fill themselves in.** Anything marked **Live data** below reads from
the site — posts, team members, destinations, brands, the Trustpilot score. You place
it once and it stays current. Don't retype its contents; if it shows the wrong thing,
the fix is in the content or the settings, not in the page.

**2. Some patterns are placed by the template, not by you.** Anything marked *Not in
the inserter* is part of a page template and appears automatically. You will not find
it in the Patterns tab, and you should not need to.

**3. Two bands are only partly wired up.** **Why Choose Southern Destinations** and the
**CTA** band appear beneath the archives on the current site, where the old theme
printed them automatically. The **tour** template now places both, because live places
both there and in that order. Everywhere else they are defined but not yet attached —
that decision is still open on
[LS-2033](https://linear.app/lightspeedwp/issue/LS-2033). Please don't paste them into
individual page bodies in the meantime: when they are attached, every page you pasted
them into will show them twice.

---

## Homepage

The homepage is built entirely from these, in this order. It is assembled by the
template — you don't need to place them by hand.

| Pattern | What it is | Live data |
|---|---|---|
| **Homepage — Hero** | Full-bleed photograph with the headline and a guest quote. The image is drawn at random from an eleven-image pool on each page load. | Banner pool |
| **Homepage — How To Plan Your Dream Trip** | The monogram, the script heading, the italic standfirst and the "Start here" cue. | — |
| **Homepage — Main Content** | The four stacked photograph panels — Destinations, Accommodation, Tours, and the enquiry invitation — with the translucent panel alternating side to side. | — |
| **Homepage — Let's Make It Happen** | The three-step invitation, the two office numbers, and the email action. | — |
| **Why Choose Southern Destinations** | The three value columns on a darkened photograph, with the Trustpilot score and the We Are Africa badge beneath. Also used beneath inner pages. | Trustpilot score |
| **Homepage — Meet Our Safari Gurus** | The consultant row. Four team members, each a portrait whose bio links appear on hover. On phones it becomes a single button through to the team page. | Team |
| **Homepage — Africa's Finest Brands** | The accommodation-brand logo carousel, five at a time. | Brands |
| **Homepage — The Southern Destinations Difference** | Trustpilot's own review carousel. | Trustpilot |
| **Homepage — Tales From Our Trails** | The news carousel — three post tiles at a time. | Posts |

### Which four safari gurus?

The row shows team members tagged with the **Safari Guru** role. To change who appears,
edit the team member and set that role — don't edit the homepage.

> ⚠️ **Nobody is tagged yet.** Until somebody is, the row falls back to showing the four
> most recently added team members, which is almost certainly not who you want. Tag the
> four you want and they will appear in the order they were added.

Each card also uses the team member's **featured image** and **contact email**. A member
with no contact email will show a "Get in Touch" link that goes nowhere, so fill it in.

---

## Page sections

Drop these into any page body.

| Pattern | What it is | Live data |
|---|---|---|
| **Page Hero Banner** | The standard page banner — a full-width photograph carrying the page title. | — |
| **Safari Expert Panel** | "Chat to your Safari Expert" — a brand-coloured card holding the consultant's portrait and name, a Call Us dropdown carrying the four office numbers, and an email action, with the Trustpilot badge beneath the card. Picks the right consultant for the page it is on. | Team · Trustpilot |
| **CTA — Not Sure Where To Go** | The enquiry band: a script heading over the two office numbers, with "Send us an Email" beneath. | — |
| **CTA — Tell Us Your Trip Ideas** | The same enquiry band with the heading every tour, lodge and destination page uses. Placed by the tour template; place it by hand only on a page that needs it. | — |
| **CTA — Inspired By This Property** | The same enquiry band with the heading the accommodation page uses. Placed by the accommodation template; place it by hand only on a page that needs it. | — |
| **Why Choose Southern Destinations** | See above. | Trustpilot score |
| **Trustpilot Score** | The rating badge on its own — the word, the mark, the stars and the TrustScore line. Already included inside the two patterns above; place it separately only where you want the badge alone. | Trustpilot |

**Safari Expert Panel** works out who to show in this order: the consultant assigned to
the destination or tour you are on, then the consultant connected to the post, then
somebody from the expert pool in the Tour Operator settings. If none of those resolve,
the panel renders nothing rather than showing the wrong person.

**Its Call Us dropdown is the same one as the header's** — hover or click the box and the
four office numbers drop out of it. There is one place those numbers live, the
`dropdown-call-us` template part, so editing them there changes the header, the footer,
the mobile menu and this panel together.

**Three headings exist for the CTA band on the current site** — "Not sure where to go?"
on the destination and brand archives, "Like what you see? Let's start planning!" on
specials, and "Tell us your trip ideas and we'll send you ours!" everywhere else. The
first two of those three are built; the specials one is one line of copy apart and will
be added with the template that needs it. Pick the variant that matches the page — they
are otherwise identical, and a change to the numbers or the action belongs in both.

**"Send us an Email" is a link to `/contact/` for now.** The pop-up enquiry form is
being built separately ([LS-2530](https://linear.app/lightspeedwp/issue/LS-2530)) and
will replace the link when it lands. This applies to the Safari Expert Panel, the CTA
band, and Let's Make It Happen.

---

## Cards

Cards are the repeating tiles inside a listing. **You rarely place these directly** —
they are what the listings and archive templates are built out of. Insert one on its own
only when you want a single tile as a feature.

| Pattern | Used for |
|---|---|
| **Card — Media Overlay** | The shared archive tile: photograph with the title over it. Used by every Tour Operator archive, unchanged, so all the archives match. |
| **Card — Tour (Compact)** · **Card — Tour (List)** | Tour tiles, in a grid and in a row |
| **Card — Accommodation (Compact)** · **Card — Accommodation (List)** | Accommodation tiles |
| **Card — Destination (Compact)** | Destination tile |
| **Card — Post (Grid)** · **Card — Post (List)** | Blog post tiles |
| **Card — Category** | Category tile |
| **Blog Card** · **Blog Card Large** | Post tiles for the news landing page |
| **Card — Review (Quote)** | The review slide the carousels on Tour Operator singles carry — a gold quote mark over the reviewer's photograph |

All of the Tour Operator cards read their content from the post they are showing, so
they always match the tour, lodge or destination they sit on.

---

## Listings

| Pattern | What it is | In the inserter |
|---|---|---|
| **Post Loop List** | Posts in a vertical list | Yes |
| **Post Loop Grid** | Posts in a grid, with the theme's own settings | No |
| **Post Loop Grid Default** | Posts in a grid, inheriting the page's query | No |
| **Single Post** | The single blog post layout | Yes |

---

## Header and footer

| Pattern | Notes |
|---|---|
| **Header** | The site header — logo, navigation, mega menus, the Trustpilot badge and the Call Us dropdown. Part of the header template part; you should not need to place it. |
| **Footer** | The four-column footer. Same — it belongs to the footer template part. |

The header shows a different arrangement on phones and on desktop. That is handled per
block with the **Block Visibility** controls in the block sidebar, not with hidden CSS,
so if something is missing at one screen size, check that panel first.

---

## Template patterns — *not in the inserter*

These are whole page layouts. Each one is attached to a template, and editing the
template is how you change it. They are listed here so you know they exist and can
recognise the name if you meet it in the Site Editor.

| Pattern | Template |
|---|---|
| **Template: Destinations Archive** | The destinations landing page |
| **Template: Tours Archive** | The tours landing page |
| **Template: Accommodation Archive** | The accommodation landing page |
| **Template: Blog Landing (News)** | The news index |
| **Template: Category** | Category archives |
| **Template: Archive** | Generic archives |
| **Template: Search Results** | Search |
| **Template: 404 Not Found** | 404 |
| **Template: Page** | The standard page |
| **Template: Page (Full Width, No Title)** | Full-bleed pages |
| **Template: Page (With Sidebar)** | Pages with the sidebar |
| **Template: Single Tour** | The tour page |
| **Itinerary Stay** | One row of the tour page's itinerary list. Repeated once per stay by the Tour Operator plugin — you never place it. |
| **Template: Single Accommodation** | The accommodation page |
| **Accommodation Unit** | One card in the accommodation page's Rooms band. Repeated once per unit by the Tour Operator plugin — you never place it. |

---

## When something looks wrong

| What you see | Usually means |
|---|---|
| A section is empty | Its query found nothing — check the post type has published content, and check any role or category filter |
| The safari gurus row shows the wrong people | Nobody is tagged **Safari Guru**; it is falling back to the four most recent team members |
| A "Get in Touch" link goes nowhere | That team member has no contact email |
| The Trustpilot badge shows placeholder text | The score cache has not refreshed; it updates twice a day |
| A pattern is missing from the inserter | It is a template pattern — see the table above |
| A block is missing at one screen size only | Check the **Visibility** panel in the block sidebar |
| An edit to a template had no effect on the site | The page is rendering a version saved in the database from a previous Site Editor session, which wins over the theme file. Ask a developer to reconcile it. |

---

## For developers

- Pattern files live in [`patterns/`](patterns/), one per file, registered automatically
  from their header comment. Slugs are namespaced `sd-theme-2026/…`.
- Categories are registered in [`functions.php`](functions.php). Add a category only when
  approved scope puts patterns in it.
- New pattern files do not appear until the pattern cache is cleared:
  `wp transient delete --all --network`.
- A `<!-- wp:pattern /-->` reference nested inside another **pattern** is dropped on
  front-end render while still resolving under a WP-CLI `do_blocks()` test. Use
  `require __DIR__ . '/other-pattern.php';` instead. References inside a *template* are
  fine.
- **A pattern is block markup, not a PHP template.** Copy strings are wrapped inline with
  `esc_html_e()` / `esc_html_x()` exactly as core's patterns do; there are no variables
  holding literals, no loops, no computed markup and no `phpcs:ignore`. Repetitions are
  written out. The measured rule is in [AGENTS.md](AGENTS.md) under "PHP".
- Conventions, token rules and the quality bar: [AGENTS.md](AGENTS.md) ·
  [CONTRIBUTING.md](CONTRIBUTING.md) · [DESIGN.md](DESIGN.md).
