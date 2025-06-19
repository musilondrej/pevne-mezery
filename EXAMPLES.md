# Příklady použití - Pevné Mezery Plugin

## Základní příklady

### Před a po zpracování

```php
// Vstup
$text = "Mgr. Novák má 25 kg a jde k domu.";

// Výstup po zpracování
$result = "Mgr.&nbsp;Novák má 25&nbsp;kg a&nbsp;jde k&nbsp;domu.";
```

### Matematické výrazy

```php
// Vstup
$text = "Výsledek je 5 + 3 = 8 nebo 10 - 2 = 8.";

// Výstup
$result = "Výsledek je 5&nbsp;+&nbsp;3&nbsp;=&nbsp;8 nebo 10&nbsp;-&nbsp;2&nbsp;=&nbsp;8.";
```

### Jednotky a měry

```php
// Vstup
$text = "Balení má 500 g, teplota 25 °C, cena 150 Kč.";

// Výstup
$result = "Balení má 500&nbsp;g, teplota 25&nbsp;°C, cena 150&nbsp;Kč.";
```

## WordPress integrace

### Automatické zpracování obsahu

```php
// Plugin automaticky zpracovává tyto filtry:
$filters = [
    'comment_author',    // Jména autorů komentářů
    'term_name',         // Názvy kategorií/štítků
    'link_name',         // Názvy odkazů
    'link_description',  // Popisy odkazů
    'link_notes',        // Poznámky k odkazům
    'bloginfo',          // Informace o blogu
    'wp_title',          // Titulek stránky
    'widget_title',      // Názvy widgetů
    'term_description',  // Popisy kategorií/štítků
    'the_title',         // Názvy příspěvků
    'the_content',       // Obsah příspěvků
    'the_excerpt',       // Výňatky
    'comment_text',      // Text komentářů
    'single_post_title', // Titulky jednotlivých příspěvků
    'list_cats',         // Výpisy kategorií
];
```

### Ruční zpracování textu

```php
// Přímé volání pro vlastní text
$text = "k domu s 10 kg";
$processed = \MusilTech\PevneMezery\ContentHandler::process_content($text);
echo $processed; // "k&nbsp;domu s&nbsp;10&nbsp;kg"
```

## WooCommerce příklady

### Názvy produktů

```php
// Automaticky zpracovává názvy produktů
// Vstup: "Káva arabica 250 g"
// Výstup: "Káva arabica 250&nbsp;g"

// Ruční zpracování názvu produktu
add_filter('woocommerce_product_get_name', function($name) {
    return \MusilTech\PevneMezery\ContentHandler::process_content($name);
});
```

### Popisy produktů

```php
// Krátký popis se zpracovává automaticky
// Vstup: "Výborná káva s 15 % slevou"
// Výstup: "Výborná káva s&nbsp;15&nbsp;% slevou"

// Dlouhý popis se také zpracovává automaticky
```

### Košík a checkout

```php
// Názvy položek v košíku se zpracovávají automaticky
// Vstup: "Káva arabica 250 g × 2"
// Výstup: "Káva arabica 250&nbsp;g × 2"
```

## ACF (Advanced Custom Fields) příklady

### Různé typy polí

```php
// Text pole
get_field('my_text');        // Automaticky zpracováno

// Textarea
get_field('my_textarea');    // Automaticky zpracováno

// WYSIWYG
get_field('my_wysiwyg');     // Automaticky zpracováno

// Select
get_field('my_select');      // Automaticky zpracováno

// Checkbox (pokud string hodnoty)
get_field('my_checkbox');    // Automaticky zpracováno

// Radio
get_field('my_radio');       // Automaticky zpracováno
```

### Vlastní ACF integrace

```php
// Přidání podpory pro custom field type
add_filter('acf/format_value/type=my_custom_type', function($value, $post_id, $field) {
    if (is_string($value)) {
        return \MusilTech\PevneMezery\ContentHandler::process_content($value);
    }
    return $value;
}, 10, 3);
```

## Pokročilé použití

### Vlastní filtry

```php
// Přidání vlastního filtru
add_filter('pevne_mezery', function($filters) {
    $filters[] = 'my_custom_filter';
    return $filters;
});

// Aplikace na vlastní filtr
add_filter('my_custom_filter', [\MusilTech\PevneMezery\ContentHandler::class, 'process_content']);
```

### Odebírání filtrů

```php
// Odstranění titulků z zpracování
add_filter('pevne_mezery', function($filters) {
    $key = array_search('the_title', $filters);
    if ($key !== false) {
        unset($filters[$key]);
    }
    return $filters;
});
```

### Podmíněné zpracování

```php
// Zpracování pouze pro určité post types
add_filter('the_title', function($title, $post_id) {
    if (get_post_type($post_id) === 'product') {
        return \MusilTech\PevneMezery\ContentHandler::process_content($title);
    }
    return $title;
}, 10, 2);
```

## Debug a vývoj

### Aktivace debug režimu

```php
// V functions.php nebo custom pluginu
add_action('init', function() {
    if (WP_DEBUG) {
        \MusilTech\PevneMezery\ContentHandler::set_debug_mode(true);
    }
});
```

### Sledování zpracování

```php
// Debug output pro testování
add_filter('the_content', function($content) {
    $original = $content;
    $processed = \MusilTech\PevneMezery\ContentHandler::process_content($content);
    
    if (WP_DEBUG && $original !== $processed) {
        error_log('Pevné mezery: ' . $original . ' → ' . $processed);
    }
    
    return $processed;
});
```

## Cache management

### Ruční správa cache

```php
// Vymazání cache pro konkrétní obsah
$content = "nějaký text";
\MusilTech\PevneMezery\CacheHandler::delete_cached_content($content);

// Vymazání celého kontextu
\MusilTech\PevneMezery\CacheHandler::delete_cache_by_context('product');

// Kontrola cache
$cached = \MusilTech\PevneMezery\CacheHandler::get_cached_content($content);
if ($cached !== null) {
    echo "Content je v cache";
}
```

### Cache warming

```php
// Předvytvoření cache pro často používané texty
$common_texts = [
    "k domu",
    "s přáteli", 
    "v práci",
    "10 kg",
    "např. takto"
];

foreach ($common_texts as $text) {
    \MusilTech\PevneMezery\ContentHandler::process_content($text);
}
```

## Testování

### Jednotkové testy

```php
// Test jednopísmenných předložek
function test_prepositions() {
    $tests = [
        "k domu" => "k&nbsp;domu",
        "s přáteli" => "s&nbsp;přáteli",
        "v práci" => "v&nbsp;práci",
        "z města" => "z&nbsp;města",
        "o tom" => "o&nbsp;tom",
        "u nás" => "u&nbsp;nás",
        "a také" => "a&nbsp;také",
        "i když" => "i&nbsp;když"
    ];
    
    foreach ($tests as $input => $expected) {
        $result = \MusilTech\PevneMezery\ContentHandler::process_content($input);
        assert($result === $expected, "Failed: $input → $result (expected: $expected)");
    }
}
```

### Výkonnostní testy

```php
// Test rychlosti zpracování
function benchmark_processing() {
    $content = str_repeat("Mgr. Novák má 25 kg a jde k domu. ", 1000);
    
    $start = microtime(true);
    for ($i = 0; $i < 100; $i++) {
        \MusilTech\PevneMezery\ContentHandler::process_content($content);
    }
    $time = microtime(true) - $start;
    
    echo "Průměrný čas zpracování: " . ($time / 100) . " sekund\n";
}
```

## Migrace z jiných pluginů

### Z jiného typografického pluginu

```php
// Dočasné vypnutí starého pluginu pro testování
add_action('plugins_loaded', function() {
    if (function_exists('old_typography_plugin')) {
        remove_all_filters('the_content', 'old_typography_function');
    }
});
```

### Migrace vlastních pravidel

```php
// Přenos vlastních regex pravidel
add_filter('pevne_mezery_regex_rules', function($rules) {
    // Stará pravidla z jiného pluginu
    $old_rules = [
        '/(\d+)\s+(EUR)/u' => '$1&nbsp;$2',
        '/\b(např)\.\s+/u' => '$1.&nbsp;'
    ];
    
    return array_merge($rules, $old_rules);
});
```

## Časté recepty

### E-shop s měnami

```php
// Rozšíření pro další měny
add_filter('pevne_mezery_regex_rules', function($rules) {
    $rules['/(\d+)\s+(EUR|USD|GBP)/u'] = '$1&nbsp;$2';
    return $rules;
});
```

### Blog s recenzemi

```php
// Zpracování hodnocení
add_filter('pevne_mezery_regex_rules', function($rules) {
    $rules['/(\d+)\s*\/\s*(\d+)\s+(hvězd|bodů)/u'] = '$1/$2&nbsp;$3';
    return $rules;
});
```

### Technický web

```php
// Technické jednotky
add_filter('pevne_mezery_regex_rules', function($rules) {
    $rules['/(\d+)\s+(MHz|GHz|GB|TB|FPS|DPI|PPI)/u'] = '$1&nbsp;$2';
    return $rules;
});
```
