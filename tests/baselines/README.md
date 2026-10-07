# Baselines

Findings that predate the QA harness, recorded so CI can gate on anything
**new** while the old debt is cleared slice by slice.

| File | Holds | Written by |
|---|---|---|
| `eslint.json`, `stylelint.json`, `markdownlint.json` | Error counts per file and rule | `npm run lint:baseline:update` |
| `known-violations.json` | Contract-test findings (Jest), listed per file | Hand-edited — remove entries as they are fixed |

## The ratchet

`npm run lint:baseline` runs each linter through its own `npm run lint:*`
script and compares the result with these files. It fails in two cases:

- **A count goes up.** That is a new error. Fix it; don't re-baseline it.
- **A count goes down.** That is a fix, and it should be recorded. Run
  `npm run lint:baseline:update` and commit the smaller baseline in the same
  PR. The baseline can only shrink.

`npm run lint:js` and the other linters still report everything, including
the recorded debt, so the full picture is always one command away.

## Rules

- Never regenerate a baseline to make a failing check pass. The only reason
  to run `lint:baseline:update` is that the counts went **down**.
- Harness code (`tests/`, the config files) is never baselined. It lints
  clean.
- When a file reaches zero, its entry disappears from the baseline. When a
  whole baseline is empty, delete it and point CI back at the plain linter.
