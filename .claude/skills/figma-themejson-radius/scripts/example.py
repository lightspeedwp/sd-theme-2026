#!/usr/bin/env python3
"""
Radius preset helper for theme.json.

Usage:
  python3 scripts/example.py --name "small" --value 4 --code "var(--wp--preset--border-radius--100)"
  python3 scripts/example.py --name "round" --value 9999 --code "var(--wp--preset--border-radius--500)"
"""

import argparse
import json
import re


SLUG_RE = re.compile(r"--wp--preset--border-radius--(?P<slug>[a-zA-Z0-9-]+)")
UNIT_RE = re.compile(r"[a-zA-Z%]+$")


def extract_slug(value: str) -> str | None:
    if not value:
        return None
    match = SLUG_RE.search(value)
    return match.group("slug") if match else None


def format_size(value: str) -> str:
    value = str(value).strip()
    if value in {"0", "0.0", "0.00"}:
        return "0"
    if UNIT_RE.search(value):
        return value
    return f"{value}px"


def main() -> None:
    parser = argparse.ArgumentParser(description="Build theme.json border radius preset")
    parser.add_argument("--name", required=True, help="Preset label")
    parser.add_argument("--value", required=True, help="Preset size value")
    parser.add_argument("--code", required=True, help="Code Syntax string")
    args = parser.parse_args()

    slug = extract_slug(args.code)
    if not slug:
        raise SystemExit("Could not extract slug from code syntax.")

    payload = {
        "name": args.name,
        "slug": slug,
        "size": format_size(args.value),
    }

    print(json.dumps(payload, indent=2))


if __name__ == "__main__":
    main()
