# referer-parser PHP library

This is the implementation of [php-referer-parser], the library for extracting search marketing data from referer _(sic)_ URLs or from UserAgents.

The implementation uses the shared 'database' of known referers found in [`referers.yml`][referers-yml].

The PHP version of referer-parser is maintained by [Lars Strojny][lstrojny]. 

JBX version extend the php-referer-parser for parse UserAgent .

## Usage

```php
use JBX\RefererParser\SnowplowParser;

$parser = new SnowplowParser();
$referrer = $parser->parse(
    'https://article.onnit.com/productivity/?utm_medium=jumbleberry&utm_campaign=affiliate&utm_content=alpha-brain-offer-jmbb&utm_source=384596&jmb_cid=NTG9B-GlRjw5rV28B9OUj5jIJZ0RDe1kq-LTuX1Wdx2Dsj22epgfKB5pW7XhPa16%2FFB%2FV%2FAB-ST',
    'http://m.facebook.com',
    'Mozilla/5.0 (iPhone; CPU iPhone OS 13_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBDV/iPhone11,8;FBMD/iPhone;FBSN/iOS;FBSV/13.5.1;FBSS/2;FBID/phone;FBLC/en_US;FBOP/5]'
);
echo $referrer->getSource(); // "Facebook"
```