# Changelog

Všechny významné změny tohoto projektu budou zdokumentovány v tomto souboru.

Formát je založen na [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
a tento projekt dodržuje [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.1] - 2025-06-19

### Přidáno
- 🌍 **Lokalizace**: Podpora českého jazyka s překladovými soubory (.po, .mo, .pot)
- 📚 **Kompletní dokumentace**: README.md, DEVELOPER.md, EXAMPLES.md
- 🔍 **Debug režim**: Vizuální označení pevných mezer pro vývojáře
- 🏪 **Rozšířená WooCommerce integrace**: 
  - Názvy produktů (respektuje product post type)
  - Krátké a dlouhé popisy produktů
  - Recenze produktů
  - Názvy položek v košíku
  - Přehled objednávky
- 🎛️ **Rozšířená ACF podpora**:
  - Text pole (type=text)
  - Textarea (type=textarea)
  - WYSIWYG editor (type=wysiwyg)
  - Select (type=select)
  - Checkbox (type=checkbox)
  - Radio (type=radio)
- ⚡ **Pokročilý cache systém**:
  - MD5 klíče pro rychlé vyhledávání
  - Kontext-based cache
  - 12hodinová cache doba
  - Automatická invalidace
- 🧮 **Matematické výrazy**: Pevné mezery kolem operátorů (+, -, *, /, =)
- 🎯 **Pokročilá regex pravidla**:
  - Pomlčky s pevnými mezerami
  - Tři tečky (…) s správným formátováním
  - Úhlové stupně, minuty, vteřiny
  - Paragrafový symbol (§)
  - Exponenty a indexy
- 🛠️ **WordPress filtry**:
  - `pevne_mezery` - úprava zpracovávaných filtrů
  - `pevne_mezery_enable_woocommerce` - vypnutí WooCommerce podpory
  - `pevne_mezery_enable_acf` - vypnutí ACF podpory

### Změněno
- 🏢 **Rebrandování**: Aktualizace z BitSpecter na MusilTech
- 🏗️ **Architektura**: Kompletní refaktoring na objektově orientovanou strukturu
- 📦 **Namespace**: Všechny třídy nyní používají `MusilTech\PevneMezery`
- 🚀 **Výkon**: Lazy loading pro WooCommerce a ACF integrace
- 📝 **Kód**: Lepší struktura, dokumentace a type hints
- 🔧 **Konfigurace**: Centralizované nastavení přes WordPress filtry

### Vylepšeno
- 🎨 **HTML parsing**: Plugin nyní respektuje HTML strukturu a zpracovává pouze textový obsah
- 📊 **Výkon**: Významně vylepšený výkon díky cache systému
- 🔒 **Bezpečnost**: Lepší validace vstupů a ochrana před XSS
- 🧪 **Testovatelnost**: Struktura připravená pro unit a integration testy
- 📖 **Dokumentace**: Kompletní dokumentace pro uživatele i vývojáře

### Opraveno
- 🐛 **Regex konflikty**: Vyřešeny konflikty mezi různými pravidly
- 💾 **Cache problémy**: Stabilní cache systém bez memory leaks
- 🔄 **WordPress kompatibilita**: Lepší integrace s WordPress filtry
- 🌐 **Multisite podpora**: Funkčnost na multisite instalacích

## [1.3] - 2024-XX-XX

### Přidáno
- 🎉 **První veřejná verze**
- ✨ **Základní typografická pravidla**:
  - Jednopísmenné předložky a spojky
  - Jednotky a měrné jednotky
  - Tituly a akademické hodnosti
  - Zkratky
- 🔌 **WordPress integrace**: Základní filtry pro `the_title`, `the_content`, `comment_text`
- 🛍️ **WooCommerce podpora**: Základní integrace
- 🎛️ **ACF podpora**: Základní integrace pro text pole

### Technické detaily

#### Nové třídy v 1.3.1
```
├── PevneMezery              # Hlavní třída (dříve přímo v main file)
├── ContentHandler           # Zpracování obsahu + regex pravidla  
├── CacheHandler            # Pokročilý cache systém
├── Utils                   # Pomocné funkce
├── WooCommerceSupport      # WooCommerce integrace
└── ACFSupport              # ACF integrace
```

#### Cache systém
- **Klíče**: `fs_fixed_spaces_{context}_{md5_hash}`
- **Doba**: 12 hodin (`12 * HOUR_IN_SECONDS`)
- **Storage**: WordPress transients
- **Invalidace**: Automatická při úpravě příspěvků

#### Regex pravidla (1.3.1)
- Matematické výrazy: `/(\d)\s+([+\-*\/=])\s+(\d)/u`
- Jednopísmenné předložky: `/\b(k|s|v|z|o|u|a|i)\s+/iu`
- Jednotky: `/(\d+)\s+(l|h|min|s|ms|m|m²|km|cm|mm|ha|km²|MB|GB|kW|W|m\/s|km\/h|l\/\d+|°|°C|°F|Kč|€|\$|%|dní|lidí|kg)/u`
- Tituly: `/\b(JUDr|Ph\.D|LL\.B|MUDr|Mgr|Bc|Ing|CSc|...)\.\s+/u`
- Zkratky: `/\b(např|atd|apod|tj|tzn|tzv|...)\.\s+/u`
- Pomlčky: `/\s*–\s*/u`
- Tři tečky: `/(\S)\s*\.{3}/u`
- Paragraf: `/\s+§\s+/u`

#### Filtry WordPress
```php
// Výchozí filtry
$filters = [
    'comment_author', 'term_name', 'link_name', 'link_description',
    'link_notes', 'bloginfo', 'wp_title', 'widget_title', 
    'term_description', 'the_title', 'the_content', 'the_excerpt',
    'comment_text', 'single_post_title', 'list_cats'
];
```

#### WooCommerce hooks (1.3.1)
```php
// WooCommerce specific hooks
add_filter('the_title', [self::class, 'process_product_title'], 10, 2);
add_filter('woocommerce_short_description', [self::class, 'process_short_description']);
add_filter('woocommerce_product_description', [self::class, 'process_long_description']);
add_filter('woocommerce_product_reviews', [self::class, 'process_reviews']);
add_filter('woocommerce_cart_item_name', [self::class, 'process_cart_item_names'], 10, 3);
```

#### ACF hooks (1.3.1)
```php
// ACF field type support
add_filter('acf/format_value/type=text', [self::class, 'process_acf_content'], 10, 3);
add_filter('acf/format_value/type=textarea', [self::class, 'process_acf_content'], 10, 3);
add_filter('acf/format_value/type=wysiwyg', [self::class, 'process_acf_content'], 10, 3);
add_filter('acf/format_value/type=select', [self::class, 'process_acf_content'], 10, 3);
add_filter('acf/format_value/type=checkbox', [self::class, 'process_acf_content'], 10, 3);
add_filter('acf/format_value/type=radio', [self::class, 'process_acf_content'], 10, 3);
```

---

## Plánované změny

### [1.4.0] - Budoucí verze
- 🌐 **Více jazyků**: Podpora slovenštiny a dalších
- 🎨 **Admin rozhraní**: GUI pro nastavení pravidel
- 📊 **Statistiky**: Sledování použití a výkonu
- 🔌 **API**: REST API pro externí integrace
- 🧪 **Testování**: Kompletní test suite
- 📱 **Gutenberg**: Lepší integrace s moderním editorem

### Roadmapa
- **Q3 2025**: Admin rozhraní + více jazyků
- **Q4 2025**: API + statistiky  
- **Q1 2026**: Mobile optimalizace + Gutenberg integrace

---

## Poznámky k verzím

### Kompatibilita
- **WordPress**: 5.0+ (testováno do 6.8.1)
- **PHP**: 8.0+ (doporučeno 8.1+)
- **WooCommerce**: Všechny podporované verze WP
- **ACF**: Všechny podporované verze WP

### Migrace
- **1.3 → 1.3.1**: Automatická, žádné zlomové změny
- **Future**: Vždy bude zpětně kompatibilní v rámci major verze

### Licence
- **GPL-2.0+**: Svobodný software
- **Komerční použití**: Povoleno
- **Modifikace**: Povoleny při zachování licence
