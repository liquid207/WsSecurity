<?php

declare(strict_types=1);

namespace WsdlToPhp\WsSecurity\Tests;

use SoapHeader;
use WsdlToPhp\WsSecurity\WsSecurity;

/**
 * @internal
 * @coversDefaultClass
 */
final class WsSecurityTest extends TestCase
{
    public function testCreateWithExpiresIn()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824, 600);
        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
            <wsu:Timestamp xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                <wsu:Created>2016-03-31T19:17:04Z</wsu:Created>
                <wsu:Expires>2016-03-31T19:27:04Z</wsu:Expires>
            </wsu:Timestamp>
        </wsse:Security>'), $header->data->enc_value);
    }

    public function testCreateWithoutExpiresIn()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824);
        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->data->enc_value);
    }

    public function testCreateWithMustUnderstand()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824, 0, true, true);
        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustunderstand="1">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->data->enc_value);
    }

    public function testCreateWithMustUnderstandAndActor()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824, 0, true, true, 'BAR');
        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustunderstand="1" SOAP-ENV:actor="BAR">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->data->enc_value);
    }

    public function testCreateSoapVar()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824, 0, false, true, 'BAR');
        $this->assertInstanceOf('\SoapVar', $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustunderstand="1" SOAP-ENV:actor="BAR">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->enc_value);
    }

    public function testCreateWithPasswordDigest()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', true, 1459451824, 0, false, true, 'BAR');
        $this->assertInstanceOf('\SoapVar', $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustunderstand="1" SOAP-ENV:actor="BAR">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">([a-zA-Z0-9=/+]*)</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->enc_value);
    }

    public function testCreateWithUsernameId()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824, 0, true, true, 'BAR', 'X90I3u8');
        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustunderstand="1" SOAP-ENV:actor="BAR">
            <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="X90I3u8">
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->data->enc_value);
    }

    public function testCreateWithoutNonce()
    {
        $header = WsSecurity::createWsSecuritySoapHeader('foo', 'bar', false, 1459451824, 0, true, true, 'BAR', 'X90I3u8', false);
        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustunderstand="1" SOAP-ENV:actor="BAR">
            <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="X90I3u8">
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->data->enc_value);
    }

    public function testCreateWithEnvelopeNamespace()
    {
        $header = WsSecurity::createWsSecuritySoapHeader(
            'foo',
            'bar',
            false,
            1459451824,
            0,
            true,
            true,
            'BAR',
            null,
            true,
            'env'
        );

        $this->assertInstanceOf(SoapHeader::class, $header);
        $this->assertMatches(self::innerTrim('
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" env:mustunderstand="1" env:actor="BAR">
            <wsse:UsernameToken>
                <wsse:Username>foo</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">bar</wsse:Password>
                <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">2016-03-31T19:17:04Z</wsu:Created>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">([a-zA-Z0-9=]*)</wsse:Nonce>
            </wsse:UsernameToken>
        </wsse:Security>'), $header->data->enc_value);
    }
}
