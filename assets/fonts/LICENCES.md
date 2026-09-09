# Font licences — `assets/fonts/`

Provenance and rights for every face registered in `theme.json`
(`settings.typography.fontFamilies[].fontFace`).

Licence documents are held client-side, not in this repository:
`docs/SD Fonts & Licenses/` and `docs/myfonts_order_7491875209386/` in the workspace
checkout.

**Licence owner for every commercial face: Southern Destinations.** LightSpeed acts as its
agent.

---

## Cleared for production

| Face | Licence | Evidence |
|---|---|---|
| `open-sans-variable-normal` · `-italic` | SIL OFL 1.1 | in-font name ID 13/14 |
| `belleza-400-normal` | SIL OFL 1.1 | in-font `license` / `licenseURL` |
| `la-belle-aurore-400-normal` | SIL OFL 1.1 | in-font `license` / `licenseURL` |
| `joe-hand-400-normal` | **JOEBOB graphics Webfont EULA 1.0** (29 Jun 2015) | `CF-13-joeHand-2_WEB/JBgfx Webfont EULA.pdf` |

Five faces, **216 KB**, all committed.

### Open Sans is now the variable font — and it is OFL, not Apache

Replaced **2026-09-09**. The seven statics were the classic Ascender release, `Version 1.10`,
which **never shipped a Medium (500)** — so a `font-weight|medium` declaration on body text had
nothing to resolve to. The bundle is now two faces built from Google's current **`Version
3.003`** variable Open Sans (`google/fonts` → `ofl/opensans`), which carries a real `wght`
axis:

| | Before (7 statics, v1.10) | After (2 variable, v3.003) |
|---|---|---|
| Weights | 300, 400, 600 + italics; 700 normal | **any 300–700**, normal + italic |
| Typical page (400 + 600 normal) | 89 KB | **61 KB** |
| With italics (300/400/600 + 400i) | 145 KB | **125 KB** |
| Whole family on disk | 319 KB | **125 KB** |
| Licence | Apache 2.0 | **SIL OFL 1.1** |

⚠️ **The licence genuinely changed** — this is not a correction of an earlier error. Open Sans
was Apache 2.0 under Ascender; Google relicensed it to **SIL OFL 1.1** with the 2021 rebuild.
The in-font name ID 13 reads *"This Font Software is licensed under the SIL Open Font License,
Version 1.1."* Both remain permissive and bundleable; OFL adds the reserved-font-name rule, so
**do not rename these files' internal family name** if they are ever re-subset.

**Build recipe** (reproducible; `fonttools` in a throwaway venv, `woff2` via `pyftsubset`):

```bash
# source: https://cdn.jsdelivr.net/gh/google/fonts@main/ofl/opensans/OpenSans%5Bwdth,wght%5D.ttf
#         …/OpenSans-Italic%5Bwdth,wght%5D.ttf
fonttools varLib.instancer -o pinned.ttf OpenSans\[wdth,wght\].ttf wdth=100 wght=300:700
pyftsubset pinned.ttf --unicodes-file=cps.txt --layout-features='*' \
  --flavor=woff2 --drop-tables+=DSIG --output-file=open-sans-variable-normal.woff2
```

Two deliberate reductions, both needed to make the migration a *win* rather than a 3×
regression (the unmodified variable font is 141 KB normal / 154 KB italic):

1. **`wdth` axis pinned to 100.** The theme uses no width variation; the axis alone cost
   ~32 KB per file.
2. **`wght` clamped to 300–700, and Greek + Cyrillic dropped** (883 → 532 codepoints; latin,
   latin-ext and Vietnamese retained). 300–700 is exactly the range the body family is asked
   for — 200 and 900 references in this theme are on the `accent` and `heading` families, not
   this one.

   The script drop was checked against real content, not assumed: on dev, **0 of 1,431**
   content rows contain Cyrillic, and the only 3 Greek-range hits are a single mojibake `ϋ`
   (U+03CB) in three 2014 blog posts — an encoding artefact, not Greek text. Uncovered
   characters fall back to the system font per-glyph, so this degrades gracefully. To restore
   full parity, rebuild with `cps.txt` in place of `cps-latin.txt` — the cost is +47 KB per
   file.

### Joe Hand — cleared; the pageview cap is accepted, 2026-09-09

The file installed here is JOEBOB's **official webfont build**, `joehand_2_15-webfont.woff2`
(internal name `JoeHand 2_15 Regular Webfont`, 532 glyphs) — not the 226-glyph copy
previously converted from the live site. Web embedding is expressly granted by §1.1.

**§1.3 — 10,000 pageviews per copy, per month. One copy is licensed.** Southern
Destinations reports current traffic of roughly **5,000 pageviews a month** (client
statement, 2026-09-09), so one copy covers it with headroom. **SD has accepted the cap**
and will license additional copies or an extended licence when traffic approaches it.
LS-2642 stays open as a **monitor, not a blocker** — the face ships.

**§1.4 — one domain**, plus a maximum of 5 subdomains, primary
`www.southerndestinations.com`. `southerndestinations.lightspeedwp.dev` is a *different*
domain, so serving the face there is outside the strict reading. **Accepted 2026-09-09**:
dev is a private review host seen only by the agency and the client, the licence owner is
the client, and there is no public audience — the face is served there. Recorded rather than
resolved; if JOEBOB is ever asked, the clean answer is to have them add the host in writing.

**§1.5 — no hotlinking, no direct download**, and **§1.6 — no transfer or sublicensing.**
These are why the *file* handling matters rather than the markup; see *Delivery*. §1.2
excludes desktop installation.

---

## 🟡 Optima — the web licence is DemiBold only, and it is in progress

Confirmed by Vanessa Ratcliffe (SD) by email, **2026-09-07**:

> "We had only specified Optima DemiBold for the website — so we're purchasing that font
> weight for the web license which is annually renewable. Also purchasing the full Optima
> set for a Desktop license for one user. We have to buy this font as we use them on
> Canva etc."

So there are **two deliberate, separate purchases** — the desktop one is not a mistake and
should not be treated as one:

| Order | Date | Purpose | Licence | Delivered |
|---|---|---|---|---|
| **#9528082** | 24 Jul 2018 | superseded | Desktop | *Optima Bold* — Pro + Std cuts of one weight, OTF/TTF |
| **#7491875209386** | 7 Sep 2026 | **Canva and other client design work**, 1 user | **Desktop** (EULA `2275`, Monotype *Font Software For Desktop* v250903) | *Optima LT Pro*, 12 styles, 400→950 + italics, OTF, `fsType 4` |
| *pending* | in progress | **the website** | **Web, annually renewable** | *Optima DemiBold* — **one weight** |

### The desktop order is for Canva, not for the theme — and it cannot be repurposed

EULA `2275` is desktop-only and closes the web door on three independent counts:

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

`fsType 4` on all 12 faces (preview & print embedding only) is consistent with that. The
archive holds 12 OTFs and that one agreement — no webfont kit, no webfont EULA, no invoice.

So: the desktop family is the right tool for Canva and the wrong tool for the theme, and
the two must not be conflated. It does mean SD legitimately owns 400/500/600/700/750/950
plus italics **for desktop**, retiring the old *"Bold only"* gap for design work.

### What the pending web licence means for the theme — one weight, renewed annually

**Only DemiBold (600) will be licensed for the web.** Not 400, not 500, not 700. That
matches the live site, which serves `optimademi_bold` and nothing else, so it is a
faithful-to-live constraint rather than a compromise — but it has two consequences the
theme has to answer for:

- **Heading weights need a decision.** `theme.json` currently asks for 700 on `h1`/`h2`,
  600 on `h3`, and 500 on `h4`/`h5`. With a single 600 face those resolve to synthetic
  bold (700) or to the 600 face anyway (500). Options and the recommendation are in the
  drop-in doc.
- 🔴 **The licence is annually renewable.** That is a recurring obligation on SD, not a
  one-off, and **if it lapses the site silently falls back to Belleza**. It needs a diary
  entry on SD's side and a note in the handover pack. It is also the first recurring
  third-party cost in this build — worth recording on the Change-Control Register
  ([LS-2033](https://linear.app/lightspeedwp/issue/LS-2033)) as an operational item, not a
  scope change.

Awaiting the kit from SD as of 2026-09-09.
→ [LS-2641](https://linear.app/lightspeedwp/issue/LS-2641)

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

**All five faces are committed as of 2026-09-09, Joe Hand included.** Decision by Zared
Rogers, recorded here.

Joe Hand was previously `.gitignore`d on a redistribution reading of §1.5 (no direct
download) and §1.6 (no transfer), with delivery deferred to "the build/deploy pipeline". That
reading cost more than it protected:

- **There was no pipeline.** Measured 2026-09-09: the face returned **404 on dev** while all
  three committed faces returned 200. There is no `.github/workflows/` in this repository and
  no other written step that places an ignored file on a server. The `@font-face` rule was
  emitted correctly the whole time — only the file was absent. Production would have failed
  identically at launch on 2026-09-30.
- **The exposure it guarded against is not real here.** This repository is private, access is
  agency plus client, and Southern Destinations *is* the licence owner with LightSpeed acting
  as its agent. §1.5 is about not offering the font as a download to the public; a private
  repo is not that.

⚠️ **This decision depends on the repo staying private.** If it is ever made public, the face
must be removed and history rewritten first — and history is *already* not clean (see below).

**Optima stays ignored**, for a different reason: there is no web-licensed file to commit yet.
The pending licence covers *Optima DemiBold* alone and is still being purchased. Twelve
**desktop** OTFs sit in the workspace for the client's Canva use, and converting them is the
§4 breach described above — so `assets/fonts/optima-*.woff2` guards a plausible accident. When
the kit arrives, the reasoning that committed Joe Hand will most likely apply to it too.

> ⚠️ **History caveat.** Four faces were committed, then untracked, and GitHub still serves the
> purged blobs at the pre-rewrite SHAs. See the Security section of `CHANGELOG.md`. Joe Hand
> returning to the tree does not change that: the three *unlicensed* Optima conversions are
> the ones that matter, and clearing them still needs a GitHub Support `gc` request or a repo
> recreate before this repository could ever be public.
