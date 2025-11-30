<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Debug helper ala Laravel untuk CI3
 * Fitur:
 * - dump(...$vars), dd(...$vars)
 * - d($var, $label) singkat
 * - dump_json($var, $label)
 * - trace() untuk backtrace ringkas
 * - console_log($var) ke browser console
 * - Depth limit & proteksi circular reference
 * - Auto-disable di production (optional)
 */

if (!function_exists('_dbg_disabled')) {
    function _dbg_disabled()
    {
        // Matikan di production jika mau
        return defined('ENVIRONMENT') && ENVIRONMENT === 'production';
    }
}

if (!function_exists('_dbg_style')) {
    function _dbg_style()
    {
        static $printed = false;
        if ($printed) return;
        $printed = true;

        echo <<<CSS
<style>
.dbg-wrap{font-family:ui-monospace,Consolas,monospace;font-size:13px;line-height:1.4;color:#111;}
.dbg-box{margin:10px 0;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.06);}
.dbg-head{background:#0f172a;color:#f8fafc;padding:8px 12px;display:flex;justify-content:space-between;align-items:center}
.dbg-head .lbl{font-weight:600}
.dbg-body{background:#f8fafc;padding:10px 12px;overflow:auto}
.dbg-kv{margin:0}
.dbg-type{color:#0ea5e9;margin-left:8px;font-weight:600}
.dbg-meta{opacity:.8;font-size:12px}
.dbg-pre{white-space:pre; margin:0}
.dbg-code{background:#0b1020;color:#e3e7ef;border-radius:6px;padding:10px;overflow:auto}
.dbg-small{font-size:12px;color:#475569}
.dbg-tag{display:inline-block;background:#e2e8f0;color:#0f172a;border-radius:999px;padding:0 8px;margin-right:6px}
.dbg-btn{background:#e2e8f0;border:0;border-radius:6px;padding:3px 8px;cursor:pointer}
details.dbg-det>summary{cursor:pointer;list-style:none}
details.dbg-det>summary::-webkit-details-marker{display:none}
details.dbg-det>summary{padding:2px 6px;background:#eef2ff;border-radius:4px;display:inline-block;margin:4px 0;color:#1e293b}
</style>
CSS;
    }
}
if (!function_exists('_dbg_html')) {
    function _dbg_html($str)
    {
        return htmlspecialchars((string)$str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('_dbg_dump_any')) {
    function _dbg_dump_any($var, $depth = 0, $maxDepth = 4, &$seen = [])
    {
        if ($depth > $maxDepth) {
            return '<span class="dbg-small">…depth limit…</span>';
        }

        // Detect circular refs
        if (is_object($var)) {
            $oid = spl_object_hash($var);
            if (isset($seen[$oid])) return '<span class="dbg-small">*circular*</span>';
            $seen[$oid] = true;
        }

        switch (gettype($var)) {
            case 'NULL':
                return '<span class="dbg-tag">null</span>';
            case 'boolean':
                return '<span class="dbg-tag">bool</span> ' . ($var ? 'true' : 'false');
            case 'integer':
                return '<span class="dbg-tag">int</span> ' . _dbg_html($var);
            case 'double':
                return '<span class="dbg-tag">float</span> ' . _dbg_html($var);
            case 'string':
                $s = _dbg_html($var);
                return '<span class="dbg-tag">string(' . strlen($var) . ')</span> "<span>' . $s . '</span>"';
            case 'array':
                $out = [];
                $out[] = '<span class="dbg-tag">array(' . count($var) . ')</span>';
                if (empty($var)) return implode(' ', $out);
                $rows = '';
                foreach ($var as $k => $v) {
                    $rows .= '<div><span class="dbg-small">[' . _dbg_html($k) . ']</span> ⇒ ' . _dbg_dump_any($v, $depth + 1, $maxDepth, $seen) . '</div>';
                }
                return implode(' ', $out) . '<div class="dbg-pre">' . $rows . '</div>';
            case 'object':
                $cls = get_class($var);
                $props = @get_object_vars($var);
                $out = [];
                $out[] = '<span class="dbg-tag">object</span> <span class="dbg-type">' . $cls . '</span>';
                if (!$props) return implode(' ', $out);
                $rows = '';
                foreach ($props as $k => $v) {
                    $rows .= '<div><span class="dbg-small">' . $cls . '->' . _dbg_html($k) . '</span> ⇒ ' . _dbg_dump_any($v, $depth + 1, $maxDepth, $seen) . '</div>';
                }
                return implode(' ', $out) . '<div class="dbg-pre">' . $rows . '</div>';
            case 'resource':
            case 'resource (closed)':
                return '<span class="dbg-tag">resource</span>';
            default:
                return '<span class="dbg-tag">' . _dbg_html(gettype($var)) . '</span>';
        }
    }
}

if (!function_exists('_dbg_wrap')) {
    function _dbg_wrap($html, $label = null)
    {
        _dbg_style();
        $label = $label ? _dbg_html($label) : 'dump';
        $time  = date('H:i:s');
        echo '<div class="dbg-wrap"><div class="dbg-box">';
        echo '<div class="dbg-head"><div class="lbl">' . $label . '</div><div class="dbg-meta">' . $time . '</div></div>';
        echo '<div class="dbg-body">' . $html . '</div>';
        echo '</div></div>';
    }
}

if (!function_exists('dump')) {
    function dump(...$vars)
    {
        if (_dbg_disabled()) return;
        foreach ($vars as $i => $v) {
            $seen = [];
            $body = _dbg_dump_any($v, 0, 6, $seen);
            _dbg_wrap($body, 'dump #' . ($i + 1));
        }
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        if (_dbg_disabled()) return;
        dump(...$vars);
        exit; // die & dump
    }
}

if (!function_exists('d')) {
    function d($var, $label = 'dump')
    {
        if (_dbg_disabled()) return;
        $seen = [];
        _dbg_wrap(_dbg_dump_any($var, 0, 6, $seen), $label);
    }
}

if (!function_exists('dump_json')) {
    function dump_json($var, $label = 'json')
    {
        if (_dbg_disabled()) return;
        $json = json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        _dbg_style();
        _dbg_wrap('<pre class="dbg-code">' . _dbg_html($json) . '</pre>', $label);
    }
}

if (!function_exists('trace')) {
    function trace($label = 'trace')
    {
        if (_dbg_disabled()) return;
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
        $lines = [];
        foreach ($bt as $i => $f) {
            $file = isset($f['file']) ? $f['file'] : '[internal]';
            $line = isset($f['line']) ? $f['line'] : '?';
            $func = isset($f['function']) ? $f['function'] : '';
            $cls  = isset($f['class']) ? $f['class'] . '::' : '';
            $lines[] = sprintf('#%02d %s(%s): %s%s()', $i, $file, $line, $cls, $func);
        }
        _dbg_wrap('<pre class="dbg-pre">' . _dbg_html(implode("\n", $lines)) . '</pre>', $label);
    }
}

if (!function_exists('console_log')) {
    function console_log($var, $label = 'log')
    {
        if (_dbg_disabled()) return;
        $json = json_encode($var, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo "<script>console.log('[{$label}]', {$json});</script>";
    }
}
