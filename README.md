# Plugin Pevné Mezery pro WordPress

## Popis

Plugin **Pevné Mezery** je navržen tak, aby automaticky aplikoval typografická pravidla pro pevné mezery podle české normy. Tento WordPress plugin zabraňuje nesprávnému zalamování textu, například po jednopísmenných předložkách a spojkách, mezi čísly a jednotkami, nebo kolem titulů a speciálních symbolů.

Plugin automaticky zpracovává obsah webu a vkládá pevné mezery tam, kde je to potřeba, čímž zvyšuje čitelnost a estetickou kvalitu textu na vašich stránkách.

### Funkce

- Automatické přidávání pevných mezer po jednopísmenných předložkách a spojkách.
- Zajištění správného zobrazení čísel a jednotek (např. 10 kg).
- Správné formátování zkratek, titulů a speciálních symbolů.
- Podpora pro WooCommerce a ACF (Advanced Custom Fields).
- Podpora pro specifické WordPress filtry, včetně `the_title`, `the_content`, `comment_text`, a dalších.

### Instalace

1. Nahrajte složku pluginu `pevne-mezery` do adresáře `/wp-content/plugins/` nebo jej nainstalujte přímo přes stránku pluginů ve WordPressu.
2. Aktivujte plugin na stránce **Pluginy** ve WordPressu.
3. Plugin začne automaticky aplikovat pravidla pro pevné mezery na různé části obsahu vašeho webu.

### Použití

Plugin bude automaticky aplikovat pravidla pro pevné mezery na:

- Názvy příspěvků a stránek (`the_title`).
- Obsah příspěvků a stránek (`the_content`).
- Výpisy kategorií (`list_cats`).
- Text komentářů (`comment_text`).
- Výpis autorů komentářů (`comment_author`).
- Popisy kategorií a štítků (`term_description`).
- Názvy kategorií a štítků (`term_name`).
- Popisy odkazů v blogrollu (`link_description`).
- Poznámky k odkazům v blogrollu (`link_notes`).
- Názvy odkazů v blogrollu (`link_name`).
- Výpis názvu webu a dalších informací (`bloginfo`).
- Názvy widgetů (`widget_title`).
- Výpis titulků stránky (`wp_title`).
- Výpis titulu jednotlivého příspěvku (`single_post_title`).
- Výpis výňatků z příspěvků (`the_excerpt`).

Pokud používáte WooCommerce nebo ACF, plugin poskytuje specifickou podporu pro správné formátování textu.

### Podporované ACF fieldy:
- Text (text)
- Textarea (textarea)
- WYSIWYG editor (wysiwyg)




### Jak mohu odebrat aplikaci pevných mezer z konkrétních částí webu?

Pokud nechcete, aby plugin aplikoval pevné mezery na některé části webu, můžete upravit seznam filtrů pomocí `add_filter`. Například, pokud nechcete, aby se pevné mezery aplikovaly na názvy příspěvků, můžete přidat následující kód do souboru `functions.php` vaší šablony:

```php
add_filter('pevne_mezery_filtry', 'remove_title_from_pevne_mezery');

function remove_title_from_pevne_mezery(array $filters) {
    unset($filters['the_title']);
    return $filters;
}
```

### Deaktivace WooCommerce podpory:
```php
add_filter('pevne_mezery_enable_woocommerce', '__return_false');
```

### Deaktivace ACF podpory:
```php
add_filter('pevne_mezery_enable_acf', '__return_false');
```

### Podpora

Pokud narazíte na jakýkoli problém s pluginem, neváhejte kontaktovat podporu nebo vytvořit issue na GitHubu.

### Časté dotazy

#### Jak plugin aplikuje pevné mezery?

Plugin používá filtrovací funkce WordPressu, aby aplikoval pevné mezery na různé typy obsahu, jako jsou názvy příspěvků, texty komentářů a další.

#### Můžu si přizpůsobit pravidla pro pevné mezery?

Ano, plugin umožňuje přizpůsobit pravidla pomocí WordPress filtrů. Můžete například odebrat aplikaci pevné mezery na některé části webu.

#### Podporuje plugin jiný jazyk než češtinu?

Plugin je navržen primárně pro český jazyk a typografická pravidla, ale může fungovat i pro jiné jazyky s podobnými pravidly.