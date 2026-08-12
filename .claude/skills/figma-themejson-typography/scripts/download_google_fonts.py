#!/usr/bin/env python3
"""
Download WOFF2 files from a Google Fonts CSS2 URL.

Examples:
  python3 download_google_fonts.py \
    --css-url "https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900" \
    --out-dir /path/to/assets/fonts

Notes:
- Avoid fonts.google.com/download (returns HTML).
- If the CSS2 endpoint returns no WOFF2, use convert_to_woff2.py.
"""

from __future__ import annotations

import argparse
import os
import re
import sys
import urllib.request
from pathlib import Path

FONT_FACE_RE = re.compile(r"@font-face\s*\{[^}]+\}", re.MULTILINE)


def fetch_css(url: str) -> str:
    req = urllib.request.Request(
        url,
        headers={
            "User-Agent": "Mozilla/5.0",
        },
    )
    with urllib.request.urlopen(req) as resp:
        return resp.read().decode("utf-8")


def parse_blocks(css: str):
    return FONT_FACE_RE.findall(css)


def extract_value(block: str, prop: str) -> str | None:
    match = re.search(rf"{prop}:\s*([^;]+);", block)
    return match.group(1).strip() if match else None


def extract_woff2_url(block: str) -> str | None:
    match = re.search(r"src:\s*url\((https://[^)]+)\)\s*format\('woff2'\)", block)
    return match.group(1) if match else None


def slugify(name: str) -> str:
    return re.sub(r"[^a-z0-9]+", "-", name.lower()).strip("-")


def download(url: str, dest: Path) -> None:
    dest.parent.mkdir(parents=True, exist_ok=True)
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(req) as resp:
        dest.write_bytes(resp.read())


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--css-url", required=True, help="Google Fonts CSS2 URL")
    parser.add_argument("--out-dir", required=True, help="Base output directory")
    args = parser.parse_args()

    css = fetch_css(args.css_url)
    blocks = parse_blocks(css)

    if not blocks:
        print("No @font-face blocks found.", file=sys.stderr)
        sys.exit(1)

    out_dir = Path(args.out_dir)
    downloaded = 0

    for block in blocks:
        family = extract_value(block, "font-family")
        style = extract_value(block, "font-style") or "normal"
        weight = extract_value(block, "font-weight") or "400"
        url = extract_woff2_url(block)

        if not family or not url:
            continue

        family_clean = family.strip("'\"")
        folder = out_dir / slugify(family_clean)
        filename = f"{family_clean.replace(' ', '')}-{weight}-{style}.woff2"
        dest = folder / filename

        download(url, dest)
        downloaded += 1

    if downloaded == 0:
        print("No WOFF2 files downloaded. Use convert_to_woff2.py if only TTF/OTF is available.", file=sys.stderr)
        sys.exit(1)

    print(f"Downloaded {downloaded} files to {os.path.abspath(out_dir)}")


if __name__ == "__main__":
    main()
