#!/usr/bin/env python3
"""
Shadow helper for theme.json presets.

Usage:
  python3 scripts/example.py --x 0.5 --y 2 --blur 3 --spread 0.5 --color "#111111@20%"
  python3 scripts/example.py --x 1 --y 4 --blur 12 --spread 4 --color "rgba(17,17,17,0.3)" --slug "var(--wp--preset--shadow--400)"
"""

import argparse
import re


HEX_RE = re.compile(r"^#(?P<hex>[0-9a-fA-F]{6})$")
HEX_ALPHA_RE = re.compile(r"^#(?P<hex>[0-9a-fA-F]{6})\s*@\s*(?P<pct>\d+(?:\.\d+)?)%$")
SLUG_RE = re.compile(r"--wp--preset--shadow--(?P<slug>[a-zA-Z0-9-]+)")


def parse_color(value: str) -> str:
    value = value.strip()
    if value.lower().startswith("rgba(") or value.lower().startswith("rgb("):
        return value

    match_alpha = HEX_ALPHA_RE.match(value)
    if match_alpha:
        hex_value = match_alpha.group("hex")
        pct = float(match_alpha.group("pct")) / 100
        r = int(hex_value[0:2], 16)
        g = int(hex_value[2:4], 16)
        b = int(hex_value[4:6], 16)
        return f"rgba({r}, {g}, {b}, {pct:.3g})"

    match_hex = HEX_RE.match(value)
    if match_hex:
        hex_value = match_hex.group("hex")
        r = int(hex_value[0:2], 16)
        g = int(hex_value[2:4], 16)
        b = int(hex_value[4:6], 16)
        return f"rgb({r}, {g}, {b})"

    raise ValueError(f"Unsupported color format: {value}")


def ensure_unit(value: str, unit: str) -> str:
    value = str(value).strip()
    if re.search(r"[a-zA-Z%]$", value):
        return value
    return f"{value}{unit}"


def extract_slug(value: str) -> str | None:
    if not value:
        return None
    match = SLUG_RE.search(value)
    return match.group("slug") if match else None


def main():
    parser = argparse.ArgumentParser(description="Build theme.json shadow strings")
    parser.add_argument("--x", required=True, help="Shadow x offset")
    parser.add_argument("--y", required=True, help="Shadow y offset")
    parser.add_argument("--blur", required=True, help="Shadow blur radius")
    parser.add_argument("--spread", required=True, help="Shadow spread radius")
    parser.add_argument("--color", required=True, help="Color in rgba() or #RRGGBB@% format")
    parser.add_argument("--unit", default="px", help="Unit for numeric values (default: px)")
    parser.add_argument("--slug", help="Code syntax to extract slug from")
    args = parser.parse_args()

    shadow = " ".join(
        [
            ensure_unit(args.x, args.unit),
            ensure_unit(args.y, args.unit),
            ensure_unit(args.blur, args.unit),
            ensure_unit(args.spread, args.unit),
            parse_color(args.color),
        ]
    )

    slug = extract_slug(args.slug) if args.slug else None

    print("shadow:", shadow)
    if slug:
        print("slug:", slug)


if __name__ == "__main__":
    main()
