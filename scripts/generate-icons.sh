#!/usr/bin/env bash
set -euo pipefail

INPUT_FILE="${1:-favicon-source.png}"
OUTPUT_DIR="${2:-dist/root-icons}"
LOGO_SCALE_PERCENT="${LOGO_SCALE_PERCENT:-84}"

if [[ ! -f "$INPUT_FILE" ]]; then
  echo "Input file not found: $INPUT_FILE" >&2
  exit 1
fi

mkdir -p "$OUTPUT_DIR"

sizes=(57 60 72 76 114 120 144 152 167 180)
input_ext="${INPUT_FILE##*.}"
input_ext="$(printf '%s' "$input_ext" | tr '[:upper:]' '[:lower:]')"

generate_svg_icon() {
  local size="$1"
  local output_file="$2"
  local inner offset

  if ! command -v rsvg-convert >/dev/null 2>&1; then
    echo "Missing dependency: rsvg-convert" >&2
    exit 1
  fi

  inner=$((size * LOGO_SCALE_PERCENT / 100))
  offset=$(((size - inner) / 2))

  rsvg-convert "$INPUT_FILE" \
    --format png \
    --keep-aspect-ratio \
    --background-color "#000000" \
    --width "$inner" \
    --height "$inner" \
    --page-width "$size" \
    --page-height "$size" \
    --left "$offset" \
    --top "$offset" \
    --output "$output_file"
}

generate_raster_icon() {
  local size="$1"
  local output_file="$2"

  if ! command -v sips >/dev/null 2>&1; then
    echo "Missing dependency: sips" >&2
    exit 1
  fi

  sips -z "$size" "$size" "$INPUT_FILE" --out "$output_file" >/dev/null
}

for size in "${sizes[@]}"; do
  base_file="$OUTPUT_DIR/apple-touch-icon-${size}x${size}.png"
  pre_file="$OUTPUT_DIR/apple-touch-icon-${size}x${size}-precomposed.png"

  if [[ "$input_ext" == "svg" ]]; then
    generate_svg_icon "$size" "$base_file"
  else
    generate_raster_icon "$size" "$base_file"
  fi

  cp "$base_file" "$pre_file"
done

cp "$OUTPUT_DIR/apple-touch-icon-180x180.png" "$OUTPUT_DIR/apple-touch-icon.png"
cp "$OUTPUT_DIR/apple-touch-icon-180x180.png" "$OUTPUT_DIR/apple-touch-icon-precomposed.png"
