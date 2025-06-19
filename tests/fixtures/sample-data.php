<?php
/**
 * Test fixtures - sample data for testing
 *
 * @package PevneMezery\Tests
 */

return [
    'sample_posts' => [
        [
            'post_title' => 'Testovací příspěvek k domu s 10 kg',
            'post_content' => 'Mgr. Novák má 25 kg a jde k domu. např. takto můžeme pokračovat s 15 % slevou.',
            'post_excerpt' => 'Krátký výňatek s 5 % obsahem.',
            'post_type' => 'post',
            'post_status' => 'publish'
        ],
        [
            'post_title' => 'WooCommerce produkt 250 g',
            'post_content' => 'Popis produktu s Mgr. Novákem a 30 °C.',
            'post_type' => 'product',
            'post_status' => 'publish'
        ]
    ],
    
    'sample_comments' => [
        [
            'comment_content' => 'Komentář k domu s 20 kg a např. takto.',
            'comment_author' => 'Mgr. Tester',
            'comment_approved' => 1
        ]
    ],
    
    'typography_samples' => [
        'simple' => [
            'input' => 'k domu',
            'expected' => 'k&nbsp;domu'
        ],
        'complex' => [
            'input' => 'Mgr. Novák má 25 kg a jde k domu s 15 % slevou. např. takto: 5 + 3 = 8.',
            'expected' => 'Mgr.&nbsp;Novák má 25&nbsp;kg a&nbsp;jde k&nbsp;domu s&nbsp;15&nbsp;% slevou. např.&nbsp;takto: 5&nbsp;+&nbsp;3&nbsp;=&nbsp;8.'
        ],
        'html' => [
            'input' => '<p>Mgr. Novák <strong>má 25 kg</strong> a jde <em>k domu</em>.</p>',
            'expected' => '<p>Mgr.&nbsp;Novák <strong>má 25&nbsp;kg</strong> a&nbsp;jde <em>k&nbsp;domu</em>.</p>'
        ]
    ],
    
    'woocommerce_samples' => [
        'product_names' => [
            'Káva arabica 250 g',
            'Čaj zelený 100 g', 
            'Mléko plnotučné 1 l',
            'Čokoláda hořká 70 %'
        ],
        'descriptions' => [
            'Výborná káva s 15 % slevou',
            'Kvalitní čaj o hmotnosti 100 g',
            'Čerstvé mléko k domu s 3 % tuku'
        ]
    ],
    
    'acf_samples' => [
        'text_fields' => [
            'Mgr. Novák s 25 kg',
            'k domu s 10 %',
            'např. takto pokračujeme'
        ],
        'textarea_fields' => [
            "První řádek k domu.\nDruhý řádek s 10 kg.\nTřetí řádek např. takto."
        ],
        'wysiwyg_fields' => [
            '<p>např. takto můžeme psát <strong>s 15 %</strong></p>',
            '<div><h2>Mgr. Novák</h2><p>má 25 kg</p></div>'
        ]
    ],
    
    'edge_cases' => [
        'empty_strings' => ['', '   ', "\n", "\t"],
        'html_only' => ['<br>', '<div></div>', '<p></p>'],
        'special_chars' => [
            'Speciální znaky: áčžíé ěščř úůň',
            'Čísla: 123456789',
            'Symboly: @#$%^&*()'
        ],
        'large_content' => str_repeat('Mgr. Novák má 25 kg a jde k domu. ', 100)
    ]
];
