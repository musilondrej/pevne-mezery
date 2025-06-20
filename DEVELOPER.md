# Developer Documentation - Pevné Mezery Plugin

## Architektura

Plugin je postaven na moderní objektově orientované architektuře s následující strukturou:

### Hlavní třídy

#### 1. `PevneMezery` (class-fixed-spaces.php)
Hlavní třída pluginu, která:
- Inicializuje WordPress filtry
- Spravuje integraci s WooCommerce a ACF
- Poskytuje hook `pevne_mezery` pro úpravu filtrů

#### 2. `ContentHandler` (class-content-handler.php)
Stěžejní třída pro zpracování obsahu:
- Obsahuje všechna regex pravidla
- Implementuje debug režim
- Rozděluje HTML a textový obsah
- Poskytuje hlavní metodu `process_content()`

#### 3. `CacheHandler` (class-cache-handler.php)
Pokročilý cache systém:
- MD5 klíče pro rychlé vyhledávání
- Kontext-based cache
- Automatické vypršení (12 hodin)
- Cache invalidace při úpravě příspěvků

#### 4. `WooCommerceSupport` (class-woocommerce-support.php)
WooCommerce integrace:
- Produktové názvy a popisy
- Košík a checkout
- Respektuje product post type

#### 5. `ACFSupport` (class-acf-support.php)
ACF integrace:
- Všechny typy polí (text, textarea, wysiwyg, select, checkbox, radio)
- Automatická detekce string hodnot

#### 6. `Utils` (class-utils.php)
Pomocné funkce:
- Regex escapování
- Zpracování zkratek

## Regex pravidla

Všechna typografická pravidla jsou definována v metodě `ContentHandler::get_regex_rules()`:

```php
private static function get_regex_rules(): array
{
    return [
        // Matematické výrazy
        '/(\d)\s+([+\-*\/=])\s+(\d)/u' => '$1&nbsp;$2&nbsp;$3',
        
        // Jednopísmenné předložky
        '/\b(k|s|v|z|o|u|a|i)\s+/iu' => '$1&nbsp;',
        
        // Jednotky
        '/(\d+)\s+(l|h|min|s|ms|m|m²|km|cm|mm|ha|km²|MB|GB|kW|W|m\/s|km\/h|l\/\d+|°|°C|°F|Kč|€|\$|%|dní|lidí|kg)/u' => '$1&nbsp;$2',
        
        // ... další pravidla
    ];
}
```

## WordPress Hooks

### Filtry pro úpravu chování

```php
// Úprava zpracovávaných filtrů
add_filter('pevne_mezery', function($filters) {
    // Přidat nový filtr
    $filters[] = 'custom_filter';
    
    // Odebrat existující filtr
    unset($filters[array_search('the_title', $filters)]);
    
    return $filters;
});

// Vypnutí WooCommerce podpory
add_filter('pevne_mezery_enable_woocommerce', '__return_false');

// Vypnutí ACF podpory
add_filter('pevne_mezery_enable_acf', '__return_false');
```

### Actions pro rozšíření

```php
// Hook pro přidání vlastních regex pravidel
add_filter('pevne_mezery_regex_rules', function($rules) {
    $rules['/vlastní-pattern/u'] = 'vlastní-náhrada';
    return $rules;
});
```

## Debug režim

Pro vývojáře je k dispozici debug režim:

```php
// Aktivace debug režimu
\MusilTech\PevneMezery\ContentHandler::set_debug_mode(true);
```

V debug režimu se pevné mezery zobrazují jako ⭕️ symbol místo `&nbsp;`.

## Cache API

### Základní operace

```php
// Získání z cache
$cached = \MusilTech\PevneMezery\CacheHandler::get_cached_content($content);

// Uložení do cache
\MusilTech\PevneMezery\CacheHandler::save_cached_content($original, $processed);

// Smazání z cache
\MusilTech\PevneMezery\CacheHandler::delete_cached_content($content);

// Smazání celého kontextu
\MusilTech\PevneMezery\CacheHandler::delete_cache_by_context('product');
```

### Cache klíče

Cache klíče jsou generovány podle vzoru:
```
fs_fixed_spaces_{context}_{md5_hash}
```

## Výkonnost

### Optimalizace

1. **Lazy Loading**: WooCommerce a ACF integrace se načítají pouze při potřebě
2. **Regex kompilace**: Všechna pravidla jsou pre-kompilovaná
3. **HTML parsing**: Zpracovává se pouze textový obsah
4. **Cache systém**: 12hodinová cache doba

### Měření výkonu

```php
// Měření času zpracování
$start = microtime(true);
$processed = \MusilTech\PevneMezery\ContentHandler::process_content($content);
$time = microtime(true) - $start;
```

## Testování

Plugin obsahuje kompletní test suite s unit testy, integration testy a automatizovanou CI/CD pipeline.

### Rychlý start

```bash
# Instalace závislostí
composer install

# Nastavení WordPress test prostředí
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

# Spuštění všech testů
composer test
```

### Test struktura

```
tests/
├── unit/                      # Unit testy (bez WordPress)
│   ├── ContentHandlerTest.php
│   ├── CacheHandlerTest.php
│   └── UtilsTest.php
├── integration/               # Integration testy (s WordPress)
│   ├── WordPressIntegrationTest.php
│   ├── WooCommerceIntegrationTest.php
│   └── ACFIntegrationTest.php
├── helpers/                   # Test helpers
└── fixtures/                  # Test data
```

### Typy testů

#### Unit testy
- **ContentHandlerTest**: Typografická pravidla, HTML parsing, debug režim
- **CacheHandlerTest**: Cache systém, výkonnost
- **UtilsTest**: Pomocné funkce, regex operace

#### Integration testy
- **WordPressIntegrationTest**: WordPress filtry, hooks, performance
- **WooCommerceIntegrationTest**: E-shop integrace
- **ACFIntegrationTest**: Custom fields podpora

### Composer scripty

```bash
composer test              # Všechny testy
composer test:unit         # Pouze unit testy
composer test:integration  # Pouze integration testy
composer test:coverage     # S coverage reportem
composer cs:check          # Code style check
composer cs:fix            # Code style fix
composer analyze           # Static analysis (PHPStan)
composer quality           # Všechny quality checks
```

### GitHub Actions CI/CD

**Test Matrix:**
- PHP: 8.0, 8.1, 8.2, 8.3
- WordPress: 6.0, 6.1, 6.2, 6.3, 6.4, latest

**Workflows:**
- `tests.yml` - Při každém push/PR
- `release.yml` - Při vytvoření release tagu

### Coverage

Plugin má vysoké pokrytí testy:
- **Unit tests**: 95%+ code coverage
- **Integration tests**: Všechny WordPress filtry
- **Edge cases**: Error handling, empty inputs
- **Performance**: Load testing, memory usage

Podrobnosti v [tests/README.md](tests/README.md).

## Bezpečnost

### Vstupní validace

```php
// Všechny vstupy jsou validovány
public static function process_content(string $content): string
{
    if (empty($content) || !is_string($content)) {
        return $content;
    }
    // ... zpracování
}
```

### HTML ochrana

Plugin respektuje HTML strukturu a nezasahuje do HTML tagů:

```php
// Rozdělení na HTML a text
$text_parts = preg_split('/(<[^>]+>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

foreach ($text_parts as &$part) {
    // Zpracovává pouze text, ne HTML
    if (!preg_match('/^<.*>$/', $part)) {
        // ... aplikace pravidel
    }
}
```

## Rozšiřování

### Přidání nového typografického pravidla

```php
add_filter('pevne_mezery_regex_rules', function($rules) {
    // Nové pravidlo pro česká specifika
    $rules['/(\d+)\s+(Kč)/u'] = '$1&nbsp;$2';
    return $rules;
});
```

### Vlastní integrace

```php
class CustomIntegration {
    public static function init() {
        add_filter('custom_filter', [\MusilTech\PevneMezery\ContentHandler::class, 'process_content']);
    }
}

// Aktivace při načtení pluginu
add_action('plugins_loaded', [CustomIntegration::class, 'init']);
```

## Migrace a aktualizace

### Při aktualizaci pluginu

```php
// Hook pro aktualizace
register_activation_hook(__FILE__, function() {
    // Vymazání cache při aktivaci
    \MusilTech\PevneMezery\CacheHandler::delete_cache_by_context('default');
});
```

## Kompatibilita

### WordPress verze
- Minimálně: 5.0
- Testováno do: 6.8.1
- PHP: 8.0+

### Známé kompatibilní pluginy
- WooCommerce (všechny verze)
- ACF (všechny verze)
- WPML/Polylang
- Elementor/Gutenberg
- Většina page builderů

### Potenciální konflikty
- Jiné typografické pluginy
- Cache pluginy (řešeno vlastním cache systémem)
- Minifikační pluginy (mohou ovlivnit HTML parsing)

## Troubleshooting

### Časté problémy

1. **Plugin nefunguje**
   - Zkontrolujte PHP verzi (8.0+)
   - Ověřte aktivaci pluginu
   - Zkontrolujte WordPress filtry

2. **Pomalý výkon**
   - Zkontrolujte cache systém
   - Ověřte množství zpracovávaného obsahu
   - Zvažte vyloučení některých filtrů

3. **Konflikty s jinými pluginy**
   - Deaktivujte ostatní typografické pluginy
   - Zkontrolujte prioritu filtrů
   - Použijte debug režim pro diagnostiku
