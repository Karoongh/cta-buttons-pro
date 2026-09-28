# CTA Buttons Pro — Shortcode API Contract (Stable / Immutable)

**Version of this contract:** 1.0  
**Applies to:** Plugin v2.1.3 and all future versions  
**Rule:** These shortcodes MUST remain valid after any number of updates. Breaking changes require a major version (3.0.0) and a migration path.

---

## 1. Frozen shortcodes (never remove, never rename)

| Shortcode | Meaning | Stable since |
|-----------|---------|--------------|
| `[cta_buttons_pro]` | Render all active buttons | 1.x |
| `[cta_phone]` | First phone button | 1.x |
| `[cta_whatsapp]` | First WhatsApp button | 1.x |
| `[cta_telegram]` | First Telegram button | 1.x |
| `[cta_email]` | First email button | 1.x |
| `[cta_instagram]` | First Instagram button | 1.x |
| `[cta_location]` | First location button | 1.x |
| `[cta_button]` | Generic (channel / id / index) | 1.x |
| `[cta_btn]` | Single button by `id` | 2.x |

## 2. Parameters (backward compatible)

| Param | Used by | Meaning |
|-------|---------|---------|
| `channel` | `cta_button` | `phone` \| `whatsapp` \| `telegram` \| `email` \| `instagram` \| `location` |
| `id` | `cta_btn`, `cta_button`, `cta_*` | Button ID from admin (e.g. `btn_a1b2c3d4`) |
| `index` | `cta_phone`, … | 1-based index among buttons of same type |
| `label` | all single | Override visible text |
| `size` | all single | Icon size in px (16–64) |

### Examples that must keep working forever

```
[cta_buttons_pro]
[cta_phone]
[cta_phone index="1"]
[cta_phone index="2"]
[cta_phone label="تماس فروش"]
[cta_whatsapp]
[cta_whatsapp index="2" label="پشتیبانی"]
[cta_btn id="btn_xxxxxxxx"]
[cta_button channel="phone" index="2" label="خط ۲"]
```

## 3. Data protection rules (mandatory for all PRs)

1. **Never** call `delete_option()` on `cta_pro_buttons`, `cta_pro_settings`, `cta_pro_clicks_*`, `cta_pro_clicks_detail_*`, or legacy `cta_pro_phone` etc. during activation, update, or migration.
2. `uninstall.php` must **not** delete stats or buttons by default (only on explicit full uninstall policy, and never during update).
3. Saving settings from a non-buttons tab must **not** rewrite `cta_pro_buttons`.
4. Migration may only **add** missing data; never wipe existing arrays.
5. Shortcode names in section 1 are frozen. New shortcodes may be added; old ones must keep the same behavior.

## 4. Display rules

- Button shows **icon + text** (label).
- By default, phone/whatsapp/telegram/email also show the **number/value** next to the text (unless "نمایش شماره" is turned off).
- Floating menu may show icon-only (space constraints); main shortcodes and fixed bar always show text.

## 5. Changelog duty

Every release must list whether shortcode behavior changed.  
If behavior of a frozen shortcode changes, it is a **breaking change** and needs major version bump.

---

Maintainers: treat this file as part of the public API of the plugin.
