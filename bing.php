<?php


// HTTP response status codes
$httpResponseStatusCodes = [
    200 => 'Success',
    // ... 其他狀態碼 ...
];

class BingScraper {
    private $headers;
    private $max_retries;
    private $num_results;
    private $retries;
    private $proxies;

    public function __construct($headers = null, $max_retries = 3, $num_results = 10, $proxy = null) {
        $this->headers = $headers ?: [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36"
        ];
        $this->max_retries = $max_retries;
        $this->num_results = $num_results;
        $this->retries = 0;
        $this->proxies = $proxy ? ['http' => $proxy, 'https' => $proxy] : null;
    }

    public function get_results($q) {
        $q_encode = urlencode($q);
        $url = "https://www.bing.com/search?q=$q_encode";

        $context = stream_context_create([
            'http' => [
                'header' => implode("\r\n", $this->headers),
                'proxy' => $this->proxies ? $this->proxies['http'] : null,
                'request_fulluri' => true,
            ]
        ]);

        $content = file_get_contents($url, false, $context);
        $status = $http_response_header[0];
        preg_match('/HTTP\/\d\.\d\s+(\d+)/', $status, $matches);
        $status_code = intval($matches[1]);

        $this->retries++;

        $results = [
            'query' => $url,
            'organic_results' => [],
            'status' => $GLOBALS['httpResponseStatusCodes'][$status_code] ?? 'Unknown'
        ];

        if ($status_code == 200) {
            $dom = new DOMDocument();
            @$dom->loadHTML($content);
            $xpath = new DOMXPath($dom);

            $b_results = $xpath->query('//ol[@id="b_results"]/li[@class="b_algo"]');

            foreach ($b_results as $pos => $elm) {
                if ($pos >= $this->num_results) break;

                $source = $title = $link = $summary = null;

                $tptt = $xpath->query('.//div[@class="tptt"]', $elm)->item(0);
                if ($tptt) $source = $tptt->textContent;

                $h2 = $xpath->query('.//h2', $elm)->item(0);
                if ($h2) {
                    $title = $h2->textContent;
                    $a = $xpath->query('.//a', $h2)->item(0);
                    if ($a) $link = $a->getAttribute('href');
                }

                $p = $xpath->query('.//p', $elm)->item(0);
                if ($p) $summary = $p->textContent;

                $results['organic_results'][] = [
                    'position' => $pos + 1,
                    'source' => $source,
                    'title' => $title,
                    'link' => $link,
                    'summary' => $summary
                ];
            }

            $results['number_of_results'] = count($results['organic_results']);
        } elseif ($this->retries < $this->max_retries) {
            $results = $this->get_results($q);
        }

        return $results;
    }
}

// 使用示例
$scraper = new BingScraper();
$results = $scraper->get_results("Apple");
echo json_encode($results, JSON_PRETTY_PRINT);


?>
