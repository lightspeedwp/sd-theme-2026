# Font licences — `assets/fonts/`

Provenance and rights for every face registered in `theme.json`
(`settings.typography.fontFamilies[].fontFace`).

Licence documents are held client-side, not in this repository:
`docs/SD Fonts & Licenses/` and `docs/myfonts_order_7491875209386/` in the workspace
checkout.

**Licence owner for every commercial face: Southern Destinations.** LightSpeed acts as its
agent. No commercial licence here is transferable, so no commercial face may be
redistributed by this repo — see *Delivery* below.

---

## Cleared for production

| Face | Licence | Evidence |
|---|---|---|
| `open-sans-*` (7 faces) | Apache 2.0 | in-font `license` / `licenseURL` |
| `belleza-400-normal` | SIL OFL 1.1 | in-font `license` / `licenseURL` |
| `la-belle-aurore-400-normal` | SIL OFL 1.1 | in-font `license` / `licenseURL` |
| `joe-hand-400-normal` | **JOEBOB graphics Webfont EULA 1.0** (29 Jun 2015) | `CF-13-joeHand-2_WEB/JBgfx Webfont EULA.pdf` |

### Joe Hand — cleared; the pageview cap is accepted, 2026-09-09

The file installed here is JOEBOB's **official webfont build**, `joehand_2_15-webfont.woff2`
(internal name `JoeHand 2_15 Regular Webfont`, 532 glyphs) — not the 226-glyph copy
previously converted from the live site. Web embedding is expressly granted by §1.1.

**§1.3 — 10,000 pageviews per copy, per month. One copy is licensed.** Southern
Destinations reports current traffic of roughly **5,000 pageviews a month** (client
statement, 2026-09-09), so one copy covers it with headroom. **SD has accepted the cap**
and will license additional copies or an extended licence when traffic approaches it.
LS-2642 stays open as a **monitor, not a blocker** — the face ships.

Two obligations remain live and are *not* affected by traffic:

- **§1.4 — one domain**, plus a maximum of 5 subdomains. `www.southerndestinations.com`
  is the licensed primary. `southerndestinations.lightspeedwp.dev` is a *different*
  domain and is **not** covered.
- **§1.5 — no hotlinking, no direct download.** The font must be reachable only as part of
  styling text on the licensed domain.

§1.2 excludes desktop installation, and §1.6 forbids transfer or sublicensing.

---

## 🔴 Not cleared for the web — Optima

Southern Destinations holds **two Optima licences, both desktop**. Neither permits the
theme to serve the face.

| Order | Date | Covers | Delivered |
|---|---|---|---|
| **#9528082** | 24 Jul 2018, USD 55 | *Optima Bold* — the "2 font styles" are the Pro and Std cuts of the **same** Bold weight | desktop OTF/TTF, `usWeightClass 700`, `fsType 4` |
| **#7491875209386** | 7 Sep 2026 | *Optima LT Pro* — **12 styles**, 400 → 950 with italics | desktop OTF, `fsType 4` |

### Why the 2026 order does not unblock the theme

Its bundled EULA is id **`2275` — Monotype "Font Software For Desktop" End User License
Agreement (v250903)**, in
`docs/myfonts_order_7491875209386/Licenses/2275/`. No webfont kit, no webfont EULA, no
invoice was included in the download; the archive is 12 OTFs and that one agreement.

It closes the web door on three independent counts:

1. **§2 License Grants** covers installing on a Licensed Desktop User's Workstation and
   using it to "create, edit, view, print and distribute materials, **provided that
   (a) the materials do not contain the Font Software embedded**". A page that serves a
   WOFF2 is precisely material containing the font software embedded.
2. **§4 Restrictions on Use** forbids "Modify the Font Software in any way, including to
   create, directly or indirectly, **Derivative Works**" — and §9 defines a Derivative
   Work to include "binary data in any format into which Font Software **may be
   converted**". Converting OTF → WOFF2 is a Derivative Work by the agreement's own
   definition.
3. **§4** also forbids, flatly: "**Install the Font Software on any server** or in any
   digital asset management system."

`fsType 4` on all 12 faces (preview & print embedding only) is consistent with that.

### What the 2026 order *does* unblock — this part is real value

Desktop rights across the full family: **Figma and design work, mock-ups, print, and
correct local rendering on any machine that installs it.** It retires the old *"Bold
only"* gap — SD now legitimately owns 400 / 500 / 600 / 700 / 750 / 950 plus italics
**for desktop**, so the previous note that "Optima 400 and 500 are not licensed at all"
no longer holds for design use. It just does not extend to the web server.

### What is still needed

A **Webfont** licence for Optima. At MyFonts that is a separate licence type at checkout,
not a re-download of a desktop order, and it delivers the **self-hosting kit** — WOFF2
files plus the **Tracking Code**, which the webfont EULA makes mandatory on all
non-development Websites and forbids removing. At ~5,000 pageviews a month the smallest
tier applies. → [LS-2641](https://linear.app/lightspeedwp/issue/LS-2641)

The drop-in is prepared and is a copy-plus-one-patch job the moment the kit arrives:
`.github/tasks/optima-webfont-kit-dropin-2026-09-09.md`.

### Family naming is fragmented — read this before hand-writing any Optima CSS

Linotype splits the LT Pro family across four CSS families, so `font-family: "Optima LT
Pro"` exposes **only 400 and 700**:

| OTF | CSS family (`name` ID 1) | Subfamily | `usWeightClass` |
|---|---|---|---|
| `OptimaLTPro-Roman` / `-Italic` | `Optima LT Pro` | Regular / Italic | 400 |
| `OptimaLTPro-Bold` / `-BoldItalic` | `Optima LT Pro` | Bold / Bold Italic | 700 |
| `OptimaLTPro-Medium` / `-MediumItalic` | `Optima LT Pro Medium` | Regular / Italic | 500 |
| `OptimaLTPro-Black` / `-BlackItalic` | `Optima LT Pro Medium` | **Bold** / Bold Italic | **750** |
| `OptimaLTPro-DemiBold` / `-DemiBoldItalic` | `Optima LT Pro DemiBold` | Regular / Italic | 600 |
| `OptimaLTPro-ExtraBlack` / `-ExtraBlackIta` | `Optima LT Pro XBlack` | Regular / Italic | 950 |

Adding the sub-families to a CSS stack does **not** recover 500 or 600: CSS resolves to the
first family in the list that has *any* matching face, then picks the nearest weight within
it. Only real `@font-face` rules — i.e. the webfont kit — give the theme true 500 and 600.

### What the theme does instead

`theme.json` registers **no Optima face**. The `heading` stack *names* it —
`"Optima LT Pro", Optima, Belleza, sans-serif` — so a machine with the licensed family
installed renders the licensed cut, a machine with only Apple's system Optima renders
that, and everyone else resolves to **Belleza**, which is registered as a real face for
exactly this reason. The design degrades rather than breaks.

`"Optima LT Pro"` leads the stack as of 2026-09-09: now that SD owns the family for
desktop, their own staff and the design machines render the cut they paid for rather than
Apple's. Rendering is unchanged for every visitor who does not have it installed, and for
macOS visitors the two cuts are the same design. Weight behaviour is unchanged too — both
families carry Regular and Bold only, so `h3` (600) and `h4`/`h5` (500) resolve exactly as
they did before.

Substituting a permanent heading face is a **Change-Control Register** item, per the
decision recorded on 2026-08-12.

### Removed 2026-08-18 — three unlicensed Optima faces

Converted from the live site during LS-2012 and covered by no licence at all:

| File | Real identity | Embedding bits |
|---|---|---|
| `optima-400-normal.woff2` | `Optima` — **©1991 AG Baltia**, a 1993 clone, not Monotype's | `fsType 1` — **embedding forbidden outright** |
| `optima-500-normal.woff2` | `Optima Medium` — Adobe Systems 1995 | `fsType 260` — preview/print, **subsetting forbidden** |
| `optima-700-normal.woff2` | `Optima Demi Bold` — Adobe Systems 1995 | `fsType 4` — preview/print only |

None is the face the client licensed, in either order. `fsType 1` on the 400 is the
sharpest point: that font's own metadata refuses embedding. The live site serves an
equivalent conversion (`optima-demibold_1-webfont.woff2`) today, so this exposure predates
the rebuild and carries over to it. → LS-2641

`Optima_Italic.ttf` was never ported: the live source is malformed (`glyf` overlaps `cmap`)
and `woff2_compress` rejects it. It never loaded on live either.

---

## Delivery

`joe-hand-400-normal.woff2` is **`.gitignore`d and untracked**, and reaches the server
through the build/deploy pipeline. That is about redistribution, not doubt, and the
accepted pageview cap does not change it: §1.5 forbids direct download and §1.6 forbids
transfer, and a repository is a distribution channel.

A fresh clone therefore carries 9 of 10 registered faces; `joe-hand-400-normal.woff2`
arrives from the pipeline. Until it does, the `accent` family resolves to La Belle Aurore
(bundled) and then `cursive`. Degraded, not broken.

The `assets/fonts/optima-*.woff2` ignore pattern stays in place. It is now guarding against
a *plausible* mistake rather than a theoretical one: 12 desktop OTFs sit in the workspace
and `woff2_compress` is installed, so an accidental conversion is one command away — and
that command is the §4 breach described above.

> ⚠️ **History caveat.** Four faces were committed before being untracked, and GitHub
> still serves the purged blobs at the pre-rewrite SHAs. See the Security section of
> `CHANGELOG.md`. That remains true and needs a GitHub Support `gc` request or a repo
> recreate before this repository could ever be public.
