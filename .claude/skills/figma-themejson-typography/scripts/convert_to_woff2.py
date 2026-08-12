#!/usr/bin/env python3
"""
Convert TTF/OTF files to WOFF2 using fonttools.

Example:
  python3 convert_to_woff2.py --in-dir /path/to/assets/fonts/montserrat --delete-source

If fonttools is missing, create a venv:
  python3 -m venv /tmp/woff2-venv
  source /tmp/woff2-venv/bin/activate
  python -m pip install fonttools brotli
"""

from __future__ import annotations

import argparse
import sys
from pathlib import Path


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--in-dir", required=True, help="Directory with TTF/OTF files")
    parser.add_argument("--delete-source", action="store_true", help="Delete source TTF/OTF files")
    args = parser.parse_args()

    try:
        from fontTools.ttLib import TTFont
    except Exception as exc:
        print(f"Missing dependency: {exc}", file=sys.stderr)
        sys.exit(1)

    in_dir = Path(args.in_dir)
    count = 0

    for ext in ("*.ttf", "*.otf"):
        for font_path in in_dir.glob(ext):
            out_path = font_path.with_suffix(".woff2")
            font = TTFont(font_path)
            font.flavor = "woff2"
            font.save(out_path)
            font.close()
            count += 1
            if args.delete_source:
                font_path.unlink(missing_ok=True)

    print(f"Converted {count} files")


if __name__ == "__main__":
    main()
