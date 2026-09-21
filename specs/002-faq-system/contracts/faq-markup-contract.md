# Contract: FAQ markup shape (theme → `ls-plugin`)

**Status**: Draft — this repository's commitment. Not yet cross-checked against `ls-plugin`'s
own plan, because that plan does not exist yet (out of scope for LS-4215's theme-side work —
see spec Clarifications Q1). Whoever plans the `ls-plugin` side of LS-4215 should read this
file before designing the schema-tag consumer.

## What this theme commits to

Every rendered FAQ pattern instance (`patterns/faq-section.php`, wherever it is placed)
produces, on the front end, exactly this shape:

```html
<div class="wp-block-accordion is-style-faq">
  <div class="wp-block-accordion-item">
    <h3 class="wp-block-accordion-heading">
      <button class="wp-block-accordion-heading__toggle" aria-expanded="false">
        {question text}
      </button>
    </h3>
    <div class="wp-block-accordion-panel" hidden="until-found">
      {answer rich text — one or more block-level elements}
    </div>
  </div>
  <!-- one .wp-block-accordion-item per FAQ entry, repeated -->
</div>
```

(Exact class names and DOM nesting are WordPress core's `core/accordion` output as of
7.1 — this theme does not alter the block's rendered structure, only its `styles/blocks/
accordion/faq.json` presentation. If core changes this markup in a future version, this
contract updates to match; this theme does not pin an old shape.)

## Guarantees

1. **One `.wp-block-accordion-item` per question/answer pair.** No pair is split across two
   items, and no item holds more than one question/answer pair.
2. **The question is the accordion-heading's rendered text.** No question text exists
   anywhere else in the DOM (no duplicate hidden label, no `data-*` attribute copy).
3. **The answer is the accordion-panel's rendered inner content**, verbatim as authored —
   no truncation, no stripped formatting.
4. **No parallel copy of question/answer content exists in post meta, a block attribute, or
   an HTML comment** for schema purposes. If `ls-plugin` needs FAQ data, it reads this DOM
   shape (via a render-time hook such as `render_block` on `core/accordion`, or a front-end
   parse) — this theme does not pre-package the data for it, per FR-014/FR-015 and R-05 in
   research.md.
5. **`is-style-faq` is present on every FAQ accordion this pattern produces**, and is not
   reused by any other accordion placement in this theme (`call-us-dropdown` uses its own
   distinct style slug). `ls-plugin` can use this class as a reliable selector for "this
   accordion is an FAQ" versus any other accordion instance on the same page.
6. **Order in the DOM matches editorial order.** `ls-plugin` does not need to re-sort.
7. **An FAQ pattern instance with zero entries produces no `.wp-block-accordion` in the DOM
   at all** (per FR-002) — `ls-plugin`'s schema layer does not need to special-case an empty
   accordion; it simply won't find one to read.

## Explicitly not guaranteed / out of scope here

- Whether `ls-plugin` reads this via `render_block`, a REST field, block bindings, or a
  build-time crawl is entirely `ls-plugin`'s decision.
- This theme makes no claim about *how many* FAQ instances exist per page (a page could
  theoretically carry more than one if an editor inserts the pattern twice) — `ls-plugin`
  should treat every matching accordion as its own FAQ set if it needs to distinguish them,
  though the editorial workflow (FR-010) does not encourage more than one per page.
- Draft/AI-generated FAQ content: this contract only describes **published**, rendered
  output. Draft content by definition is not rendered on the front end and is therefore not
  visible to whatever reads this contract.
