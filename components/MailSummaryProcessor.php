<?php

namespace humhub\modules\limitsummarymail\components;

use Yii;
use humhub\libs\StringHelper;

class MailSummaryProcessor
{
    private $charLimit;

    public function __construct($charLimit = 500)
    {
        $this->charLimit = max(1, min(500, (int)$charLimit));
        Yii::info('LimitSummaryMail: Processor initialized, limit: ' . $this->charLimit, 'limitsummarymail');
    }

    public function processHtmlContent($html)
    {
        Yii::info('LimitSummaryMail: Processing HTML, length: ' . strlen($html), 'limitsummarymail');

        if (empty($html)) {
            return $html;
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        
        $wrappedHtml = '<div id="humhub-mail-wrapper">' . $html . '</div>';
        $loaded = @$dom->loadHTML('<?xml encoding="UTF-8">' . $wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        if (!$loaded) {
            Yii::warning('LimitSummaryMail: DOM load failed, trying regex fallback', 'limitsummarymail');
            return $this->processWithRegex($html);
        }

        $modified = false;
        $xpath = new \DOMXPath($dom);
        $textNodes = $xpath->query('//text()[string-length(normalize-space(.)) > 100]');
        
        Yii::info('LimitSummaryMail: Found ' . $textNodes->length . ' text nodes with >100 chars', 'limitsummarymail');

        foreach ($textNodes as $textNode) {
            $originalText = $textNode->nodeValue;
            $plainText = trim($originalText);
            
            if (mb_strlen($plainText, 'UTF-8') <= $this->charLimit) {
                continue;
            }

            $parent = $textNode->parentNode;
            if ($parent && in_array(strtolower($parent->nodeName), ['script', 'style'])) {
                continue;
            }

            Yii::info('LimitSummaryMail: Truncating text node from ' . mb_strlen($plainText, 'UTF-8') . ' chars', 'limitsummarymail');

            $truncated = StringHelper::truncate($plainText, $this->charLimit, '...');

            $fragment = $dom->createDocumentFragment();
            @$fragment->appendXML($truncated);
            
            if ($fragment->hasChildNodes()) {
                $parent->replaceChild($fragment, $textNode);
                $modified = true;
            } else {
                $textNode->nodeValue = strip_tags($truncated);
                $modified = true;
            }
        }

        if ($modified) {
            $wrapper = $dom->getElementById('humhub-mail-wrapper');
            if ($wrapper) {
                $result = '';
                foreach ($wrapper->childNodes as $child) {
                    $result .= $dom->saveHTML($child);
                }
                Yii::info('LimitSummaryMail: HTML modified successfully', 'limitsummarymail');
                return $result;
            }
        }

        Yii::warning('LimitSummaryMail: HTML was NOT modified', 'limitsummarymail');
        return $html;
    }

    private function processWithRegex($html)
    {
        Yii::info('LimitSummaryMail: Using regex fallback', 'limitsummarymail');
        
        $pattern = '/(<td[^>]*>)([\s\S]{200,}?)(<\/td>)/i';
        
        $html = preg_replace_callback($pattern, function($matches) {
            $opening = $matches[1];
            $content = $matches[2];
            $closing = $matches[3];
            
            $plainText = strip_tags($content);
            $plainText = html_entity_decode($plainText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $plainText = trim($plainText);
            
            $length = mb_strlen($plainText, 'UTF-8');
            
            if ($length <= $this->charLimit || $length < 100) {
                return $matches[0];
            }
            
            Yii::info('LimitSummaryMail: Regex truncating from ' . $length . ' chars', 'limitsummarymail');
            
            $truncated = StringHelper::truncate($plainText, $this->charLimit, '...');
            
            return $opening . $truncated . $closing;
        }, $html);
        
        return $html;
    }
}
