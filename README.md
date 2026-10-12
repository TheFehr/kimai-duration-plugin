# Overridable Duration Rounding for Kimai 2

> **Fork notice.** This is a modified fork of
> [glorpen/kimai-duration-plugin](https://github.com/glorpen/kimai-duration-plugin)
> by Arkadiusz Dzięgiel, licensed under GPL-3.0-or-later (see `LICENSE`).
> Differences from upstream:
>
> - Renamed bundle/namespace `GlorpenDurationBundle` → `TheFehrDurationBundle`
>   and meta fields `glorpen_duration` → `thefehr_duration`. Values saved under
>   the old field name are **not** migrated.
> - **Added begin and end rounding overrides** (`thefehr_begin`, `thefehr_end`),
>   not just duration.
> - Removed `Migrations/`, so the install command no longer has anything to do.
> - Kimai 2.x only (upstream's 1.x support was already dropped).

Enables setting the rounding options (same as the global ones) to override
rounding for any Customer, Project or Activity — for the **duration**, the
**beginning** and the **ending** of a time entry.

## Installation

Requires Kimai 2.x. Check this repository out as
`var/plugins/TheFehrDurationBundle` (the directory name must match) and
reload Kimai:

```
git clone https://github.com/TheFehr/kimai-duration-plugin.git var/plugins/TheFehrDurationBundle
bin/console kimai:reload --env=prod
```

`bin/console kimai:bundle:thefehr-duration:install` still exists but is a
no-op for this fork (no migrations, no assets), so it can be skipped.

## Usage

After installation, the existing rounding modes are extended with this
functionality. When editing any Customer, Project or Activity, three new
fields appear:

| Field | Meta name | Overrides |
|-------|-----------|-----------|
| Rounding of the duration in minutes | `thefehr_duration` | duration rounding |
| Rounding of the Beginning in minutes | `thefehr_begin` | begin rounding |
| Rounding of the Ending in minutes | `thefehr_end` | end rounding |

Leave a field empty to use the global setting; `0` deactivates rounding for
that scope. When several levels set a value, the most specific one wins:
**Activity → Project → Customer → global**.
