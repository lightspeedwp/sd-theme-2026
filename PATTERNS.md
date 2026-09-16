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
| **Blog — Browse By Category** | The category shelf on the blog landing — five tiles at a time, following the categories that have posts. Add a category and it appears; you never edit the shelf. | Posts |

### Which four safari gurus?

The row shows team members tagged with the **Safari Guru** role. To change who appears,
edit the team member and set that role — don't edit the homepage.

> ⚠️ **Nobody is tagged yet.** Until somebody is, the row falls back to showing the four
> most recently added team members, which is almost certainly not who you want. Tag the
> four you want and they will appear in the order they were added.

Each card also uses the team member's **featured image** and **contact email**. A member
with no contact email will show a "Get in Touch" link that goes nowhere, so fill it in.

---

## The team page

The *Meet the Team* page is three sections — **Management Team**, **Consultants** and
**Support Team** — and each one is a separate listing that shows the team members tagged
with that **Role**. Nothing on the page is a hand-written list of people.

So, to change the page:

| To do this | Edit this |
|---|---|
| Move somebody between sections, or add a new joiner | The team member's **Role** |
| Change the order within a section | The team member's **Order** field — the sections read it low to high |
| Change the name or the job title on a card | The team member's title and its **Role** text field |
| Change the standfirst under the banner | The template, for now — it is not a setting yet |

> Two different fields are both called "role", which is confusing and worth knowing about.
> The **Role** *taxonomy* decides which section somebody lands in. The **Role** *text
> field* is the job title printed on the card ("Queen Bee", "Support Travel Guru"). A
> member can be in the Management Team section and have "CEO" printed on their card.

A member tagged into more than one role appears in each of those sections. Vanessa is
tagged both **Founder** and **Management Team** and shows once, in Management Team,
because there is no Founder section on the page — add the role to somebody and they will
not appear anywhere new until a section for it is added to the template.

**A section with an empty heading and nothing under it** means nobody carries that role.
Tag somebody, or ask a developer to take the section out.

### A team member's own page

Clicking a card opens that member's page, which is built entirely from fields on their
post. Every section disappears on its own when the field behind it is empty, so a member
who has only a bio gets a page with only a bio — there is nothing to switch off.

| Section | Comes from |
|---|---|
| The banner photograph | **Banner Image** (falls back to the featured image) |
| The job title under the name | The **Role** text field |
| **Get in touch** | **Email** (falls back to the contact page) |
| *{Name}’s client feedback* | **Trustpilot ID** — the tag their reviews are filed under |
| *{Name}’s Wild Adventures* | **Gallery** |
| *{Name}’s Favourite Tours* | **Related Tours** |
| *{Name}’s Favourite Destinations* | **Related Destinations** |
| *Read {Name}’s Blog* | **Related Posts** |

Every heading uses the member's **first name**, taken from the post title. So a post
titled “Camille Rowe” gives “Meet Camille” and “Camille’s Favourite Tours” — rename the
post and all six headings follow.

> **The map is not on the page yet.** Live shows *“Places {name} has visited”* — a map
> pinned with the lodges they have been to. It needs a block that does not exist yet and
> is being built separately; when it lands it goes between the gallery and the tours.

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
| **Trustpilot Score** | The rating badge on its own, in a row — the word, the mark, the stars and the TrustScore line. Already included inside the two patterns above; place it separately only where you want the badge alone. | Trustpilot |
| **Trustpilot Score — Stacked** | The same badge in a column — the word, the stars, "Based on {n} reviews" and the mark. The arrangement that sits beside a review row; used by the consultant pages' client-feedback band. | Trustpilot |

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
| **Card — Team Member** | The consultant tile on the team page — a portrait with a dark band along its foot carrying the name and the role |
| **Card — Trustpilot Review** | One Trustpilot review on a consultant's own page. Repeated three times by the reviews block; you never place it. |

All of the Tour Operator cards read their content from the post they are showing, so
they always match the tour, lodge or destination they sit on.

---

## Listings

| Pattern | What it is | In the inserter |
|---|---|---|
| **Post Loop List** | Posts in a vertical list | Yes |
| **Post Loop Grid** | Posts in a grid, with the theme's own settings | No |
| **Post Loop Grid Default** | Posts in a grid, inheriting the page's query | No |

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
| **Template: Team Archive** | The team landing page — *Meet the Team* |
| **Template: Brands Landing** | The *Brands* page — the grid of lodge-operator logos. Attached by the page's slug, so renaming the page to anything other than `brands` detaches it. |
| **Template: Accommodation Brand Taxonomy** | A single brand's page — its story, its logo, and its accommodation. One template for all twenty-one brands. |
| **Template: Single Team Member** | A consultant's own page |
| **Template: Blog Landing** | The blog landing page — *Tales from our trails*, the category shelf and the list of posts. Used by both the posts index and the generic fallback template. |
| **Template: Single Post** | A blog post's own page — the byline, the title, the post, then the tinted band carrying three **Related Posts** and the previous/next pager. |
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
| **Template: Single Destination** | Any destination page — country or region. What a destination uses unless you pick otherwise. |
| **Template: Single Country** | A destination page with the **regions** shelf and no accommodation shelf |
| **Template: Single Region** | A destination page with the **accommodation** shelf and no regions shelf |
| **Destination — Banner / Summary Band / Gallery / Regions Shelf / Accommodation Shelf / Tours Shelf / Reviews Shelf** | The seven sections the three destination templates are built from. Each appears in all the templates that use it, so a change to one section changes every destination page that shows it — which is the point. You never place them. |
| **Breadcrumbs** | The Yoast trail band beneath the banner. Shared by all three destination templates and the tour template — a change here changes every page that shows it. You never place it. |

### Choosing Single Country or Single Region

Every destination uses **Template: Single Destination** by default, and it is correct for a
country and for a region alike: each shelf removes itself when it has nothing to show, so a
country lands on its regions and a region lands on its accommodation without anyone
choosing anything.

Pick one of the other two — in the **Template** panel in the destination's sidebar — only
when you want to *force* the choice:

| Pick | To get |
|---|---|
| **Single Country** | The regions shelf, and **no** accommodation shelf even where lodges are connected to the country |
| **Single Region** | The accommodation shelf, and no regions shelf |

A country that has no child regions published yet will still show no regions shelf on
Single Country — the shelf is its regions, and there are none to list.

---

## When something looks wrong

| What you see | Usually means |
|---|---|
| A section is empty | Its query found nothing — check the post type has published content, and check any role or category filter |
| A destination page has no map at all | That destination has no **location** set. The whole map band removes itself rather than showing an empty box |
| The destination map shows "Click here to display the map" and clicking does nothing | A known Tour Operator 2.2 fault, not a theme one — the plugin builds the map and then discards it, so the script that handles the click never starts. On the change register; it needs a plugin fix |
| A destination's breadcrumb trail is missing a level | Yoast builds the trail from the destination's **parent**. A region with no parent country set reads as a top-level page |
| A destination page's description is cut off with a "Read more..." link | Expected — the copy collapses to its first paragraph until a reader clicks through |
| The safari gurus row shows the wrong people | Nobody is tagged **Safari Guru**; it is falling back to the four most recent team members |
| A "Get in Touch" link goes nowhere | That team member has no contact email |
| A consultant's page has no client-feedback section | They have no **Trustpilot ID**, or the review cache has not refreshed |
| A consultant's page has no map | The map is a separate build and is not on the page yet |
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
