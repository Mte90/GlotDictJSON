#!/usr/bin/php
<?php

define('GLOTDICT_GLOSSARY','1.0.0');
$path = './dictionaries/'. GLOTDICT_GLOSSARY.'/';
$glossary_file_list = './dictionaries/'. GLOTDICT_GLOSSARY.'.json';

echo "Processing " . GLOTDICT_GLOSSARY . "<br>";

if( !file_exists($path) ) {
    mkdir($path, 0700);
}

$glossary_list = json_decode(trim(file_get_contents($glossary_file_list)), true);

$glossaries = array();
$glossaries['ast'] = go_download_glotdict('ast', "https://translate.wordpress.org/projects/wp/dev/ast/default/glossary");
$glossaries['bg_BG'] = go_download_glotdict('bg_BG', "https://translate.wordpress.org/projects/wp/dev/bg/default/glossary");
$glossaries['de_DE'] = go_download_glotdict('de_DE', "https://translate.wordpress.org/projects/wp/dev/de/default/glossary");
$glossaries['en_AU'] = go_download_glotdict('en_AU', "https://translate.wordpress.org/projects/wp/dev/en-au/default/glossary");
$glossaries['en_CA'] = go_download_glotdict('en_CA', "https://translate.wordpress.org/projects/wp/dev/en-ca/default/glossary");
$glossaries['es_ES'] = go_download_glotdict('es_ES', "https://translate.wordpress.org/projects/wp/dev/es/default/glossary");
$glossaries['fi'] = go_download_glotdict('fi', "https://translate.wordpress.org/projects/wp/dev/fi/default/glossary");
$glossaries['fr_FR'] = go_download_glotdict('fr_FR', "https://translate.wordpress.org/projects/wp/dev/fr/default/glossary");
$glossaries['he_IL'] = go_download_glotdict('he_IL', "https://translate.wordpress.org/projects/wp/dev/he/default/glossary");
$glossaries['hi_IN'] = go_download_glotdict('hi_IN', "https://translate.wordpress.org/projects/wp/dev/hi/default/glossary");
$glossaries['it_IT'] = go_download_glotdict('it_IT', "https://translate.wordpress.org/projects/wp/dev/it/default/glossary");
$glossaries['ja'] = go_download_glotdict('ja', "https://translate.wordpress.org/projects/wp/dev/ja/default/glossary");
$glossaries['lt_LT'] = go_download_glotdict('lt_LT', "https://translate.wordpress.org/projects/wp/dev/lt/default/glossary");
$glossaries['nl_NL'] = go_download_glotdict('nl_NL', "https://translate.wordpress.org/projects/wp/dev/nl/default/glossary");
$glossaries['pt_BR'] = go_download_glotdict('pt_BR', "https://translate.wordpress.org/projects/wp/dev/pt-br/default/glossary");
$glossaries['ro_RO'] = go_download_glotdict('ro_RO', "https://translate.wordpress.org/projects/wp/dev/ro/default/glossary");
//go_download_glotdict('sk_SK', "https://translate.wordpress.org/projects/wp/dev/sk/default/glossary");
$glossaries['sv_SE'] = go_download_glotdict('sv_SE', "https://translate.wordpress.org/projects/wp/dev/sv/default/glossary");
$glossaries['th'] = go_download_glotdict('th', "https://translate.wordpress.org/projects/wp/dev/th/default/glossary");
$glossaries['tr_TR'] = go_download_glotdict('tr_TR', "https://translate.wordpress.org/projects/wp/dev/tr/default/glossary");

file_put_contents( $glossary_file_list , json_encode( $glossaries, JSON_PRETTY_PRINT ) );

function go_download_glotdict($locale, $url) {
    global $path, $glossary_file_list, $glossary_list;
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
            // don't override if there is already a translation.
            if( false === array_key_exists( $values[0], $output ) ) {
                // construct translation
                $output[ $values[0] ][0] = array( "comment" => @$values[3], "pos" => @$values[2], "translation" => @$values[1] );
            } else {
                array_push($output[ $values[0] ], array( "comment" => @$values[3], "pos" => @$values[2], "translation" => @$values[1] ));
            }
        }
        
        if(file_exists($path . $locale . ".json")) {
            $old_json = json_decode(trim(file_get_contents($path . $locale . ".json")), true);
            $time = $glossary_list[$locale]['time'];
        } else {
            $old_json = '';
            $time = '';
        }
        
        if($old_json !== $output) {
            // write to locale json file
            file_put_contents( $path . $locale . ".json" , json_encode( $output, JSON_PRETTY_PRINT ) );
            $time = @date('d/m/Y');
        }

        // log info about locale
        echo count( $lines ) . ' Glossary terms<br>';
    } else {
        echo 'Page ' . $url . ' not responding...<br>';
    }
    return array('time'=>$time);
}
