# Plugin Pevné Mezery pro WordPress

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-5.0%2B-blue)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-purple)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL--2.0%2B-green)](http://www.gnu.org/licenses/gpl-2.0.txt)

## Popis

Plugin **Pevné Mezery** je profesionální nástroj pro automatické aplikování českých typografických pravidel na WordPress weby. Plugin inteligentně zpracovává text a vkládá pevné mezery (non-breaking spaces) tam, kde jsou potřeba podle českých typografických norem.

### 🔥 Klíčové funkce

- **Automatické typografické zpracování** - Plugin automaticky aplikuje česká typografická pravidla
- **Inteligentní cache systém** - Vysoký výkon díky pokročilému cache mechanismu
- **WooCommerce integrace** - Kompletní podpora pro e-shop obsah
- **ACF podpora** - Zpracování všech ACF polí (text, textarea, wysiwyg, select, checkbox, radio)
- **Matematické výrazy** - Správné formátování matematických operací
- **Pokročilá regex pravidla** - Přes 15 různých typografických pravidel
- **Debug režim** - Pro vývojáře s vizuálním označením pevných mezer
- **Filtrování obsahu** - Možnost vypnutí pro konkrétní části webuMezery pro WordPress

## Popis

Plugin **Pevné Mezery** je navržen tak, aby automaticky aplikoval typografická pravidla pro pevné mezery podle české normy. Tento WordPress plugin zabraňuje nesprávnému zalamování textu, například po jednopísmenných předložkách a spojkách, mezi čísly a jednotkami, nebo kolem titulů a speciálních symbolů.

Plugin automaticky zpracovává obsah webu a vkládá pevné mezery tam, kde je to potřeba, čímž zvyšuje čitelnost a estetickou kvalitu textu na vašich stránkách.

### Funkce

- Automatické přidávání pevných mezer po jednopísmenných předložkách a spojkách.
- Zajištění správného zobrazení čísel a jednotek (např. 10 kg).
- Správné formátování zkratek, titulů a speciálních symbolů.
- Podpora pro WooCommerce a ACF (Advanced Custom Fields).
- Podpora pro specifické WordPress filtry, včetně `the_title`, `the_content`, `comment_text`, a dalších.

### 📝 Typografická pravidla

Plugin automaticky aplikuje následující pravidla:

#### Jednopísmenné předložky a spojky
- `k`, `s`, `v`, `z`, `o`, `u`, `a`, `i` + mezera → pevná mezera
- Příklad: `k tomu` → `k&nbsp;tomu`

#### Matematické výrazy
- Číslice + operátor + číslice s pevnými mezerami
- Příklad: `5 + 3 = 8` → `5&nbsp;+&nbsp;3&nbsp;=&nbsp;8`

#### Jednotky a měrné jednotky
- Automatické spojení čísel s jednotkami
- Podporované: `l`, `h`, `min`, `s`, `ms`, `m`, `m²`, `km`, `cm`, `mm`, `ha`, `km²`, `MB`, `GB`, `kW`, `W`, `m/s`, `km/h`, `°`, `°C`, `°F`, `Kč`, `€`, `$`, `%`, `kg`, `dní`, `lidí`
- Příklad: `10 kg` → `10&nbsp;kg`

#### Tituly a akademické hodnosti
- `JUDr.`, `Ph.D`, `Mgr.`, `Bc.`, `Ing.`, `prof.`, `doc.` a další
- Příklad: `Mgr. Novák` → `Mgr.&nbsp;Novák`

#### Zkratky
- `např.`, `atd.`, `apod.`, `tj.`, `tzn.`, `tzv.`, `resp.` a další
- Příklad: `např. takto` → `např.&nbsp;takto`

#### Pomlčky a speciální znaky
- Automatické formátování pomlček s pevnými mezerami
- Podpora pro `§`, tři tečky (`…`), úhlové stupně
- Příklad: `10 – 20` → `10&nbsp;–&nbsp;20`

### 🚀 Instalace

1. **Ruční instalace:**
   ```bash
   cd /wp-content/plugins/
   git clone https://github.com/musilondrej/pevne-mezery.git
   ```

2. **WordPress admin:**
   - Nahrajte ZIP soubor přes **Pluginy** → **Přidat nový** → **Nahrát plugin**
   - Aktivujte plugin na stránce **Pluginy**

3. **Composer (pro vývojáře):**
   ```bash
   composer require musiltech/pevne-mezery
   ```

### ⚙️ Použití

Plugin funguje automaticky po aktivaci. Zpracovává následující WordPress hooks:

- **Obsah příspěvků a stránek:** `the_title`, `the_content`, `the_excerpt`
- **Komentáře:** `comment_text`, `comment_author`
- **Kategorie a štítky:** `term_description`, `term_name`, `list_cats`
- **Odkazy:** `link_description`, `link_notes`, `link_name`
- **WordPress systém:** `bloginfo`, `wp_title`, `widget_title`, `single_post_title`

### 🛍️ WooCommerce integrace

Plugin poskytuje kompletní podporu pro WooCommerce:

```php
// Automaticky zpracovává:
- Názvy produktů (respektuje product post type)
- Krátké popisy produktů (woocommerce_short_description)
- Dlouhé popisy produktů (woocommerce_product_description)
- Recenze produktů (woocommerce_product_reviews)
- Názvy položek v košíku (woocommerce_cart_item_name)
- Přehled objednávky (woocommerce_checkout_order_review)
```

### 🔧 ACF (Advanced Custom Fields) podpora

Plugin automaticky zpracovává všechny typy ACF polí:

- **Text pole** (`type=text`)
- **Textarea** (`type=textarea`) 
- **WYSIWYG editor** (`type=wysiwyg`)
- **Select** (`type=select`)
- **Checkbox** (`type=checkbox`)
- **Radio** (`type=radio`)

### 💾 Cache systém

Plugin využívá pokročilý cache systém pro vysoký výkon:

- **Automatické cachování** zpracovaného obsahu
- **Cache doba:** 12 hodin (`12 * HOUR_IN_SECONDS`)
- **Inteligentní klíče** založené na MD5 hash obsahu
- **Automatické mazání** při úpravě příspěvků
- **Context-based cache** pro různé typy obsahu

### 🛠️ Pokročilé nastavení

#### Vypnutí pro konkrétní filtry

```php
// Vypnutí pro názvy příspěvků
add_filter('pevne_mezery', function($filters) {
    unset($filters[array_search('the_title', $filters)]);
    return $filters;
});
```

#### Vypnutí WooCommerce podpory

```php
add_filter('pevne_mezery_enable_woocommerce', '__return_false');
```

#### Vypnutí ACF podpory

```php
add_filter('pevne_mezery_enable_acf', '__return_false');
```

#### Debug režim pro vývojáře

```php
// Aktivuje vizuální označení pevných mezer symbolem ⭕️
add_action('init', function() {
    if (WP_DEBUG) {
        \MusilTech\PevneMezery\ContentHandler::set_debug_mode(true);
    }
});
```

### 🏗️ Architektura pluginu

Plugin je postavený na moderní objektově orientované architektuře:

```
├── pevne-mezery.php           # Hlavní soubor pluginu
├── includes/
│   ├── class-fixed-spaces.php     # Hlavní třída s filtry
│   ├── class-content-handler.php  # Zpracování obsahu + regex pravidla
│   ├── class-cache-handler.php    # Cache systém
│   └── class-utils.php             # Pomocné funkce
├── integrations/
│   ├── class-acf-support.php       # ACF integrace
│   └── class-woocommerce-support.php # WooCommerce integrace
└── languages/                      # Překladové soubory
```

### 🐛 Debug a vývoj

Pro vývojáře plugin nabízí debug možnosti:

```php
// Zapnutí debug režimu
\MusilTech\PevneMezery\ContentHandler::set_debug_mode(true);

// Mazání cache pro konkrétní kontext
\MusilTech\PevneMezery\CacheHandler::delete_cache_by_context('product');

// Ruční zpracování textu
$processed = \MusilTech\PevneMezery\ContentHandler::process_content($text);
```

### 📊 Výkon a optimalizace

- **Inteligentní cache** - zpracovaný obsah se cachuje na 12 hodin
- **Lazy loading** - WooCommerce a ACF integrace se načítají pouze pokud jsou potřeba
- **Regex optimalizace** - všechna pravidla jsou předkompilovaná
- **HTML parsing** - plugin zpracovává pouze textový obsah, respektuje HTML strukturu

### 🤝 Kompatibilita

- **WordPress:** 5.0+
- **PHP:** 8.0+
- **WooCommerce:** Automatická detekce
- **ACF:** Automatická detekce
- **Multisite:** Plně podporováno
- **Překladové soubory:** Připraveno pro lokalizaci

### 📚 Příklady použití

#### Základní použití
Plugin funguje automaticky, ale můžete ho rozšířit:

```php
// Přidání vlastních pravidel
add_filter('pevne_mezery_regex_rules', function($rules) {
    $rules['/vlastní-pattern/u'] = 'vlastní-náhrada';
    return $rules;
});
```

#### Zpracování vlastního obsahu
```php
$text = "Mgr. Novák má 25 kg.";
$processed = \MusilTech\PevneMezery\ContentHandler::process_content($text);
// Výsledek: "Mgr.&nbsp;Novák má 25&nbsp;kg."
```

### ❓ Časté dotazy

#### Ovlivňuje plugin výkon webu?
Ne, plugin používá pokročilý cache systém. Každý text se zpracuje pouze jednou a pak se cachuje na 12 hodin.

#### Mohu plugin upravit pro jiný jazyk?
Ano, všechna regex pravidla jsou v metodě `get_regex_rules()` ve třídě `ContentHandler`.

#### Podporuje plugin WPML/Polylang?
Ano, plugin respektuje WordPress filtry a je kompatibilní s většinou překladových pluginů.

#### Jak vypnout plugin pro konkrétní stránku?
```php
// V template souboru konkrétní stránky
remove_filter('the_content', [\MusilTech\PevneMezery\ContentHandler::class, 'process_content']);
```

### 🔗 Odkazy

- **GitHub repozitář:** https://github.com/musilondrej/pevne-mezery
- **Autor:** [MusilTech](https://musiltech.com)
- **Podpora:** Vytvořte issue na GitHubu
- **Licence:** GPL-2.0+

### 🏆 Přispěvatelé

- **Ondřej Musil** - Hlavní vývojář ([MusilTech](https://musiltech.com))

### 📄 Licence

Tento plugin je licencován pod GPL-2.0+ licencí. Více informací najdete v souboru LICENSE.

---

**Vytvořeno s ❤️ pro českou typografii**