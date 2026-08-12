#!/usr/bin/env python3
"""
Download variable font files from Google Fonts.

Usage:
  python3 scripts/download_google_fonts.py --family "Nunito Sans" --out assets/fonts

Notes:
- Fetches the first normal and italic WOFF2 URLs for the family.
- Saves as <FamilySlug>-VariableFont_wght.woff2 and <FamilySlug>-Italic-VariableFont_wght.woff2.
- If italic isn't available, only the normal file is saved.
"""

import argparse
import re
import sys
import urllib.parse
import urllib.request
from pathlib import Path

UA = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36"


def family_slug(name: str) -> str:
    return re.sub(r"[^a-z0-9]+", "-", name.lower()).strip("-")


def fetch_css(family: str) -> str:
    family_query = urllib.parse.quote_plus(family)
    url = f"https://fonts.googleapis.com/css2?family={family_query}:ital,wght@0,100..1000;1,100..1000&display=swap"
    req = urllib.request.Request(url, headers={"User-Agent": UA})
    return urllib.request.urlopen(req).read().decode("utf-8")


def extract_woff2(css: str, style: str) -> str:
    blocks = css.split("@font-face")
    for block in blocks[1:]:
        if f"font-style: {style}" not in block:
            continue
        match = re.search(r"url\((https://[^)]+\.woff2)\)", block)
        if match:
            return match.group(1)
    return ""


def download(url: str, dest: Path) -> None:
    req = urllib.request.Request(url, headers={"User-Agent": UA})
    data = urllib.request.urlopen(req).read()
    dest.write_bytes(data)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--family", required=True, help="Font family name (Google Fonts)")
    parser.add_argument("--out", required=True, help="Output base folder (e.g., assets/fonts)")
    args = parser.parse_args()

    css = fetch_css(args.family)
    normal_url = extract_woff2(css, "normal")
    italic_url = extract_woff2(css, "italic")

    if not normal_url:
        print("No WOFF2 URL found for normal style.", file=sys.stderr)
        return 1

    out_dir = Path(args.out) / family_slug(args.family)
    out_dir.mkdir(parents=True, exist_ok=True)

    base_name = re.sub(r"\s+", "", args.family)
    normal_path = out_dir / f"{base_name}-VariableFont_wght.woff2"
    download(normal_url, normal_path)
    print(f"Saved {normal_path}")

    if italic_url:
        italic_path = out_dir / f"{base_name}-Italic-VariableFont_wght.woff2"
        download(italic_url, italic_path)
        print(f"Saved {italic_path}")
    else:
        print("No italic WOFF2 URL found.")

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
