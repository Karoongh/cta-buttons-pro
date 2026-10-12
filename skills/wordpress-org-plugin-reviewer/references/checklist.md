# WordPress.org Plugin Directory — Pass/Fail Checklist

Use this matrix on every org-ready pass. Update against the live handbook if guidelines change.

## A. Security (any FAIL = not ready)

| ID | Check | PASS criteria |
|----|--------|----------------|
| S1 | ABSPATH guard | All PHP files bail if `ABSPATH` undefined |
| S2 | Nonces | All state-changing forms and admin-post handlers verify nonces |
| S3 | Capabilities | Privileged actions require appropriate `current_user_can` |
| S4 | Sanitize input | `$_GET`/`$_POST`/`$_REQUEST` sanitized with appropriate APIs |
| S5 | Escape output | Attributes, HTML, URLs escaped on output |
| S6 | AJAX | `check_ajax_referer` + auth/cap as required; nopriv only when intentional |
| S7 | SQL | No raw SQL without `$wpdb->prepare` |
| S8 | No dangerous functions | No eval, obfuscation, shell_exec, remote include of code |
| S9 | Files | Uploads use WP APIs; no arbitrary file write/read from user paths |
| S10 | Enqueue | Scripts/styles via `wp_enqueue_*`; dependencies declared |

## B. Guidelines (any FAIL = not ready)

| ID | Check | PASS criteria |
|----|--------|----------------|
| G1 | GPL | GPLv2 or later (or compatible); declared in header and readme |
| G2 | No trialware | No paid unlock, license gate, or timed disable of shipped features |
| G3 | No forced credits | No public powered-by without opt-in |
| G4 | Tracking | No tracking without consent; prefer no external telemetry |
| G5 | Trademarks | Name/slug does not abuse third-party trademarks |
| G6 | Complete plugin | Submission is usable, not a placeholder |
| G7 | Human-readable | Source is readable PHP/JS/CSS |
| G8 | Illegal/offensive | No illegal or clearly harmful purpose |

## C. Headers and packaging

| ID | Check | PASS criteria |
|----|--------|----------------|
| H1 | Plugin header | Name, Description, Version, Author, License, Text Domain present |
| H2 | Requires | Requires at least, Requires PHP, Tested up to realistic and current |
| H3 | Text domain | Matches slug; Domain Path if used |
| H4 | Zip structure | Single root folder = plugin slug; main file inside |
| H5 | No junk | No .git, node_modules, tests-only clutter in distribution zip |
| H6 | Prefix | Functions/options/hooks uniquely prefixed |

## D. Internationalization and global UX

| ID | Check | PASS criteria |
|----|--------|----------------|
| I1 | English default | All source strings English |
| I2 | i18n wrappers | Visible strings use __ / _e / esc_*__ / _n with text domain |
| I3 | load_plugin_textdomain | Loaded correctly |
| I4 | RTL | Front/admin UI usable in RTL (Persian/Arabic) |
| I5 | No locale hardcode | Not Iran-only or FA-only in directory build |

## E. readme.txt and listing

| ID | Check | PASS criteria |
|----|--------|----------------|
| R1 | Valid header | Stable tag, license URI, contributors, tags |
| R2 | Tags | English, max 5, relevant |
| R3 | Sections | Description, Installation, FAQ, Changelog minimum |
| R4 | Honesty | No empty hype-only description |
| R5 | Privacy note | Personal data / IP storage disclosed if applicable |
| R6 | Screenshots | Optional but recommended; documented if present |

## F. Data lifecycle

| ID | Check | PASS criteria |
|----|--------|----------------|
| D1 | Update-safe | Activation/update does not wipe user options or stats |
| D2 | Uninstall | Documented; no surprise mass delete on update |
| D3 | Stats reset | User can clear stats from UI if stats exist |

## Verdict rules

- Any FAIL in A or B → NOT READY
- Only fails in C/D/E/F → NOT READY until fixed (still blockers)
- All PASS → READY TO SUBMIT
- All critical PASS with minor docs debt explicitly listed → READY WITH WARNINGS only if Review Team would not auto-reject
