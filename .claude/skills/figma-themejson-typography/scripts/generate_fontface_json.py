#!/usr/bin/env python3
"""
Generate a fontFace JSON array from WOFF2 filenames.

Expected filename pattern:
  Family-Weight-Style.woff2
  Example: Montserrat-400-normal.woff2

Example:
  python3 generate_fontface_json.py --family "Montserrat" --dir /path/to/assets/fonts/montserrat
"""

from __future__ import annotations

import argparse
import json
import re
from pathlib import Path

FILENAME_RE = re.compile(r"^(?P<family>.+)-(?P<weight>\d+(?:-\d+)?)\-(?P<style>normal|italic)\.woff2$", re.IGNORECASE)


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--family", required=True, help="Font family name")
    parser.add_argument("--dir", required=True, help="Directory containing WOFF2 files")
    parser.add_argument("--prefix", default="file:./assets/fonts", help="Prefix path for src")
    args = parser.parse_args()

    font_dir = Path(args.dir)
    entries = []

    for font_file in sorted(font_dir.glob("*.woff2")):
        match = FILENAME_RE.match(font_file.name)
        if not match:
            continue
        weight = match.group("weight")
        style = match.group("style").lower()
        rel_path = f"{args.prefix}/{font_dir.name}/{font_file.name}"
        entries.append(
            {
                "fontFamily": args.family,
                "fontStyle": style,
                "fontWeight": weight,
                "src": [rel_path],
            }
        )

    print(json.dumps(entries, indent=2))


if __name__ == "__main__":
    main()
