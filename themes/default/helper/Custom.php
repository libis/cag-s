<?php

namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;

class Custom extends AbstractHelper
{
    // ---------- Normalizer (Option 2) ----------
    public function normalize_ocr_text($text)
    {
        // lower
        $text = mb_strtolower($text, 'UTF-8');

        // FIX 1: join hyphenated line-break splits (cre-\natief → creatief)
        $text = preg_replace('/(\p{L}+)-\s*\n\s*(\p{L}+)/u', '$1$2', $text);

        // FIX 2: join soft hyphens inside line (cre- atief → creatief)
        $text = preg_replace('/(\p{L}+)-\s+(\p{L}+)/u', '$1$2', $text);

        // convert all dash types to spaces (for flexible matching)
        $text = preg_replace('/[-‐-‒–—―]/u', ' ', $text);

        // remove punctuation that breaks word boundaries
        $text = preg_replace('/[.,;:!?(){}\[\]<>\/\\\\"“”\']+/u', ' ', $text);

        // replace tabs & newlines with spaces
        $text = str_replace(["\t", "\r", "\n"], ' ', $text);

        // strip HTML
        $text = strip_tags($text);

        // collapse whitespace
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim($text);
    }

    // ---------- Make the two flexible variants (space/hyphen) ----------
    public function flexible_search_variants($search)
    {
        $s = mb_strtolower(trim((string)$search), 'UTF-8');

        // normalize dashes to hyphen for canonical form
        $s_dash = preg_replace('/[-‐-‒–—―\s]+/u', '-', $s); // all spaces/dashes -> single hyphen
        $s_space = preg_replace('/[-‐-‒–—―\s]+/u', ' ', $s); // all spaces/dashes -> single space

        // prefer longer first to avoid partial matches (e.g. multiword vs single word)
        $variants = array_unique([$s_dash, $s_space]);

        // sort by length desc so longer variants are tested first
        usort($variants, function ($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });

        return $variants;
    }

   
    // ---------- Core snippet builder (fixed) ----------
    public function make_flexible_snippet($raw_text, $search, $radius = 30)
    {
        if (!strlen(trim($search))) return null;

        // normalize OCR text (we will extract snippet from this normalized text)
        $text = $this->normalize_ocr_text($raw_text);

        if ($text === '') return null;

        // variants: "tomaat garnaal" and "tomaat-garnaal"
        $variants = $this->flexible_search_variants($search);

        // build an alternation group of escaped variants for regex
        $escaped = array_map(function ($v) {
            return preg_quote($v, '/');
        }, $variants);
        $group = implode('|', $escaped);

        // word-boundary like anchors that work with unicode letters & numbers
        $pattern = '/(' . $group . ')/iu';

        // find first match and get offset
        if (!preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        // $m[0][0] is matched text, $m[0][1] is byte offset (works with PREG_OFFSET_CAPTURE)
        $match_text = $m[0][0];
        $byte_offset = $m[0][1];

        // convert byte offset to character offset for mb_substr
        // mb_substr accepts character index, but mb_strpos can be used to find char offset:
        // simpler: use mb_substr with mb_strlen on left part
        $left = mb_substr($text, 0, mb_strlen(mb_substr($text, 0, mb_stripos($text, $match_text, 0, 'UTF-8'), 'UTF-8'), 'UTF-8'), 'UTF-8');
        // however above is convoluted. We'll find character offset properly:
        $char_offset = mb_strpos($text, $match_text, 0, 'UTF-8');
        if ($char_offset === false) {
            // fallback: compute from byte offset
            $char_offset = mb_strlen(substr($text, 0, $byte_offset), '8bit');
        }

        // snippet bounds
        $start_char = max(0, $char_offset - $radius);
        $end_char   = min(mb_strlen($text, 'UTF-8'), $char_offset + mb_strlen($match_text, 'UTF-8') + $radius);
        list($start_char, $end_char) = $this->expand_to_full_words($text, $start_char, $end_char);

        // Extract final snippet
        $snippet = mb_substr($text, $start_char, $end_char - $start_char, 'UTF-8');

        // highlight the matched part(s) in the snippet (highlight all variants to be safe)
        foreach ($variants as $v) {
            if (mb_strlen($v) < 1) continue;
            $snippet = preg_replace(
                '/' . preg_quote($v, '/') . '/iu',
                '<span class="hit">$0</span>',
                $snippet
            );
        }

        return '…' . trim($snippet) . '…';
    }

    public function expand_to_full_words($text, $start, $end)
    {

        $len = mb_strlen($text, 'UTF-8');

        // correct bounds
        if ($start < 0) $start = 0;
        if ($end > $len) $end = $len;

        // step left until hitting whitespace or start
        while ($start > 0 && !preg_match('/\s/u', mb_substr($text, $start, 1, 'UTF-8'))) {
            $start--;
        }

        // step right until hitting whitespace or end
        while ($end < $len && !preg_match('/\s/u', mb_substr($text, $end, 1, 'UTF-8'))) {
            $end++;
        }

        return [$start, $end];
    }
}
