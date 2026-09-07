# ProxySearch

Live at [proxysearch.org](https://proxysearch.org).

A privacy-respecting, JavaScript-free meta search engine that fetches results
from major US search providers without tracking you.

This started as a fork of [Ahwxorg/LibreY](https://github.com/Ahwxorg/LibreY),
and still carries its look, feel, and general structure — full credit to that
project and its contributors for the foundation. But the backend has since
been thoroughly rewritten: broken and poorly-performing search engines were
cut, working ones were fixed (some for the first time), and the whole
approach to what this proxy actually searches was rethought from scratch.
Enough has changed that this is arguably no longer just a LibreY fork running
someone else's search logic — it's its own proxy search built on that
foundation, kept close to upstream's structure mainly so it stays easy to
maintain going forward.

## What's different from upstream, and why

- **Text search only comes from five major US-based providers**: Google,
  DuckDuckGo, Brave, Bing, and Yahoo. Upstream also scrapes Yandex, Ecosia,
  and Mojeek; those were dropped since this fork is geared toward US
  visitors and US-relevant results, not international coverage.

- **Image and video search have both been removed entirely** — and for the
  same underlying reason: every major provider now defends those two result
  types far more aggressively than plain text search. Text search is still
  wide open across all five engines above; images and video are not, at
  least not from a server like this one:
  - *Video* ran on Invidious (a YouTube frontend). Google's PoToken
    requirement now specifically breaks Invidious when it runs from a
    datacenter IP — which describes essentially every public Invidious
    instance, including the one this fork used. Confirmed dead by direct
    testing, not a local bug.
  - *Images* went through several: Qwant started returning a DataDome
    bot-check instead of results; DuckDuckGo's image API returned a flat
    403; Yahoo's image search errored out entirely; Bing was the most
    interesting failure — it returned fast, complete, legitimate-looking
    200 responses, but with content **unrelated to the query**. That's
    Bing quietly serving decoy results to a request pattern it's flagged
    as automated, rather than blocking it outright. Worse than an honest
    block, since it looks like it's working.
  
  If image or video search ever becomes reliably scrapeable from a server
  like this again, it may come back. For now, this fork does one thing —
  text search — and does it honestly rather than pretending to do more.

- **No fallback to other LibreY/LibreX instances.** Upstream retries a
  failed search on another public instance when its own scrapers come up
  empty. That sends a visitor's full raw query to a third-party server
  outside this fork's control, which doesn't fit a privacy-focused tool —
  disabled outright. A failed search here just fails, honestly and fast.

- **SafeSearch is mandatory, not a visitor setting.** Every search always
  runs with strict content filtering; there's no way to turn it off. This
  is for the protection of the site and the IP it runs on — no exceptions.
  While fixing this, it turned out several engines were using SafeSearch
  parameter values that don't correspond to real settings for that engine
  at all, meaning SafeSearch may not have done anything on some engines
  before now, on this fork or upstream.

## Maintenance

Originally built in 2022, then untouched for a couple years. Revived and now
maintained with [Claude Code](https://claude.com/claude-code) doing the actual
upstream syncs, bug fixes, and deploys, checked in on weekly.

## License

AGPL-3.0, same as upstream — see [LICENSE](LICENSE).
