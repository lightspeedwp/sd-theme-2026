# Structured data audit

schema.org release 30.1; 26 page(s); 0 error(s), 28 warning(s), 74 note(s).

| Page | HTTP | Generators | Types | Errors | Warnings | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| https://beta.local/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/hello-world/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/review/empty-review/ | 200 | unattributed | Person, Review | 0 | 3 | 0 |
| https://beta.local/reviews/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/accommodation/clouds-estate/ | 200 | unattributed | GeoCoordinates, LodgingBusiness, TouristDestination | 0 | 1 | 4 |
| https://beta.local/accommodation/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/destination/south-africa/stellenbosch/ | 200 | unattributed | GeoCoordinates, TouristDestination | 0 | 1 | 2 |
| https://beta.local/destinations/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/tour/western-cape-in-8-days/ | 200 | unattributed | LodgingBusiness, Offer, Place, Product, PropertyValue, TouristDestination, TouristTrip | 0 | 1 | 32 |
| https://beta.local/tours/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/special/giraffe-conservation-safari/ | 200 | unattributed | Offer, UnitPriceSpecification | 0 | 1 | 0 |
| https://beta.local/specials/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/team/warwick-booth/ | 200 | unattributed | LodgingBusiness, Person, TouristTrip | 0 | 1 | 3 |
| https://beta.local/team/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/category/uncategorized/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/accommodation-type/hotel/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/travel-style/self-drive/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/facility/available-services/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/continent/africa/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/author/admin/ | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/?s=a | 200 | - | - | 0 | 1 | 0 |
| https://beta.local/review/rachel-nigel/ | 200 | unattributed | LodgingBusiness, Person, Rating, Review, TouristDestination, TouristTrip | 0 | 1 | 5 |
| https://beta.local/destination/zimbabwe/ | 200 | unattributed | GeoCoordinates, PropertyValue, TouristDestination | 0 | 1 | 2 |
| https://beta.local/special/best-of-the-desert-special-11-nights/ | 200 | unattributed | Offer, UnitPriceSpecification | 0 | 1 | 0 |
| https://beta.local/tour/phuket-krabi-thailand-14-nights/ | 200 | unattributed | LodgingBusiness, Offer, Place, Product, PropertyValue, TouristDestination, TouristTrip | 0 | 1 | 24 |
| https://beta.local/destination/namibia/ | 200 | unattributed | GeoCoordinates, PropertyValue, TouristDestination | 0 | 1 | 2 |

## Findings

| Severity | Check | Detail | Pages |
| --- | --- | --- | --- |
| warn | no-structured-data | The page has no JSON-LD. | 15: https://beta.local/, https://beta.local/hello-world/, https://beta.local/reviews/ and 12 more |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/review/empty-review/, which no block on the page defines. | 1: https://beta.local/review/empty-review/ |
| warn | rich-result-required | Review needs 'reviewRating' for a Google rich result. | 1: https://beta.local/review/empty-review/ |
| warn | rich-result-required | Review needs 'itemReviewed' for a Google rich result. | 1: https://beta.local/review/empty-review/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/accommodation/clouds-estate/, which no block on the page defines. | 1: https://beta.local/accommodation/clouds-estate/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/destination/south-africa/stellenbosch/, which no block on the page defines. | 1: https://beta.local/destination/south-africa/stellenbosch/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/tour/western-cape-in-8-days/, which no block on the page defines. | 1: https://beta.local/tour/western-cape-in-8-days/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/special/giraffe-conservation-safari/, which no block on the page defines. | 1: https://beta.local/special/giraffe-conservation-safari/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/team/warwick-booth/, which no block on the page defines. | 1: https://beta.local/team/warwick-booth/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/review/rachel-nigel/, which no block on the page defines. | 1: https://beta.local/review/rachel-nigel/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/destination/zimbabwe/, which no block on the page defines. | 1: https://beta.local/destination/zimbabwe/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/special/best-of-the-desert-special-11-nights/, which no block on the page defines. | 1: https://beta.local/special/best-of-the-desert-special-11-nights/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/tour/phuket-krabi-thailand-14-nights/, which no block on the page defines. | 1: https://beta.local/tour/phuket-krabi-thailand-14-nights/ |
| warn | unresolved-reference | 'mainEntityOfPage' points to https://beta.local/destination/namibia/, which no block on the page defines. | 1: https://beta.local/destination/namibia/ |
| info | pending-type | 'TouristDestination' is still in schema.org's pending section. | 7: https://beta.local/accommodation/clouds-estate/, https://beta.local/destination/south-africa/stellenbosch/, https://beta.local/tour/western-cape-in-8-days/ and 4 more |
| info | pending-type | 'TouristTrip' is still in schema.org's pending section. | 4: https://beta.local/tour/western-cape-in-8-days/, https://beta.local/team/warwick-booth/, https://beta.local/review/rachel-nigel/ and 1 more |
| info | pending-property | 'subTrip' is still in schema.org's pending section. | 2: https://beta.local/tour/western-cape-in-8-days/, https://beta.local/tour/phuket-krabi-thailand-14-nights/ |
| info | pending-property | 'itinerary' is still in schema.org's pending section. | 2: https://beta.local/tour/western-cape-in-8-days/, https://beta.local/tour/phuket-krabi-thailand-14-nights/ |
| info | html-entity | 'name' contains an HTML entity (for example &#8211;) instead of the character. | 2: https://beta.local/tour/western-cape-in-8-days/, https://beta.local/tour/phuket-krabi-thailand-14-nights/ |
| info | rich-result-recommended | Offer should have 'availability'. | 2: https://beta.local/tour/western-cape-in-8-days/, https://beta.local/tour/phuket-krabi-thailand-14-nights/ |
| info | rich-result-recommended | Offer should have 'url'. | 2: https://beta.local/tour/western-cape-in-8-days/, https://beta.local/tour/phuket-krabi-thailand-14-nights/ |
| info | rich-result-recommended | LocalBusiness should have 'telephone'. | 1: https://beta.local/accommodation/clouds-estate/ |
| info | address-text | 'address' is plain text; Google expects a PostalAddress. | 1: https://beta.local/accommodation/clouds-estate/ |

## https://beta.local/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/ | The page has no JSON-LD. |

## https://beta.local/hello-world/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/hello-world/ | The page has no JSON-LD. |

## https://beta.local/review/empty-review/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<Review>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/review/empty-review/, which no block on the page defines. |
| warn | rich-result-required | block0.@graph[0]<Review> | Review needs 'reviewRating' for a Google rich result. |
| warn | rich-result-required | block0.@graph[0]<Review> | Review needs 'itemReviewed' for a Google rich result. |

## https://beta.local/reviews/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/reviews/ | The page has no JSON-LD. |

## https://beta.local/accommodation/clouds-estate/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<LodgingBusiness>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/accommodation/clouds-estate/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<LodgingBusiness>.containedInPlace[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<LodgingBusiness>.containedInPlace[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | rich-result-recommended | block0.@graph[0]<LodgingBusiness> | LocalBusiness should have 'telephone'. |
| info | address-text | block0.@graph[0]<LodgingBusiness> | 'address' is plain text; Google expects a PostalAddress. |

## https://beta.local/accommodation/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/accommodation/ | The page has no JSON-LD. |

## https://beta.local/destination/south-africa/stellenbosch/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<TouristDestination>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/destination/south-africa/stellenbosch/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristDestination>.containedInPlace<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |

## https://beta.local/destinations/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/destinations/ | The page has no JSON-LD. |

## https://beta.local/tour/western-cape-in-8-days/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<TouristTrip/Product>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/tour/western-cape-in-8-days/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product> | 'subTrip' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | html-entity | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip>.itinerary[0]<LodgingBusiness>.name | 'name' contains an HTML entity (for example &#8211;) instead of the character. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[1]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[1]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[1]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[3]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[3]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[3]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[4]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[4]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[4]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[5]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[5]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[5]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[6]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[6]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[6]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[7]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[7]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[7]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[8]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[8]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[8]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | rich-result-recommended | block0.@graph[0]<TouristTrip/Product>.offers<Offer> | Offer should have 'availability'. |
| info | rich-result-recommended | block0.@graph[0]<TouristTrip/Product>.offers<Offer> | Offer should have 'url'. |

## https://beta.local/tours/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/tours/ | The page has no JSON-LD. |

## https://beta.local/special/giraffe-conservation-safari/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<Offer>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/special/giraffe-conservation-safari/, which no block on the page defines. |

## https://beta.local/specials/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/specials/ | The page has no JSON-LD. |

## https://beta.local/team/warwick-booth/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<Person>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/team/warwick-booth/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<Person>.knowsAbout[0]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<Person>.knowsAbout[1]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<Person>.knowsAbout[2]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |

## https://beta.local/team/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/team/ | The page has no JSON-LD. |

## https://beta.local/category/uncategorized/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/category/uncategorized/ | The page has no JSON-LD. |

## https://beta.local/accommodation-type/hotel/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/accommodation-type/hotel/ | The page has no JSON-LD. |

## https://beta.local/travel-style/self-drive/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/travel-style/self-drive/ | The page has no JSON-LD. |

## https://beta.local/facility/available-services/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/facility/available-services/ | The page has no JSON-LD. |

## https://beta.local/continent/africa/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/continent/africa/ | The page has no JSON-LD. |

## https://beta.local/author/admin/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/author/admin/ | The page has no JSON-LD. |

## https://beta.local/?s=a

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | no-structured-data | https://beta.local/?s=a | The page has no JSON-LD. |

## https://beta.local/review/rachel-nigel/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<Review>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/review/rachel-nigel/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<Review>.itemReviewed[0]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<Review>.itemReviewed[2]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<Review>.itemReviewed[3]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<Review>.spatialCoverage[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<Review>.spatialCoverage[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |

## https://beta.local/destination/zimbabwe/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<TouristDestination>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/destination/zimbabwe/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristDestination>.containsPlace[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |

## https://beta.local/special/best-of-the-desert-special-11-nights/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<Offer>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/special/best-of-the-desert-special-11-nights/, which no block on the page defines. |

## https://beta.local/tour/phuket-krabi-thailand-14-nights/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<TouristTrip/Product>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/tour/phuket-krabi-thailand-14-nights/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product> | 'TouristTrip' is still in schema.org's pending section. |
| info | html-entity | block0.@graph[0]<TouristTrip/Product>.name | 'name' contains an HTML entity (for example &#8211;) instead of the character. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product> | 'subTrip' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[0]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[1]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[1]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[1]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | html-entity | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip>.itinerary[0]<LodgingBusiness>.name | 'name' contains an HTML entity (for example &#8211;) instead of the character. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[2]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[3]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[3]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[3]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[4]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[4]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[4]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[5]<TouristTrip> | 'TouristTrip' is still in schema.org's pending section. |
| info | pending-property | block0.@graph[0]<TouristTrip/Product>.subTrip[5]<TouristTrip> | 'itinerary' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristTrip/Product>.subTrip[5]<TouristTrip>.itinerary[1]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | rich-result-recommended | block0.@graph[0]<TouristTrip/Product>.offers<Offer> | Offer should have 'availability'. |
| info | rich-result-recommended | block0.@graph[0]<TouristTrip/Product>.offers<Offer> | Offer should have 'url'. |

## https://beta.local/destination/namibia/

| Severity | Check | Where | Detail |
| --- | --- | --- | --- |
| warn | unresolved-reference | block0.@graph[0]<TouristDestination>.mainEntityOfPage | 'mainEntityOfPage' points to https://beta.local/destination/namibia/, which no block on the page defines. |
| info | pending-type | block0.@graph[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
| info | pending-type | block0.@graph[0]<TouristDestination>.containsPlace[0]<TouristDestination> | 'TouristDestination' is still in schema.org's pending section. |
