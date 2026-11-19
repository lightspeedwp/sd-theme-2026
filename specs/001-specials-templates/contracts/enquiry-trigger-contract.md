# Contract: The enquiry trigger

**Between**: this page (emits) and the shared enquiry modal + form, funded on lines 15 and 16
(consumes).

**Why a contract**: FR-018 requires the enquiry surface to open "carrying that band's offer
name", and SC-007 requires it to be the *right* offer on every band — not the first rendered,
not the last. FR-019 forbids this feature from building the form or the modal. So the offer
identity has to cross a boundary, and the crossing has to be specified before either side is
built.

---

## What already exists

`SD\Enhancements\Enquiry::register_trigger_modal()`
(`sd-enhancements-2026/modules/enquiry.php:96-137`) filters `render_block_core/button`. If a
button's rendered href matches `#to-modal-{slug}` and `{slug}` names a template part, it asks
Tour Operator's `Modals` class to print that modal's content, then returns the button
**unchanged**.

So the open-the-modal half is solved and needs nothing from this feature beyond using the
right href. `parts/modal-enquiry.html` is the part.

## What does not exist

The module carries **no per-offer payload**. It reads the href and returns. There is no route
today by which the band tells the form which offer it is.

## The seam

This feature emits, on each band's enquire button:

- `href="#to-modal-enquiry"` — the existing trigger convention, unchanged.
- A per-band identifier naming the offer, rendered from the queried post.

The identifier's exact attribute name and whether it carries the offer's **name**, its
**slug**, or its **post ID** is a decision for the implementation phase, taken with the line
15/16 owner rather than unilaterally here — the form's field decides which of the three it
can actually use. It must be settled before the band pattern is authored, because it changes
the markup.

Whatever is chosen:

- It is emitted **per band**, inside the `post-template`, so pagination and multiple bands on
  one page each carry their own value. A single page-level value fails SC-007 by construction.
- It is read by the modal at open time, from the trigger that was activated.
- The button remains a real, focusable control with a visible focus indicator and keyboard
  activation (FR-029), and reads *"Enquire about this special"* (FR-018).

## Degraded behaviour

If the enquiry modal is not yet present when this page is accepted, the button is inert
rather than broken, and the band is otherwise complete. This feature does not block on lines
15/16 (Assumption 10). SC-007 is then verified when that surface lands, not waived.
