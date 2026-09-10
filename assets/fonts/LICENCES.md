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
| `optima-600-normal` | **Monotype webfont licence**, DemiBold only, annually renewable | `docs/DS Optima DemiBold/` kit, MyFonts build 3867246 |

Six faces, **236 KB**, all committed.

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

## Optima — cleared 2026-09-10; DemiBold only, annually renewable

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
| *kit received 2026-09-10* | — | **the website** | **Web, annually renewable** | *Optima LT Pro DemiBold* — **one weight**, WOFF2 + WOFF |

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

### What the web licence means for the theme — one weight, renewed annually

**Only DemiBold (600) is licensed for the web.** Not 400, not 500, not 700. That matches
the live site, which serves an Optima Demi Bold and nothing else, so it is a
faithful-to-live constraint rather than a compromise — but it had two consequences the
theme has now answered for:

- **Heading weights were levelled to 600.** `theme.json` previously asked for 700 on
  `h1`/`h2`, 600 on `h3`, and 500 on `h4`/`h5`. A single 600 face cannot serve those
  honestly — 700 would be synthetically emboldened, which on Optima's modulated humanist
  strokes reads as smeared. `h1`, `h2`, `h4` and `h5` are now
  `var:custom|font-weight|semi-bold`, so **no synthetic weight is produced anywhere** and
  the weight tokens tell the truth about what is served. Heading hierarchy is carried by
  size, letter-spacing and case, as it is on live. (`h6` sets no weight and inherits 400;
  with only a 600 face registered it renders the 600 outline. Harmless, but it is the one
  remaining token that does not describe what is drawn.)
- 🔴 **The licence is annually renewable.** That is a recurring obligation on SD, not a
  one-off, and **if it lapses the face must be removed and headings fall back to Belleza**.
  It needs a diary entry on SD's side and a note in the handover pack. It is also the first
  recurring third-party cost in this build — worth recording on the Change-Control Register
  ([LS-2033](https://linear.app/lightspeedwp/issue/LS-2033)) as an operational item, not a
  scope change.

Kit received from SD **2026-09-10** and applied the same day, following
`.github/tasks/optima-webfont-kit-dropin-2026-09-09.md` → option A.
→ [LS-2641](https://linear.app/lightspeedwp/issue/LS-2641)

### The kit's licence obligation is a notice, not a tracking script

Reading `StartHere.html` in full settled a question the drop-in doc had left open. This is a
**MyFonts self-hosting kit**, and its instructions are three steps: upload the kit, link
`MyWebfontsKit.css` from the `<head>` of every page, assign the family in CSS. **There is no
Tracking Code, counter script or beacon anywhere in it** — that belongs to Monotype's
*hosted* web-font service, which this is not. Nothing is owed on that front and nothing is
waiting to be installed.

What the kit *does* carry is an `@license` block, and the instruction that it travel in the
`<head>`. Two places now reproduce it verbatim:

| Where | What |
|---|---|
| `functions.php` → `font_licence_notice()` on `wp_head` (priority 1) | Prints the notice into every page, byte-identical to the kit |
| `assets/fonts/optima-600-normal.LICENSE.txt` | The same text, sitting beside the font it covers |

**`MyWebfontsKit.css` itself is deliberately not shipped or enqueued.** It is the kit's way
of getting an `@font-face` and the notice onto a page at once, and this theme already has the
`@font-face` from `theme.json`. Linking it as well would fetch the same file a second time
under a second family name — `OptimaProDemiBold`, which nothing here references — and its
relative `webFonts/OptimaProDemiBold/…` paths do not exist in this theme, so it would 404 on
top. The obligation is the notice; the notice is served. The pristine kit stays in
`docs/DS Optima DemiBold/` as the licence artefact.

🟡 **Still genuinely outstanding, and all of it sits with SD:** the *webfont* EULA itself
(only the desktop EULA `2275` is in the workspace), the invoice, and the pageview tier. The
kit is also stamped `MyFonts Webfont Build ID 3867246, 2020-12-16` — a 2020 build, which does
not match a September 2026 purchase and may mean an older kit was forwarded. **Ask SD for the
webfont order confirmation, its EULA and the pageview tier** before production launch. None
of it blocks dev.

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

### What the theme does

`theme.json` registers **one real Optima face** on the `heading` preset:

| | |
|---|---|
| File | `assets/fonts/optima-600-normal.woff2` (32 KB) |
| Source | `docs/DS Optima DemiBold/font.woff2`, renamed to the theme's convention |
| In-font identity | family `Optima LT Pro`, subfamily `SemiBold` / `Demi Bold`, PostScript `OptimaLTPro-DemiBold`, `usWeightClass 600`, foundry `MONO` |
| Registered as | `@font-face { font-family: Optima; font-weight: 600; font-display: swap }` |
| Stack | `Optima, "Optima LT Pro", Belleza, sans-serif` |

**`Optima` leads the stack deliberately.** WordPress overwrites a `fontFace`'s
`fontFamily` with the first name in its preset's stack, so leaving `"Optima LT Pro"` first
would rename the `@font-face` rule to `Optima LT Pro` — where, on a macOS design machine
with the desktop OTFs installed, it would be shadowed by a locally-installed family of the
same name that carries only 400 and 700. The served family therefore has to be named
first, and `"Optima LT Pro"` demotes to a fallback, which is still useful: the design
machines have it. Belleza remains the real registered fallback, so a lapsed licence or a
404 degrades rather than breaks. → `style.md` §3.7

**This is the same design as live, but a different and better cut.** Live serves a face
whose internal name is `Optima Demi Bold` (Adobe Systems, 1995) under the CSS family
`optimademi_bold`; the licensed kit is Monotype's `OptimaLTPro-DemiBold`. Verified by
`fc-scan` on both files, 2026-09-10 — they are not the same binary.

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

**Optima is now committed too**, as of 2026-09-10, on exactly that reasoning. The
`assets/fonts/optima-*.woff2` ignore rule has been removed: it existed because no
web-licensed file existed, and the kit's arrival retired that reason. Monotype's webfont
transfer restrictions read much like JOEBOB's, and the same answer applies — private repo,
agency-plus-client access, the client is the licence owner — with the added evidence that
ignoring a face is precisely what made Joe Hand 404 on dev.

One thing does **not** carry over from Joe Hand: Monotype's webfont terms explicitly exempt
Development Websites, where JOEBOB's have no such carve-out. Serving Optima on
`southerndestinations.lightspeedwp.dev` is therefore unambiguously fine, and the Tracking
Code should be suppressed there once it is supplied.

The twelve **desktop** OTFs in `docs/myfonts_order_7491875209386/` remain unusable here:
converting them is the §4 breach described above. Never convert them — the licensed WOFF2
already ships.

⚠️ **Both commercial faces now depend on the repo staying private**, and Optima adds a second
condition: **if the annual licence lapses, the file must be removed**, not merely left in
place. A deploy check of `curl -I` on both font URLs belongs on the release checklist — that
one check would have caught the Joe Hand gap on day one.

> ⚠️ **History caveat.** Four faces were committed, then untracked, and GitHub still serves the
> purged blobs at the pre-rewrite SHAs. See the Security section of `CHANGELOG.md`. Joe Hand
> returning to the tree does not change that: the three *unlicensed* Optima conversions are
> the ones that matter, and clearing them still needs a GitHub Support `gc` request or a repo
> recreate before this repository could ever be public.
