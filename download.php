#!/usr/bin/php
<?php
echo 'Download it_IT' . "...\n";
echo shell_exec('./convert-it_IT.py');
go_download_glotdict('bg_BG', "https://translate.wordpress.org/projects/wp/dev/bg/default/glossary");
go_download_glotdict('de_DE', "https://translate.wordpress.org/projects/wp/dev/de/default/glossary");
go_download_glotdict('en_AU', "https://translate.wordpress.org/projects/wp/dev/en-au/default/glossary");
go_download_glotdict('en_CA', "https://translate.wordpress.org/projects/wp/dev/en-ca/default/glossary");
go_download_glotdict('es_ES', "https://translate.wordpress.org/projects/wp/dev/es/default/glossary");
go_download_glotdict('fi', "https://translate.wordpress.org/projects/wp/dev/fi/default/glossary");
go_download_glotdict('fr_FR', "https://translate.wordpress.org/projects/wp/dev/fr/default/glossary");
go_download_glotdict('he_IL', "https://translate.wordpress.org/projects/wp/dev/he/default/glossary");
go_download_glotdict('hi_IN', "https://translate.wordpress.org/projects/wp/dev/hi/default/glossary");
go_download_glotdict('lt_LT', "https://translate.wordpress.org/projects/wp/dev/lt/default/glossary");
go_download_glotdict('ja', "https://translate.wordpress.org/projects/wp/dev/ja/default/glossary");
go_download_glotdict('nl_NL', "https://translate.wordpress.org/projects/wp/dev/nl/default/glossary");
go_download_glotdict('pt_BR', "https://translate.wordpress.org/projects/wp/dev/pt-br/default/glossary");
go_download_glotdict('ro_RO', "https://translate.wordpress.org/projects/wp/dev/ro/default/glossary");
//go_download_glotdict('sk_SK', "https://translate.wordpress.org/projects/wp/dev/sk/default/glossary");
go_download_glotdict('sv_SE', "https://translate.wordpress.org/projects/wp/dev/sv/default/glossary");
go_download_glotdict('th', "https://translate.wordpress.org/projects/wp/dev/th/default/glossary");
go_download_glotdict('tr_TR', "https://translate.wordpress.org/projects/wp/dev/tr/default/glossary");


function go_download_glotdict($locale, $url) {
        $response = array();
        $lines = '';
        echo 'Download ' . $locale . "...\n";
        $doc1 = file_get_contents($url);
        if ($doc1 !== false) {
            $doc = new DOMDocument;
            @$doc->loadHTML($doc1);
            $doc->preserveWhiteSpace = false;
            $classname = 'view';
            $xpath = new DOMXPath($doc);
            $rows = $xpath->query("//*[@class='" . $classname . "']");
            if ($rows->length > 2) {
                $firstrow = true;
                foreach ($rows as $row) {
                    if ($firstrow) {
                        $firstrow = false;
                    } else {
                        $lines = $lines . ',';
                    }
                    $all_info = $row->getElementsByTagName('td');
                    $line = array();
                    $line[$all_info->item(0)->nodeValue] = array('comment' => $all_info->item(3)->nodeValue, 'pos' => '', 'translation' => $all_info->item(2)->nodeValue);
                    $lines = $lines . substr(json_encode($line, JSON_PRETTY_PRINT),1,-1);
                }
                file_put_contents('./dictionaries/' . $locale.'.json' , '{' . $lines . '}');
                echo $rows->length . ' Glossary terms' . "\n";
            } else {
                echo ' No rows found on page ' . $url . "\n";
            }
        } else {
            echo 'Page ' . $url . ' not responding...' . "\n";
        }
}
