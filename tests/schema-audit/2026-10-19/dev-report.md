# Structured data report: Southern Destinations (dev)

- **Site:** https://southerndestinations.lightspeedwp.dev
- **Date:** 2026-10-19
- **Pages checked:** 27
- **Checked against:** schema.org release 30.1, and Google's requirements for enhanced search listings

## Summary

Structured data is code in each page that tells search engines what the page is about: a tour,
a review, a special offer, a business. It does not change what visitors see. Done well, it helps
search engines understand the site and can earn enhanced listings, such as prices or breadcrumbs.

24 of 27 pages have nothing that needs fixing. We found 3 issues to fix and 10 suggestions for improvement.

| Priority | Issues | What it means |
| --- | --- | --- |
| Must fix | 0 | Search engines cannot read this structured data at all. |
| Should fix | 3 | Valid in part, but some information is ignored or a listing enhancement is lost. |
| Could improve | 10 | Works today; these would make the information richer. |

## What each page describes

| Page | Kind of page | Describes | Status |
| --- | --- | --- | --- |
| [/](https://southerndestinations.lightspeedwp.dev/) | Home page | Article, Blog post (BlogPosting), Listing page (CollectionPage), Person, Web page (WebPage) | OK |
| [/camille-experiences-the-magic-of-bush-and-beach-at-serondella-thornybush-and-santorini-mozambique/](https://southerndestinations.lightspeedwp.dev/camille-experiences-the-magic-of-bush-and-beach-at-serondella-thornybush-and-santorini-mozambique/) | Posts: single item | Article, Person, Web page (WebPage) | OK |
| [/review/lion-sands-was-truly-a-little-slice-of-heaven/](https://southerndestinations.lightspeedwp.dev/review/lion-sands-was-truly-a-little-slice-of-heaven/) | Reviews: single item | Accommodation (LodgingBusiness), Person, Review, Destination (TouristDestination), Tour (TouristTrip), Web page (WebPage) | 1 to fix |
| [/?post_type=review](https://southerndestinations.lightspeedwp.dev/?post_type=review) | Reviews archive | Listing page (CollectionPage) | OK |
| [/accommodation/bagatelle/](https://southerndestinations.lightspeedwp.dev/accommodation/bagatelle/) | Accommodation: single item | Accommodation (LodgingBusiness), Destination (TouristDestination), Web page (WebPage) | 1 to fix |
| [/?post_type=accommodation](https://southerndestinations.lightspeedwp.dev/?post_type=accommodation) | Accommodation archive | Listing page (CollectionPage) | OK |
| [/destination/rwanda/kigali/](https://southerndestinations.lightspeedwp.dev/destination/rwanda/kigali/) | Destinations: single item | Destination (TouristDestination), Web page (WebPage) | OK |
| [/?post_type=destination](https://southerndestinations.lightspeedwp.dev/?post_type=destination) | Destinations archive | Listing page (CollectionPage) | OK |
| [/tour/south-africa-mozambique/](https://southerndestinations.lightspeedwp.dev/tour/south-africa-mozambique/) | Tours: single item | Accommodation (LodgingBusiness), Place, Destination (TouristDestination), Tour (TouristTrip), Web page (WebPage) | OK |
| [/?post_type=tour](https://southerndestinations.lightspeedwp.dev/?post_type=tour) | Tours archive | Listing page (CollectionPage) | OK |
| [/special/thornybush-2026-specials/](https://southerndestinations.lightspeedwp.dev/special/thornybush-2026-specials/) | Specials: single item | Listing page (CollectionPage) | 1 to fix |
| [/?post_type=special](https://southerndestinations.lightspeedwp.dev/?post_type=special) | Specials archive | Listing page (CollectionPage) | OK |
| [/team/jeannot-blum/](https://southerndestinations.lightspeedwp.dev/team/jeannot-blum/) | Team: single item | Person, Web page (WebPage) | OK |
| [/?post_type=team](https://southerndestinations.lightspeedwp.dev/?post_type=team) | Team archive | Listing page (CollectionPage) | OK |
| [/category/news/](https://southerndestinations.lightspeedwp.dev/category/news/) | Categories: term page | Listing page (CollectionPage) | OK |
| [/tag/air-conditioning/](https://southerndestinations.lightspeedwp.dev/tag/air-conditioning/) | Tags: term page | Listing page (CollectionPage) | OK |
| [/brand/wilderness-safaris/](https://southerndestinations.lightspeedwp.dev/brand/wilderness-safaris/) | Brands: term page | Listing page (CollectionPage) | OK |
| [/accommodation-type/safari-lodges/](https://southerndestinations.lightspeedwp.dev/accommodation-type/safari-lodges/) | Accommodation Types: term page | Listing page (CollectionPage) | OK |
| [/continent/eastern-africa/](https://southerndestinations.lightspeedwp.dev/continent/eastern-africa/) | Continents: term page | Listing page (CollectionPage) | OK |
| [/facility/available-services/](https://southerndestinations.lightspeedwp.dev/facility/available-services/) | Facilities: term page | Listing page (CollectionPage) | OK |
| [/travel-style/safari-honeymoons/](https://southerndestinations.lightspeedwp.dev/travel-style/safari-honeymoons/) | Travel Styles: term page | Listing page (CollectionPage) | OK |
| [/blog/](https://southerndestinations.lightspeedwp.dev/blog/) | Page: blog | Article, Blog post (BlogPosting), Listing page (CollectionPage), Person, Web page (WebPage) | OK |
| [/privacy-policy/](https://southerndestinations.lightspeedwp.dev/privacy-policy/) | Page: privacy-policy | Article, Blog post (BlogPosting), Listing page (CollectionPage), Person, Web page (WebPage) | OK |
| [/terms-conditions/](https://southerndestinations.lightspeedwp.dev/terms-conditions/) | Page: terms-conditions | Article, Blog post (BlogPosting), Listing page (CollectionPage), Person, Web page (WebPage) | OK |
| [/contact/](https://southerndestinations.lightspeedwp.dev/contact/) | Page: contact | Article, Blog post (BlogPosting), Listing page (CollectionPage), Person, Web page (WebPage) | OK |
| [/about-us/](https://southerndestinations.lightspeedwp.dev/about-us/) | Page: about-us | About page (AboutPage), Article, Blog post (BlogPosting), Person, Web page (WebPage) | OK |
| [/?s=a](https://southerndestinations.lightspeedwp.dev/?s=a) | Search results | Listing page (CollectionPage), Search results (SearchResultsPage) | OK |

## Issues to fix

### 1. Page redirects elsewhere

- **Priority:** Should fix
- **What we found:** Redirects to https://southerndestinations.lightspeedwp.dev/specials/; its structured data is what crawlers see.
- **Why it matters:** Search engines see only the destination page, so this page's own structured data is never read.
- **What to do:** Decide whether the page should exist on its own; if not, make sure the destination describes it.
- **Pages affected (1):** [/special/thornybush-2026-specials/](https://southerndestinations.lightspeedwp.dev/special/thornybush-2026-specials/)

### 2. Missing information Google needs for an enhanced search listing

- **Priority:** Should fix
- **What we found:** Review needs 'reviewRating' for a Google rich result.
- **Why it matters:** Without it, Google will not show this page with extras such as review stars, prices or breadcrumbs.
- **What to do:** Add the missing property named in the finding.
- **Pages affected (1):** [/review/lion-sands-was-truly-a-little-slice-of-heaven/](https://southerndestinations.lightspeedwp.dev/review/lion-sands-was-truly-a-little-slice-of-heaven/)

### 3. Missing information Google needs for an enhanced search listing

- **Priority:** Should fix
- **What we found:** LocalBusiness needs 'address' for a Google rich result.
- **Why it matters:** Without it, Google will not show this page with extras such as review stars, prices or breadcrumbs.
- **What to do:** Add the missing property named in the finding.
- **Pages affected (1):** [/accommodation/bagatelle/](https://southerndestinations.lightspeedwp.dev/accommodation/bagatelle/)

## Suggestions for improvement

| Suggestion | Detail | Pages |
| --- | --- | --- |
| Uses a type schema.org has not finalised | 'TouristDestination' is still in schema.org's pending section. | [/review/lion-sands-was-truly-a-little-slice-of-heaven/](https://southerndestinations.lightspeedwp.dev/review/lion-sands-was-truly-a-little-slice-of-heaven/), [/accommodation/bagatelle/](https://southerndestinations.lightspeedwp.dev/accommodation/bagatelle/), [/destination/rwanda/kigali/](https://southerndestinations.lightspeedwp.dev/destination/rwanda/kigali/) and 1 more |
| Text contains HTML codes instead of characters | 'description' contains an HTML entity (for example &#8211;) instead of the character. | [/blog/](https://southerndestinations.lightspeedwp.dev/blog/), [/contact/](https://southerndestinations.lightspeedwp.dev/contact/), [/about-us/](https://southerndestinations.lightspeedwp.dev/about-us/) |
| Could give Google more detail | Article should have 'image'. | [/blog/](https://southerndestinations.lightspeedwp.dev/blog/), [/privacy-policy/](https://southerndestinations.lightspeedwp.dev/privacy-policy/), [/terms-conditions/](https://southerndestinations.lightspeedwp.dev/terms-conditions/) |
| Text contains HTML codes instead of characters | 'name' contains an HTML entity (for example &#8211;) instead of the character. | [/tour/south-africa-mozambique/](https://southerndestinations.lightspeedwp.dev/tour/south-africa-mozambique/), [/terms-conditions/](https://southerndestinations.lightspeedwp.dev/terms-conditions/) |
| Uses a type schema.org has not finalised | 'TouristTrip' is still in schema.org's pending section. | [/review/lion-sands-was-truly-a-little-slice-of-heaven/](https://southerndestinations.lightspeedwp.dev/review/lion-sands-was-truly-a-little-slice-of-heaven/), [/tour/south-africa-mozambique/](https://southerndestinations.lightspeedwp.dev/tour/south-africa-mozambique/) |
| Text contains HTML codes instead of characters | 'headline' contains an HTML entity (for example &#8211;) instead of the character. | [/terms-conditions/](https://southerndestinations.lightspeedwp.dev/terms-conditions/) |
| Uses a property schema.org has not finalised | 'subTrip' is still in schema.org's pending section. | [/tour/south-africa-mozambique/](https://southerndestinations.lightspeedwp.dev/tour/south-africa-mozambique/) |
| Uses a property schema.org has not finalised | 'itinerary' is still in schema.org's pending section. | [/tour/south-africa-mozambique/](https://southerndestinations.lightspeedwp.dev/tour/south-africa-mozambique/) |
| Could give Google more detail | LocalBusiness should have 'geo'. | [/accommodation/bagatelle/](https://southerndestinations.lightspeedwp.dev/accommodation/bagatelle/) |
| Could give Google more detail | LocalBusiness should have 'telephone'. | [/accommodation/bagatelle/](https://southerndestinations.lightspeedwp.dev/accommodation/bagatelle/) |

## How this check was made

- Each page was loaded as a visitor would see it, and its structured data was read from the page's code.
- Every type and property was checked against schema.org release 30.1, the shared vocabulary used by Google, Bing and others.
- Enhanced-listing requirements are a summary of Google's published guidance. Google decides which
  pages get enhanced listings; confirm important pages with Google's Rich Results Test
  (<https://search.google.com/test/rich-results>) once the site is public.
- Pages behind a login, and pages not in the list above, were not checked.
- A spreadsheet version of these findings (CSV) can be provided for tracking fixes.
- Not checked, with the reason: Popups (no published items), Popup Themes (HTTP 404), Calls to Action (no published items), Presets (no published items), Author archive (users endpoint not public).
