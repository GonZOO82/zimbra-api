<?php

namespace Zimbra\Account\Tests\Request;

use Zimbra\Account\Tests\ZimbraAccountApiTestCase;
use Zimbra\Account\Request\Auth;
use Zimbra\Account\Struct\PreAuth;
use Zimbra\Account\Struct\AuthToken;
use Zimbra\Account\Struct\Attr;
use Zimbra\Account\Struct\AuthAttrs;
use Zimbra\Account\Struct\Pref;
use Zimbra\Account\Struct\AuthPrefs;
use Zimbra\Enum\AccountBy;
use Zimbra\Struct\AccountSelector;

/**
 * Testcase class for Auth.
 */
class AuthTest extends ZimbraAccountApiTestCase
{
    public function testAuthRequest()
    {
        $name = $this->faker->word;
        $value = $this->faker->word;
        $password = $this->faker->word;
        $virtualHost = $this->faker->word;
        $requestedSkin = $this->faker->word;
        $time = time();

        $account = new AccountSelector(AccountBy::NAME(), $value);
        $preauth = new PreAuth($time, $value, $time);
        $authToken = new AuthToken($value, true);

        $attr = new Attr($name, $value, true);
        $attrs = new AuthAttrs([$attr]);

        $pref = new Pref($name, $value, $time);
        $prefs = new AuthPrefs([$pref]);

        $req = new Auth(
            $account, $password, $preauth, $authToken, $virtualHost,
            $prefs, $attrs, $requestedSkin, false, true
        );
        $this->assertInstanceOf('Zimbra\Account\Request\Base', $req);
        $this->assertSame($account, $req->getAccount());
        $this->assertSame($password, $req->getPassword());
        $this->assertSame($preauth, $req->getPreAuth());
        $this->assertSame($authToken, $req->getAuthToken());
        $this->assertSame($virtualHost, $req->getVirtualHost());
        $this->assertSame($prefs, $req->getPrefs());
        $this->assertSame($attrs, $req->getAttrs());
        $this->assertSame($requestedSkin, $req->getRequestedSkin());
        $this->assertFalse($req->getPersistAuthTokenCookie());
        $this->assertTrue($req->getCsrfTokenSecured());

        $req->setAccount($account)
            ->setPassword($password)
            ->setPreAuth($preauth)
            ->setAuthToken($authToken)
            ->setVirtualHost($virtualHost)
            ->setPrefs($prefs)
            ->setAttrs($attrs)
            ->setRequestedSkin($requestedSkin)
            ->setPersistAuthTokenCookie(true)
            ->setCsrfTokenSecured(false);
        $this->assertSame($account, $req->getAccount());
        $this->assertSame($password, $req->getPassword());
        $this->assertSame($preauth, $req->getPreAuth());
        $this->assertSame($authToken, $req->getAuthToken());
        $this->assertSame($virtualHost, $req->getVirtualHost());
        $this->assertSame($prefs, $req->getPrefs());
        $this->assertSame($attrs, $req->getAttrs());
        $this->assertSame($requestedSkin, $req->getRequestedSkin());
        $this->assertTrue($req->getPersistAuthTokenCookie());
        $this->assertFalse($req->getCsrfTokenSecured());

        $xml = '<?xml version="1.0"?>' . "\n"
            . '<AuthRequest persistAuthTokenCookie="true" csrfTokenSecured="false">'
                . '<account by="' . AccountBy::NAME() . '">' . $value . '</account>'
                . '<password>' . $password . '</password>'
                . '<preauth timestamp="' . $time . '" expiresTimestamp="' . $time . '">' . $value . '</preauth>'
                . '<authToken verifyAccount="true">' . $value . '</authToken>'
                . '<virtualHost>' . $virtualHost . '</virtualHost>'
                . '<prefs>'
                    . '<pref name="' . $name . '" modified="' . $time . '">' . $value . '</pref>'
                . '</prefs>'
                . '<attrs>'
                    . '<attr name="' . $name . '" pd="true">' . $value . '</attr>'
                . '</attrs>'
                . '<requestedSkin>' . $requestedSkin . '</requestedSkin>'
            . '</AuthRequest>';
        $this->assertXmlStringEqualsXmlString($xml, (string) $req);

        $array = [
            'AuthRequest' => [
                '_jsns' => 'urn:zimbraAccount',
                'account' => [
                    'by' => AccountBy::NAME()->value(),
                    '_content' => $value,
                ],
                'password' => $password,
                'preauth' => [
                    'timestamp' => $time,
                    'expiresTimestamp' => $time,
                    '_content' => $value,
                ],
                'authToken' => [
                    'verifyAccount' => true,
                    '_content' => $value,
                ],
                'virtualHost' => $virtualHost,
                'prefs' => [
                    'pref' => [
                        [
                            'name' => $name,
                            'modified' => $time,
                            '_content' => $value,
                        ],
                    ],
                ],
                'attrs' => [
                    'attr' => [
                        [
                            'name' => $name,
                            'pd' => true,
                            '_content' => $value,
                        ],
                    ],
                ],
                'requestedSkin' => $requestedSkin,
                'persistAuthTokenCookie' => true,
                'csrfTokenSecured' => false,
            ],
        ];
        $this->assertEquals($array, $req->toArray());
    }

    public function testAuthApi()
    {
        $name = $this->faker->word;
        $value = $this->faker->word;
        $password = $this->faker->word;
        $virtualHost = $this->faker->word;
        $requestedSkin = $this->faker->word;
        $time = time();

        $account = new AccountSelector(AccountBy::NAME(), $value);
        $preauth = new PreAuth($time, $value, $time);
        $authToken = new AuthToken($value, true);

        $attr = new Attr($name, $value, true);
        $attrs = new AuthAttrs([$attr]);

        $pref = new Pref($name, $value, $time);
        $prefs = new AuthPrefs([$pref]);

        $this->api->auth(
            $account, $password, $preauth, $authToken, $virtualHost,
            $prefs, $attrs, $requestedSkin, false, false
        );

        $client = $this->api->getClient();
        $req = $client->lastRequest();
        $xml = '<?xml version="1.0"?>' . "\n"
            . '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:urn="urn:zimbra" xmlns:urn1="urn:zimbraAccount">'
                . '<env:Body>'
                    . '<urn1:AuthRequest persistAuthTokenCookie="false" csrfTokenSecured="false">'
                        . '<urn1:account by="' . AccountBy::NAME() . '">' . $value . '</urn1:account>'
                        . '<urn1:password>' . $password . '</urn1:password>'
                        . '<urn1:preauth timestamp="' . $time . '" expiresTimestamp="' . $time . '">' . $value . '</urn1:preauth>'
                        . '<urn1:authToken verifyAccount="true">' . $value . '</urn1:authToken>'
                        . '<urn1:virtualHost>' . $virtualHost . '</urn1:virtualHost>'
                        . '<urn1:prefs>'
                            . '<urn1:pref name="' . $name . '" modified="' . $time . '">' . $value . '</urn1:pref>'
                        . '</urn1:prefs>'
                        . '<urn1:attrs>'
                            . '<urn1:attr name="' . $name . '" pd="true">' . $value . '</urn1:attr>'
                        . '</urn1:attrs>'
                        . '<urn1:requestedSkin>' . $requestedSkin . '</urn1:requestedSkin>'
                    . '</urn1:AuthRequest>'
                . '</env:Body>'
            . '</env:Envelope>';
        $this->assertXmlStringEqualsXmlString($xml, (string) $req);
    }

    public function testAuthByAcount()
    {
        $value = $this->faker->word;
        $password = $this->faker->sha1;
        $virtualHost = $this->faker->word;

        $account = new AccountSelector(AccountBy::NAME(), $value);

        $this->api->authByAcount(
            $account, $password, $virtualHost
        );

        $client = $this->api->getClient();
        $req = $client->lastRequest();
        $xml = '<?xml version="1.0"?>' . "\n"
            . '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:urn="urn:zimbra" xmlns:urn1="urn:zimbraAccount">'
                . '<env:Body>'
                    . '<urn1:AuthRequest>'
                        . '<urn1:account by="' . AccountBy::NAME() . '">' . $value . '</urn1:account>'
                        . '<urn1:password>' . $password . '</urn1:password>'
                        . '<urn1:virtualHost>' . $virtualHost . '</urn1:virtualHost>'
                        . '<urn1:prefs />'
                        . '<urn1:attrs />'
                    . '</urn1:AuthRequest>'
                . '</env:Body>'
            . '</env:Envelope>';
        $this->assertXmlStringEqualsXmlString($xml, (string) $req);
    }

    public function testAuthByToken()
    {
        $value = $this->faker->sha1;
        $virtualHost = $this->faker->word;

        $account = new AccountSelector(AccountBy::NAME(), $value);
        $authToken = new AuthToken($value, true);

        $this->api->authByToken(
            $account, $authToken, $virtualHost
        );

        $client = $this->api->getClient();
        $req = $client->lastRequest();
        $xml = '<?xml version="1.0"?>' . "\n"
            . '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:urn="urn:zimbra" xmlns:urn1="urn:zimbraAccount">'
                . '<env:Body>'
                    . '<urn1:AuthRequest>'
                        . '<urn1:account by="' . AccountBy::NAME() . '">' . $value . '</urn1:account>'
                        . '<urn1:authToken verifyAccount="true">' . $value . '</urn1:authToken>'
                        . '<urn1:virtualHost>' . $virtualHost . '</urn1:virtualHost>'
                        . '<urn1:prefs />'
                        . '<urn1:attrs />'
                    . '</urn1:AuthRequest>'
                . '</env:Body>'
            . '</env:Envelope>';
        $this->assertXmlStringEqualsXmlString($xml, (string) $req);
    }
}
