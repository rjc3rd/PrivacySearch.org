# ProxySearch

Live at [proxysearch.org](https://proxysearch.org).

A privacy-respecting, JavaScript-free meta search engine that fetches results
from major US search providers without tracking you — built to actually work,
every time you click search, not just sometimes.

This started as a fork of [Ahwxorg/LibreY](https://github.com/Ahwxorg/LibreY),
and still carries its look, feel, and general structure — full credit to that
project and its contributors for the foundation. But the backend has since
been thoroughly rewritten, and the backend is where the real work went. The
visual layer barely changed; every search engine this site talks to was
individually tested against production, kept only if it demonstrably still
works, and one (Yahoo) was added that was never part of upstream LibreY at
all. Enough has changed that this is arguably no longer just a LibreY fork
running someone else's search logic — it's its own proxy search built on
that foundation, kept close to upstream's structure mainly so it stays easy
to maintain going forward.

## Reliability first — that's the actual point

A meta search proxy is only as good as its scrapers. If a chunk of them are
quietly dead, "search" turns into "search, get nothing, search again, maybe
get something" — which is exactly the experience that led to this site being
abandoned and unvisited for a couple of years after it was first built.
Every engine in the current rotation was verified with the same process:

1. Fetch a real results page for a real query, directly from the production
   server (not a clean dev machine elsewhere — the IP a scraper runs from
   matters enormously to how search engines treat it).
2. Run it through this codebase's *actual* parser, not a guess at what the
   markup looks like — a page can return HTTP 200 and still contain nothing
   usable, or worse, contain results for something else entirely.
3. Only keep it if that produces real, relevant, correctly-parsed results,
   repeatably.

That process is why **Google and Brave were removed** — not a style
preference, a finding. Brave returns an AWS WAF CAPTCHA challenge on every
request from this server. Google redirects to a JavaScript-required
verification gate instead of serving results. Neither is a markup bug;
both are active, deliberate blocks that no amount of scraper tweaking fixes.
Left in, they'd silently fail on every "auto" pick that landed on them —
exactly the kind of randomly-broken experience this rewrite exists to
eliminate.

**Google, DuckDuckGo, Brave, Bing, and Yahoo** were considered — the
legitimate US search "big five." Two didn't survive contact with reality.
Startpage and Ask.com were also evaluated as possible additions: Startpage
is blocked by its own proof-of-work anti-bot challenge, and Ask.com has
shut down as a search engine entirely (its homepage is now literally a
retirement notice). Padding the list back out to five with engines that
don't actually work would defeat the entire point.

What's left — **DuckDuckGo, Bing, and Yahoo** — is smaller than upstream's
default set, and every one of them is confirmed working, right now, from
this server. That trade was made deliberately: three engines that always
return something beats five where two are dead weight.

**The difference this makes is not subtle.** The previous version of this
site — stock LibreY, dead engines and all — routinely took close to ten
seconds per search and still frequently came back with "no results found,"
because a chosen engine would time out, then the cross-instance fallback
(since removed) would take its own several seconds trying another server,
and often *that* would fail too. With only the three engines actually
confirmed reliable now, and that fallback gone, searches typically resolve
in under a second, and every search run during this testing has returned
a real result on the first try — not "eventually, after retrying."
That gap is the whole reason this rewrite happened.

## Other changes from upstream

- **Image and video search have been removed entirely**, for the same
  underlying reason as Google/Brave: every major provider defends those
  result types far more aggressively than plain text search, and every
  backend tried for them failed in production testing:
  - *Video* ran on Invidious (a YouTube frontend). Google's PoToken
    requirement now specifically breaks Invidious from datacenter IPs —
    which is what essentially every public Invidious instance runs on,
    including the one this fork used.
  - *Images* went through Qwant (DataDome bot-check), DuckDuckGo's image
    API (flat 403), Yahoo's image search (server errors), and Bing —
    the most interesting failure, since it returned fast, legitimate-
    looking 200 responses with content **unrelated to the query**,
    rather than an honest block.

  If either becomes reliably scrapeable from a server like this again, it
  may come back. For now this fork does one thing — text search — and
  does it honestly rather than pretending to do more.

- **No fallback to other LibreY/LibreX instances.** Upstream retries a
  failed search on another public instance when its own scrapers come up
  empty. That sends a visitor's full raw query to a third-party server
  outside this fork's control — disabled outright. A failed search here
  just fails, honestly and fast (and per the above, should be rare).

- **SafeSearch is mandatory, not a visitor setting**, for the protection of
  the site and the IP it runs on — no exceptions, no toggle. While fixing
  this, several engines turned out to be using SafeSearch parameter values
  that don't correspond to real settings for that engine at all, meaning
  SafeSearch may not have done anything on some engines before now, on
  this fork or upstream.

## Maintenance

Originally built in 2022, then untouched for a couple years. Revived and now
maintained with [Claude Code](https://claude.com/claude-code) doing the
actual upstream syncs, bug fixes, and deploys, checked in on weekly.

## License

AGPL-3.0, same as upstream — see [LICENSE](LICENSE).
