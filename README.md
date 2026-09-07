# ProxySearch

Live at [proxysearch.org](https://proxysearch.org).

A privacy-respecting, JavaScript-free meta search engine that fetches results
from major search providers without tracking you.

This is a fork of [Ahwxorg/LibreY](https://github.com/Ahwxorg/LibreY). All
credit for the underlying engine goes to that project and its contributors —
but this fork has diverged from it in a few deliberate ways worth calling out:

- **Text search comes from five major US-based providers only**: Google,
  DuckDuckGo, Brave, Bing, and Yahoo. Upstream also includes Yandex, Ecosia,
  and Mojeek; those were dropped here since this fork is geared toward US
  visitors and US-relevant results specifically, not international coverage.
- **Image search now comes from Bing**, not Qwant. Qwant's API started
  returning a DataDome anti-bot challenge instead of results — not fixable
  from this end — so it was replaced outright with a purpose-built Bing
  Images scraper rather than just dropping the feature.
- **Video search has been removed entirely.** It ran on Invidious (a YouTube
  frontend), which is no longer a viable way to search video through a proxy
  as of this writing — Google's PoToken requirement specifically breaks
  Invidious when run from datacenter IPs, which describes essentially every
  public Invidious instance, including the one this fork used. If a workable
  alternative for proxied video search turns up, it may be added back; none
  exists right now.
- **No fallback to other LibreY/LibreX instances.** Upstream will retry a
  failed search on another public instance if this one's own scrapers come
  up empty. That sends the visitor's full raw query to a third-party server
  outside this fork's control, which doesn't fit a privacy-focused tool, so
  it's been disabled outright — a failed search just fails here.
- **SafeSearch is mandatory, not a visitor setting.** Every text and image
  engine always searches with strict content filtering enabled; there's no
  way to turn it off. This protects the site and the IP it runs on from
  being associated with the kind of content that setting exists to filter,
  full stop. (While correcting this, several engines turned out to be using
  parameter values that don't actually correspond to real SafeSearch
  settings for that engine at all — so on upstream, and on this fork prior
  to this fix, SafeSearch may not have been doing anything on some engines
  regardless of visitor preference.)

## Maintenance

Originally built in 2022, then untouched for a couple years. Revived and now
maintained with [Claude Code](https://claude.com/claude-code) doing the actual
upstream syncs, bug fixes, and deploys, checked in on weekly.

## License

AGPL-3.0, same as upstream — see [LICENSE](LICENSE).
