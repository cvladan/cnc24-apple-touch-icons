#!/usr/bin/env bash
set -euo pipefail

if ! command -v rsvg-convert >/dev/null 2>&1; then
  echo "Missing dependency: rsvg-convert" >&2
  exit 1
fi

INPUT_SVG="${1:-cnc24-logo.svg}"
OUTPUT_DIR="${2:-dist/root-icons}"
LOGO_SCALE_PERCENT="${LOGO_SCALE_PERCENT:-84}"

if [[ ! -f "$INPUT_SVG" ]]; then
  echo "Input SVG not found: $INPUT_SVG" >&2
  exit 1
fi

mkdir -p "$OUTPUT_DIR"

sizes=(57 60 72 76 114 120 144 152 167 180)

for size in "${sizes[@]}"; do
  inner=$((size * LOGO_SCALE_PERCENT / 100))
  offset=$(((size - inner) / 2))
  base_file="$OUTPUT_DIR/apple-touch-icon-${size}x${size}.png"
  pre_file="$OUTPUT_DIR/apple-touch-icon-${size}x${size}-precomposed.png"

  rsvg-convert "$INPUT_SVG" \
    --format png \
    --keep-aspect-ratio \
    --background-color "#000000" \
    --width "$inner" \
    --height "$inner" \
    --page-width "$size" \
    --page-height "$size" \
    --left "$offset" \
    --top "$offset" \
    --output "$base_file"

  cp "$base_file" "$pre_file"
done

cp "$OUTPUT_DIR/apple-touch-icon-180x180.png" "$OUTPUT_DIR/apple-touch-icon.png"
cp "$OUTPUT_DIR/apple-touch-icon-180x180.png" "$OUTPUT_DIR/apple-touch-icon-precomposed.png"
