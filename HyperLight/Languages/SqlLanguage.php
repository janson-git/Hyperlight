<?php

namespace HyperLight\Languages;

use HyperLight\HyperLanguage;
use HyperLight\Rule;

class SqlLanguage extends HyperLanguage {
    public function __construct() {
        $this->setInfo(array(
            parent::NAME => 'SQL',
            parent::VERSION => '0.1',
            parent::AUTHOR => array(
                parent::NAME => 'Ivan Janson',
                parent::WEBSITE => '',
                parent::EMAIL => 'ivan.janson@gmail.com'
            )
        ));

        $this->setExtensions(array('sql'));

        $this->setCaseInsensitive(true);

        $this->addStates(array(
            'init' => array(
                'string',
                'number',
                'comment' => array('', 'doc'),
                'keyword' => array('', 'type', 'literal', 'operator', 'preprocessor'),
                'date',
                'identifier',
                'operator',
                'whitespace',
            ),
            'string' => 'escaped',
            'comment doc' => 'doc',
        ));

        $this->addRules(array(
            'whitespace' => Rule::ALL_WHITESPACE,
            'operator' => '/[-+*\/\\\\^&.=,()<>{}]/',
            'string' => new Rule('/\'/', '/"c?/i'),
            'number' => '/(?: # Integer followed by optional fractional part.
                (?:&(?:H[0-9a-f]+|O[0-7]+)|\d+)
                (?:\.\d*)?
                (?:e[+-]\d+)?
                U?[SILDFR%@!#&]?
            )
            |
            (?: # Just the fractional part.
                (?:\.\d+)
                (?:e[+-]\d+)?
                [FR!#]?
            )
            /ix',
            'escaped' => '/""/',
            'keyword' => array(
                array(
                    'create','table','update','insert', 'drop', 'delete', 'default', 'using', 'constraint', 'index','tablespace', 'unique', 'primary key'
                ),
                'type' => array(
                    'boolean', 'date', 'integer', 'varchar', 'text', 'null', 'bigserial', 'bigint'
                ),
                'literal' => array(
                    'false', 'true'
                ),
                'operator' => array(
                    'and', 'is', 'is not', 'like', 'not', 'or', 
                ),
                'preprocessor' => '/#(?:const|else|elseif|end if|end region|if|region)/i'
            ),
            'comment' => array(
                "/(?:'{1,2}[^']|--\s).*/i",
                'doc' => new Rule("/\/\*.*\*\//", '/$/m')
            ),
            'date' => '/#.+?#/',
            'identifier' => '/"?[a-z_][a-z_0-9]*|\[.+?\]"?/i',
            'doc' => '/<(?:".*?"|\'.*?\'|[^>])*>/',
        ));

        $this->addMappings(array(
            'whitespace' => '',
            'operator' => '',
            'date' => 'tag',
        ));
    }
}

