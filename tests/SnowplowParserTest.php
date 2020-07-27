<?php

use JBX\RefererParser\SnowplowParser;
use PHPUnit\Framework\TestCase;

class SnowplowParserTest extends TestCase
{
    /**
     * @var SnowplowParser
     */
    private $parser;

    public function setUp(): void
    {
        $this->parser = new SnowplowParser();
    }

    public function testGetLeadSource_fromPageReferrer_Facebook()
    {
        $source = [
            'page_url'      => 'https://buy.hanacure.com/?utm_source=jb&utm_medium=384826&utm_campaign=jbbuypresell&click_id=TvP5Pm87uIif6bLeiNG5CDPSpfxSqULS0xKoN1028ayowkrDsuy8qQbWn_W76XV3%2F%7BFBFem25%7D%2F%7Bvid1%7D%2F%7BJac%7D',
            'page_referrer' => 'http://m.facebook.com',
            'useragent'     => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBDV/iPhone11,8;FBMD/iPhone;FBSN/iOS;FBSV/13.3.1;FBSS/2;FBID/phone;FBLC/en_US;FBOP/5;FBCR/]',
            'refr_source'   => 'Facebook'
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Facebook', $referer);
    }

    public function testGetLeadSource_fromPageReferrer_Youtube()
    {
        $source = [
            'page_url'      => 'https://glamnetic.com/?utm_source=jumbleberry&utm_medium=383591&utm_campaign=jbecom&click_id=03eNPLNCE62qmxzeC81gZfSVIwBQUcxl277lLQiE4UoWwD3AbC_-bmRj7exB58nO%2Fgoogle%2FEAIaIQobChMIqbLB87q36gIVx9B8Ch1SWQiVEAEYASAAEgKvgfD_BwE%2F&gclid=EAIaIQobChMIqbLB87q36gIVx9B8Ch1SWQiVEAEYASAAEgKvgfD_BwE',
            'page_referrer' => 'https://youtube.com',
            'useragent'     => "Mozilla/5.0 (iPhone; CPU iPhone OS 13_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Mobile/15E148 Safari/604.1",
            'refr_source'   => 'Youtube'
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Youtube', $referer);
    }

    public function testGetLeadSource_fromPageReferrer_Google()
    {
        $source = [
            'page_url'      => 'https://glamnetic.com/?utm_source=jumbleberry&utm_medium=383591&utm_campaign=jbecom&click_id=03eNPLNCE62qmxzeC81gZfSVIwBQUcxl277lLQiE4UoWwD3AbC_-bmRj7exB58nO%2Fgoogle%2FCj0KCQjwn7j2BRDrARIsAHJkxmwsjgiCIG6x05otIf0QwJKHoLPP_NKkVT06TheUJ7qt101N943D7yMaAtxyEALw_wcB%2F&gclid=Cj0KCQjwn7j2BRDrARIsAHJkxmwsjgiCIG6x05otIf0QwJKHoLPP_NKkVT06TheUJ7qt101N943D7yMaAtxyEALw_wcB',
            'page_referrer' => 'https://www.google.com',
            'useragent'     => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1 Mobile/15E148 Safari/604.1',
            'refr_source'   => 'Google'
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Google', $referer);
    }

    public function testGetLeadSource_fromPageReferrer_Instagram()
    {
        $source = [
            'page_url'      => 'https://article.onnit.com/productivity/?utm_medium=jumbleberry&utm_campaign=affiliate&utm_content=alpha-brain-offer-jmbb&utm_source=383591&jmb_cid=03eNPLNCE62qmxzeC81gZfurwvmotGCq3aDQNxgfYOeej8MNCDfkPHQKwuyXnYNZ%2Ffacebook%2F%2F',
            'page_referrer' => 'http://instagram.com',
            'useragent'     => "Mozilla/5.0 (Linux; Android 9; SM-G950W Build/PPR1.180610.011; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.101 Mobile Safari/537.36 Instagram 145.0.0.32.119 Android (28/9; 480dpi; 1080x2076; samsung; SM-G950W; dreamqltecan; qcom; en_CA; 219308759)",
            'refr_source'   => 'Instagram'
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Instagram', $referer);
    }

    public function testGetLeadSource_fromPageReferrer_withFbClid_Facebook()
    {

        $source = [
            'page_url'      => 'https://ketonowreal.com/us2/?a_aid=jby&cid=41&data1=380241&data2=3ee84d27-e980-4649-8e01-119d7d956095&utm_source=jby&utm_medium=cpa&utm_campaign=jby',
            'page_referrer' => 'https://cafe-metropol.com/exit.php?clid=107378967&lid=0&l=26581&o=7613&v=85322&e=9857&d=0&variant=25909608321&fbclid=IwAR2FvdV20_HVSDuOFcC-mxkO1iFJk7Thxc-HcwymnXUIl5C1FBThbuL5PGQ',
            'useragent'     => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.18362',
            'refr_source'   => null
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Facebook', $referer);
    }

    public function testGetLeadSource_fromPageReferrer_withMsclkid_Microsoft()
    {
        $source = [
            'page_url'      => 'https://glamnetic.com/?utm_source=jumbleberry&utm_medium=380164&utm_campaign=jbecom&click_id=zcW-Fu9kHp-d_C-p3tBV9Zaemyug5ErfuIYTK_cK-4Nm8uMe78NKo-dWJeuP8Qv8%2F%2F%2F',
            'page_referrer' => 'https://review.longerlashestoday.com/?msclkid=1ddbe737051610cdb897b9d9203b6cd4',
            'useragent'     => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Safari/605.1.15',
            'refr_source'   => null
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Microsoft', $referer);
    }

    public function testGetLeadSource_fromUserAgent_Snapchat()
    {
        $source = [
            'page_url'      => 'https://www.onnit.com/alpha-brain-offer-jmb/?utm_medium=jumbleberry&utm_campaign=affiliate&utm_content=alpha-brain-offer-jmbb&utm_source=384596&jmb_cid=177d63d4-1e08-4b0a-984e-bd9fc11c5ef8',
            'page_referrer' => 'https://article.onnit.com/productivity/?utm_medium=jumbleberry&utm_campaign=affiliate&utm_content=alpha-brain-offer-jmbb&utm_source=384596&jmb_cid=NTG9B-GlRjw5rV28B9OUj5jIJZ0RDe1kq-LTuX1Wdx2Dsj22epgfKB5pW7XhPa16%2FSNAP%2FPodcast%2F',
            'useragent'     => "Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Snapchat/10.80.5.79 (like Safari/604.1)",
            'refr_source'   => null
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Snapchat', $referer);
    }

    public function testGetLeadSource_fromUserAgent_Facebook()
    {
        $source = [
            'page_url'      => 'https://www.hanacure.com/products/the-all-in-one-facial-set?utm_source=jb&utm_medium=383466&utm_campaign=jbbuypresell&click_id=06a36c1b-2932-4705-93b7-d120a2d54fc1',
            'page_referrer' => 'https://healthybear.co/hanacure/dark/?utm_source=jb&utm_medium=383466&utm_campaign=jbbuypresell&click_id=1z35le2OeMvefL-TRNd1_63unoGp7E8W9u-SIDSnLoE%2F%2F%2F',
            'useragent'     => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBDV/iPhone11,8;FBMD/iPhone;FBSN/iOS;FBSV/13.5.1;FBSS/2;FBID/phone;FBLC/en_US;FBOP/5]',
            'refr_source'   => null
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Facebook', $referer);
    }

    public function testGetLeadSource_fromUserAgent_Instagram()
    {
        $source = [
            'page_url'      => 'https://buy.hanacure.com/?utm_source=jb&utm_medium=384826&utm_campaign=jbbuypresell&click_id=65263242-6798-4e47-bde1-23ec6d02e11e',
            'page_referrer' => 'https://healthybear.co/hanacure/dark/jbmain.html?utm_source=jb&utm_medium=384826&utm_campaign=jbbuypresell&click_id=I0ANRpzlmxk7C2mDdkeZoJbe9xdKLiuGlrkdLxBBzf4%2F%7BKKLandINT%7D%2F%7BFB%7D%2F%7BJacob%7D',
            'useragent'     => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 Instagram 146.0.0.21.122 (iPhone12,1; iOS 13_5_1; en_US; en-US; scale=2.00; 828x1792; 220223664)',
            'refr_source'   => null
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Instagram', $referer);
    }

    public function testGetLeadSource_fromUserAgent_Twitter()
    {
        $source = [
            'page_url'      => 'https://blog.hoomsleep.com/v3/?utm_source=jumbleberry&utm_campaign=jbpresell&utm_content=v3&utm_medium=384041&click_id=ddb272b2-07e9-4608-91b9-b4e96e80609e',
            'page_referrer' => 'https://blog.hoomsleep.com/v2.html?utm_source=jumbleberry&utm_campaign=jbpresell&utm_content=v3&utm_medium=384041&click_id=hEJJ--Y5_2SvjovPKuc1cdNYyqTmYVde1HPcS-izNn8~//wf5ko6rby970',
            'useragent'     => 'Mozilla/5.0 (iPad; CPU OS 9_3_5 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13G36 Twitter for iPhone/7.29.1',
            'refr_source'   => null
        ];
        $referer = $this->parser->parseReferrer($source['page_referrer'], $source['page_url'], $source['useragent']);
        $this->assertEquals('Twitter', $referer);
    }
}
