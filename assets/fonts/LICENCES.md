# Font licences — `assets/fonts/`

Provenance and rights for every face registered in `theme.json`
(`settings.typography.fontFamilies[].fontFace`).

Licence documents are held client-side, not in this repository:
`docs/SD Fonts & Licenses/` in the workspace checkout.

**Licence owner for both commercial faces: Southern Destinations.** LightSpeed acts as its
agent. Neither licence is transferable, so neither face may be redistributed by this repo —
see *Delivery* below.

---

## Cleared for production

| Face | Licence | Evidence |
|---|---|---|
| `open-sans-*` (7 faces) | Apache 2.0 | in-font `license` / `licenseURL` |
| `belleza-400-normal` | SIL OFL 1.1 | in-font `license` / `licenseURL` |
| `la-belle-aurore-400-normal` | SIL OFL 1.1 | in-font `license` / `licenseURL` |
| `joe-hand-400-normal` | **JOEBOB graphics Webfont EULA 1.0** (29 Jun 2015) | `CF-13-joeHand-2_WEB/JBgfx Webfont EULA.pdf` |

### Joe Hand — cleared, but capped

The file installed here is JOEBOB's **official webfont build**, `joehand_2_15-webfont.woff2`
(internal name `JoeHand 2_15 Regular Webfont`, 532 glyphs) — not the 226-glyph copy
previously converted from the live site. Web embedding is expressly granted by §1.1.

Three obligations ride with it:

- **§1.3 — 10,000 pageviews per copy, per month.** One copy is licensed. Southern
  Destinations' actual traffic almost certainly exceeds this; additional copies or an
  extended licence are needed. → LS-2642
- **§1.4 — one domain**, plus a maximum of 5 subdomains. `www.southerndestinations.com`
  is the licensed primary. `southerndestinations.lightspeedwp.dev` is a *different*
  domain and is **not** covered.
- **§1.5 — no hotlinking, no direct download.** The font must be reachable only as part of
  styling text on the licensed domain.

§1.2 excludes desktop installation, and §1.6 forbids transfer or sublicensing.

---

## 🔴 Not cleared — Optima

`theme.json` registers no Optima face. The heading stack still *names* Optima, so a
visitor with Optima installed locally (every macOS) renders it; everyone else resolves to
**Belleza**, which is registered as a real face for exactly this reason.

**What Southern Destinations owns:** MyFonts order **#9528082**, 24 July 2018, USD 55 —
*Optima Bold, 2 font styles, Linotype*, SKU `legacy_631558_2053_1`. Delivered as
`Optima LT Pro Bold` and `Optima LT Std Bold` — desktop **OTF/TTF**, `usWeightClass 700`,
`fsType 4`. EULA: <https://www.myfonts.com/pages/license-agreement?eula_lang=eula_en&id=eula_2053>

**Why that does not unblock the theme — three separate reasons:**

1. **No self-hosting kit.** The EULA grants website use of the *Web Font Software*
   "in a self-hosting kit or through a third party web font hosting service", and §3
   Restrictions states plainly: *"You may not link to, or put online, Web Font Software
   not supplied to you in a self-hosting kit."* Only desktop OTF/TTF were delivered.
   Converting them to WOFF2 ourselves also trips *"Modify the Software in any way"*.
2. **Tracking Code is mandatory.** *"You must use the Tracking Code supplied in our
   self-hosting kit on all Websites"* — not required on a Development Website — and §3
   forbids removing it. The Code only ships with the kit.
3. **Bold only.** The invoice's "2 font styles" are the Pro and Std cuts of the *same*
   Bold weight, not two weights. There is **no licence for Optima 400 or 500**, which
   `h4`/`h5` (weight 500) previously used.

Where the licence *is* permissive: **250,000 pageviews per month**, Development Websites
are explicitly allowed, and the "separate Agreement per client" clause is satisfied
because Southern Destinations is the named licence owner rather than the agency.

**Action:** Southern Destinations should sign in to the MyFonts account holding order
#9528082 and download the **webfont / self-hosting kit** for Optima Bold. Under the
current Software-for-Creatives terms web rights are bundled with the purchase, so this is
likely a download rather than a new purchase — but the 2018 order predates those terms, so
Monotype should confirm in writing which EULA governs it. → LS-2641

### Removed 2026-08-18 — three unlicensed Optima faces

Converted from the live site during LS-2012 and covered by no licence at all:

| File | Real identity | Embedding bits |
|---|---|---|
| `optima-400-normal.woff2` | `Optima` — **©1991 AG Baltia**, a 1993 clone, not Monotype's | `fsType 1` — **embedding forbidden outright** |
| `optima-500-normal.woff2` | `Optima Medium` — Adobe Systems 1995 | `fsType 260` — preview/print, **subsetting forbidden** |
| `optima-700-normal.woff2` | `Optima Demi Bold` — Adobe Systems 1995 | `fsType 4` — preview/print only |

None is the face the client licensed. `fsType 1` on the 400 is the sharpest point: that
font's own metadata refuses embedding. The live site serves an equivalent conversion
(`optima-demibold_1-webfont.woff2`) today, so this exposure predates the rebuild and
carries over to it. → LS-2641

`Optima_Italic.ttf` was never ported: the live source is malformed (`glyf` overlaps `cmap`)
and `woff2_compress` rejects it. It never loaded on live either.

---

## Delivery

Both commercial faces are **`.gitignore`d and untracked**, and reach the server through the
build/deploy pipeline. This is now about redistribution, not doubt: Joe Hand's rights are
confirmed, but §1.5/§1.6 make the file non-redistributable and non-transferable, and a
repository is a distribution channel. The Apache/OFL faces are committed.

A fresh clone therefore carries 9 of 10 registered faces; `joe-hand-400-normal.woff2`
arrives from the pipeline. Until it does, the `accent` family resolves to La Belle Aurore
(bundled) and then `cursive`. Degraded, not broken.

> ⚠️ **History caveat.** The four faces were committed before being untracked, and GitHub
> still serves the purged blobs at the pre-rewrite SHAs. See the Security section of
> `CHANGELOG.md`. That remains true and needs a GitHub Support `gc` request or a repo
> recreate before this repository could ever be public.
