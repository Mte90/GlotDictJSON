#!/usr/bin/php
<?php

define('GLOTDICT_GLOSSARY','1.0.1');
$path = './dictionaries/'. GLOTDICT_GLOSSARY.'/';
$glossary_file_list = './dictionaries/'. GLOTDICT_GLOSSARY.'.json';

echo "Processing " . GLOTDICT_GLOSSARY . "<br>";

if( !file_exists($path) ) {
    mkdir($path, 0700);
}

$glossary_list = json_decode(trim(file_get_contents($glossary_file_list)), true);

$glossaries = array();
$glossaries['ast']   = go_download_glotdict('ast',   "https://translate.wordpress.org/locale/ast/default/glossary");
$glossaries['bel']   = go_download_glotdict('bel',   "https://translate.wordpress.org/locale/bel/default/glossary");
$glossaries['bg_BG'] = go_download_glotdict('bg_BG', "https://translate.wordpress.org/locale/bg/default/glossary");
$glossaries['cy']    = go_download_glotdict('cy',    "https://translate.wordpress.org/locale/cy/default/glossary");
$glossaries['da_DK'] = go_download_glotdict('da_DK', "https://translate.wordpress.org/locale/da/default/glossary");
$glossaries['de_DE'] = go_download_glotdict('de_DE', "https://translate.wordpress.org/locale/de/default/glossary");
$glossaries['en_AU'] = go_download_glotdict('en_AU', "https://translate.wordpress.org/locale/en-au/default/glossary");
$glossaries['en_CA'] = go_download_glotdict('en_CA', "https://translate.wordpress.org/locale/en-ca/default/glossary");
$glossaries['en_GB'] = go_download_glotdict('en_GB', "https://translate.wordpress.org/locale/en-gb/default/glossary");
$glossaries['es_ES'] = go_download_glotdict('es_ES', "https://translate.wordpress.org/locale/es/default/glossary");
$glossaries['fi']    = go_download_glotdict('fi',    "https://translate.wordpress.org/locale/fi/default/glossary");
$glossaries['fr_FR'] = go_download_glotdict('fr_FR', "https://translate.wordpress.org/locale/fr/default/glossary");
$glossaries['gu']    = go_download_glotdict('gu',    "https://translate.wordpress.org/locale/gu/default/glossary");
$glossaries['he_IL'] = go_download_glotdict('he_IL', "https://translate.wordpress.org/locale/he/default/glossary");
$glossaries['hi_IN'] = go_download_glotdict('hi_IN', "https://translate.wordpress.org/locale/hi/default/glossary");
$glossaries['hr_HR'] = go_download_glotdict('hr_HR', "https://translate.wordpress.org/locale/hr/default/glossary");
$glossaries['it_IT'] = go_download_glotdict('it_IT', "https://translate.wordpress.org/locale/it/default/glossary");
$glossaries['ja']    = go_download_glotdict('ja',    "https://translate.wordpress.org/locale/ja/default/glossary");
$glossaries['lt_LT'] = go_download_glotdict('lt_LT', "https://translate.wordpress.org/locale/lt/default/glossary");
$glossaries['lv_LV'] = go_download_glotdict('lv_LV', "https://translate.wordpress.org/locale/lv/default/glossary");
//$glossaries['ne_NP'] = go_download_glotdict('ne_NP', "https://translate.wordpress.org/locale/ne/default/glossary");
$glossaries['nl_BE'] = go_download_glotdict('nl_BE', "https://translate.wordpress.org/locale/nl-be/default/glossary");
$glossaries['nl_NL'] = go_download_glotdict('nl_NL', "https://translate.wordpress.org/locale/nl/default/glossary");
$glossaries['pt_BR'] = go_download_glotdict('pt_BR', "https://translate.wordpress.org/locale/pt-br/default/glossary");
$glossaries['pl']    = go_download_glotdict('pl',    "https://translate.wordpress.org/locale/pl/default/glossary");
$glossaries['ro_RO'] = go_download_glotdict('ro_RO', "https://translate.wordpress.org/locale/ro/default/glossary");
$glossaries['sk']    = go_download_glotdict('sk',    "https://translate.wordpress.org/locale/sk/default/glossary");
$glossaries['sv_SE'] = go_download_glotdict('sv_SE', "https://translate.wordpress.org/locale/sv/default/glossary");
$glossaries['th']    = go_download_glotdict('th',    "https://translate.wordpress.org/locale/th/default/glossary");
$glossaries['tr_TR'] = go_download_glotdict('tr_TR', "https://translate.wordpress.org/locale/tr/default/glossary");
$glossaries['uk']    = go_download_glotdict('uk',    "https://translate.wordpress.org/locale/uk/default/glossary");
$glossaries['vi']    = go_download_glotdict('vi',    "https://translate.wordpress.org/locale/vi/default/glossary");

file_put_contents( $glossary_file_list , str_replace('\/','/',json_encode( $glossaries, JSON_PRETTY_PRINT )) );

function go_download_glotdict($locale, $url) {
    global $path, $glossary_list;
    $output = array();

    // be sure that the $url ends with a trailing slash
    if ( substr( $url, -1 ) != "/" ) {
        $url .= "/";
    }

    // add "-export" to $url for CSV export
    $url .= "-export";

    echo "Processing " . $locale . "...\n";
    $file = file_get_contents( $url );

    if ( $file !== false ) {
        $lines = explode( "\n", trim( $file ) );

        // get rid of first line, because it's information that we don't need
        array_shift( $lines );

        // iterating each line
        foreach ( $lines as $csv ) {
            $values = str_getcsv( $csv );
            $values[0] = trim( strtolower( $values[0] ) );
            if(empty($values[0])) {
                continue;
            }
            $values_[1] = '';
            $values_[2] = '';
            $values_[3] = '';
            if(isset($values[1]))  {
                $values_[1] = trim( $values[1] );
            }
            if(isset($values[2])) {
                $values_[2] = trim( $values[2] );
            }
            if(isset($values[3])) {
                $values_[3] = trim( $values[3] );
            }
            $values[1] = $values_[1];
            $values[2] = $values_[2];
            $values[3] = $values_[3];
            // don't override if there is already a translation.
            if(
                false === array_key_exists( $values[0], $output ) ||
                $output[ $values[0] ][0]['pos'] === @$values[2] && $output[ $values[0] ][0]['translation'] === @$values[1] && empty($output[ $values[0] ][0]['comment'])
            ) {
                // construct translation
                $output[ $values[0] ][0] = array( "comment" => @$values[3], "pos" => @$values[2], "translation" => @$values[1] );
            } else {
                if( !empty( $values[2] ) && !empty( $values[1] )) {
                    array_push($output[ $values[0] ], array( "comment" => @$values[3], "pos" => @$values[2], "translation" => @$values[1] ));
                }
            }
        }

        if(file_exists($path . $locale . ".json")) {
            $old_json = json_decode(trim(file_get_contents($path . $locale . ".json")), true);
            if(!isset($glossary_list[$locale]['time']) || empty($time)) {
                $time = @date('d/m/Y');
            } else {
                $time = $glossary_list[$locale]['time'];
            }
        } else {
            $old_json = '';
            $time = @date('d/m/Y');
        }

        // Sort terms by length
        uksort($output, function($a, $b){
            return strlen($b) - strlen($a);
        });

        if($old_json !== $output) {
            // write to locale json file
            file_put_contents( $path . $locale . ".json" , json_encode( $output, JSON_PRETTY_PRINT ) );
            $time = @date('d/m/Y');
        }

        // log info about locale
        echo count( $lines ) . ' Glossary terms<br>'."\n";
    } else {
        echo 'Page ' . $url . ' not responding...<br>'."\n";
    }

    return array('time'=>$time);
}
