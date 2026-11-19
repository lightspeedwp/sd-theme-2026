# Structured data report: Southern Destinations (beta.local, Yoast active)

- **Site:** https://beta.local
- **Date:** 2026-10-19
- **Pages checked:** 26
- **Checked against:** schema.org release 30.1, and Google's requirements for enhanced search listings

## Summary

Structured data is code in each page that tells search engines what the page is about: a tour,
a review, a special offer, a business. It does not change what visitors see. Done well, it helps
search engines understand the site and can earn enhanced listings, such as prices or breadcrumbs.

None of the 26 pages is free of issues yet. We found 3 issues to fix and 10 suggestions for improvement.

One issue affects every page, usually because of a single site-wide setting, so fixing it once clears it everywhere: "A field is present but empty" ('description' is empty.).

| Priority | Issues | What it means |
| --- | --- | --- |
| Must fix | 0 | Search engines cannot read this structured data at all. |
| Should fix | 3 | Valid in part, but some information is ignored or a listing enhancement is lost. |
| Could improve | 10 | Works today; these would make the information richer. |

## What each page describes

| Page | Kind of page | Describes | Status |
| --- | --- | --- | --- |
| [/](https://beta.local/) | - | Web page (WebPage) | 1 to fix |
| [/hello-world/](https://beta.local/hello-world/) | - | Article, Person, Web page (WebPage) | 1 to fix |
| [/review/empty-review/](https://beta.local/review/empty-review/) | - | Person, Review, Web page (WebPage) | 3 to fix |
| [/reviews/](https://beta.local/reviews/) | - | Listing page (CollectionPage) | 1 to fix |
| [/accommodation/clouds-estate/](https://beta.local/accommodation/clouds-estate/) | - | Answer, FAQ page (FAQPage), Accommodation (LodgingBusiness), Question, Destination (TouristDestination), Web page (WebPage) | 1 to fix |
| [/accommodation/](https://beta.local/accommodation/) | - | Listing page (CollectionPage) | 1 to fix |
| [/destination/south-africa/stellenbosch/](https://beta.local/destination/south-africa/stellenbosch/) | - | Destination (TouristDestination), Web page (WebPage) | 1 to fix |
| [/destinations/](https://beta.local/destinations/) | - | Listing page (CollectionPage) | 1 to fix |
| [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/) | - | FAQ page (FAQPage), Accommodation (LodgingBusiness), Offer, Place, Product, Destination (TouristDestination), Tour (TouristTrip), Web page (WebPage) | 1 to fix |
| [/tours/](https://beta.local/tours/) | - | Listing page (CollectionPage) | 1 to fix |
| [/special/giraffe-conservation-safari/](https://beta.local/special/giraffe-conservation-safari/) | - | Offer, Web page (WebPage) | 1 to fix |
| [/specials/](https://beta.local/specials/) | - | Listing page (CollectionPage) | 1 to fix |
| [/team/warwick-booth/](https://beta.local/team/warwick-booth/) | - | Accommodation (LodgingBusiness), Person, Tour (TouristTrip), Web page (WebPage) | 1 to fix |
| [/team/](https://beta.local/team/) | - | Listing page (CollectionPage) | 1 to fix |
| [/category/uncategorized/](https://beta.local/category/uncategorized/) | - | Listing page (CollectionPage) | 1 to fix |
| [/accommodation-type/hotel/](https://beta.local/accommodation-type/hotel/) | - | Listing page (CollectionPage) | 1 to fix |
| [/travel-style/self-drive/](https://beta.local/travel-style/self-drive/) | - | Listing page (CollectionPage) | 1 to fix |
| [/facility/available-services/](https://beta.local/facility/available-services/) | - | Listing page (CollectionPage) | 1 to fix |
| [/continent/africa/](https://beta.local/continent/africa/) | - | Listing page (CollectionPage) | 1 to fix |
| [/author/admin/](https://beta.local/author/admin/) | - | Person, Profile page (ProfilePage) | 1 to fix |
| [/?s=a](https://beta.local/?s=a) | - | Listing page (CollectionPage), Search results (SearchResultsPage) | 1 to fix |
| [/review/rachel-nigel/](https://beta.local/review/rachel-nigel/) | - | Accommodation (LodgingBusiness), Person, Review, Destination (TouristDestination), Tour (TouristTrip), Web page (WebPage) | 1 to fix |
| [/destination/zimbabwe/](https://beta.local/destination/zimbabwe/) | - | Destination (TouristDestination), Web page (WebPage) | 1 to fix |
| [/special/best-of-the-desert-special-11-nights/](https://beta.local/special/best-of-the-desert-special-11-nights/) | - | Offer, Web page (WebPage) | 1 to fix |
| [/tour/phuket-krabi-thailand-14-nights/](https://beta.local/tour/phuket-krabi-thailand-14-nights/) | - | Answer, FAQ page (FAQPage), Accommodation (LodgingBusiness), Offer, Place, Product, Question, Destination (TouristDestination), Tour (TouristTrip), Web page (WebPage) | 1 to fix |
| [/destination/namibia/](https://beta.local/destination/namibia/) | - | Answer, FAQ page (FAQPage), Question, Destination (TouristDestination), Web page (WebPage) | 1 to fix |

## Issues to fix

### 1. A field is present but empty

- **Priority:** Should fix
- **What we found:** 'description' is empty.
- **Why it matters:** Empty fields add nothing and can look like an error to search engines.
- **What to do:** Fill in the value (often a site setting such as the tagline) or stop outputting the field.
- **Pages affected (26):** [/](https://beta.local/), [/hello-world/](https://beta.local/hello-world/), [/review/empty-review/](https://beta.local/review/empty-review/), [/reviews/](https://beta.local/reviews/), [/accommodation/clouds-estate/](https://beta.local/accommodation/clouds-estate/), [/accommodation/](https://beta.local/accommodation/), [/destination/south-africa/stellenbosch/](https://beta.local/destination/south-africa/stellenbosch/), [/destinations/](https://beta.local/destinations/), [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/tours/](https://beta.local/tours/) and 16 more

### 2. Missing information Google needs for an enhanced search listing

- **Priority:** Should fix
- **What we found:** Review needs 'reviewRating' for a Google rich result.
- **Why it matters:** Without it, Google will not show this page with extras such as review stars, prices or breadcrumbs.
- **What to do:** Add the missing property named in the finding.
- **Pages affected (1):** [/review/empty-review/](https://beta.local/review/empty-review/)

### 3. Missing information Google needs for an enhanced search listing

- **Priority:** Should fix
- **What we found:** Review needs 'itemReviewed' for a Google rich result.
- **Why it matters:** Without it, Google will not show this page with extras such as review stars, prices or breadcrumbs.
- **What to do:** Add the missing property named in the finding.
- **Pages affected (1):** [/review/empty-review/](https://beta.local/review/empty-review/)

## Suggestions for improvement

| Suggestion | Detail | Pages |
| --- | --- | --- |
| Uses a type schema.org has not finalised | 'TouristDestination' is still in schema.org's pending section. | [/accommodation/clouds-estate/](https://beta.local/accommodation/clouds-estate/), [/destination/south-africa/stellenbosch/](https://beta.local/destination/south-africa/stellenbosch/), [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/) and 4 more |
| Uses a type schema.org has not finalised | 'TouristTrip' is still in schema.org's pending section. | [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/team/warwick-booth/](https://beta.local/team/warwick-booth/), [/review/rachel-nigel/](https://beta.local/review/rachel-nigel/) and 1 more |
| Text contains HTML codes instead of characters | 'name' contains an HTML entity (for example &#8211;) instead of the character. | [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/review/rachel-nigel/](https://beta.local/review/rachel-nigel/), [/tour/phuket-krabi-thailand-14-nights/](https://beta.local/tour/phuket-krabi-thailand-14-nights/) |
| Uses a property schema.org has not finalised | 'subTrip' is still in schema.org's pending section. | [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/tour/phuket-krabi-thailand-14-nights/](https://beta.local/tour/phuket-krabi-thailand-14-nights/) |
| Uses a property schema.org has not finalised | 'itinerary' is still in schema.org's pending section. | [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/tour/phuket-krabi-thailand-14-nights/](https://beta.local/tour/phuket-krabi-thailand-14-nights/) |
| Could give Google more detail | Offer should have 'availability'. | [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/tour/phuket-krabi-thailand-14-nights/](https://beta.local/tour/phuket-krabi-thailand-14-nights/) |
| Could give Google more detail | Offer should have 'url'. | [/tour/western-cape-in-8-days/](https://beta.local/tour/western-cape-in-8-days/), [/tour/phuket-krabi-thailand-14-nights/](https://beta.local/tour/phuket-krabi-thailand-14-nights/) |
| Address is a single line of text | 'address' is plain text; Google expects a PostalAddress. | [/accommodation/clouds-estate/](https://beta.local/accommodation/clouds-estate/) |
| Could give Google more detail | Article should have 'image'. | [/hello-world/](https://beta.local/hello-world/) |
| Could give Google more detail | LocalBusiness should have 'telephone'. | [/accommodation/clouds-estate/](https://beta.local/accommodation/clouds-estate/) |

## How this check was made

- Each page was loaded as a visitor would see it, and its structured data was read from the page's code.
- Every type and property was checked against schema.org release 30.1, the shared vocabulary used by Google, Bing and others.
- Enhanced-listing requirements are a summary of Google's published guidance. Google decides which
  pages get enhanced listings; confirm important pages with Google's Rich Results Test
  (<https://search.google.com/test/rich-results>) once the site is public.
- Pages behind a login, and pages not in the list above, were not checked.
- A spreadsheet version of these findings (CSV) can be provided for tracking fixes.
