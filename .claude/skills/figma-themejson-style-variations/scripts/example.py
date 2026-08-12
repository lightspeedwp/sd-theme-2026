#!/usr/bin/env python3
"""
Example entrypoint: delegates to download_google_fonts.py.

Usage:
  python3 scripts/example.py --family "Nunito Sans" --out assets/fonts
"""

import subprocess
import sys
from pathlib import Path


def main() -> int:
    script = Path(__file__).with_name("download_google_fonts.py")
    cmd = [sys.executable, str(script), *sys.argv[1:]]
    return subprocess.call(cmd)


if __name__ == "__main__":
    raise SystemExit(main())
