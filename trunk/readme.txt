=== Pevné mezery ===
Contributors: musilondrej
Donate link: https://musiltech.com
Tags: pevné mezery, pevná mezera, nezalomitlená mezera, nedělitelná mezera, czech typography, typografie, woocommerce, acf
Requires at least: 5.0
Tested up to: 6.8.1
Stable tag: 1.3.1
Requires PHP: 8.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Profesionální plugin pro automatické aplikování českých typografických pravidel s pokročilým cache systémem, WooCommerce a ACF integrací.

== Description ==

Plugin **Pevné mezery** je profesionální nástroj pro automatické aplikování českých typografických pravidel na WordPress weby. Plugin inteligentně zpracovává text a vkládá pevné mezery (non-breaking spaces) tam, kde jsou potřeba podle českých typografických norem.

= Klíčové funkce =

* **Automatické typografické zpracování** - Plugin automaticky aplikuje česká typografická pravidla
* **Inteligentní cache systém** - Vysoký výkon díky pokročilému cache mechanismu
* **WooCommerce integrace** - Kompletní podpora pro e-shop obsah
* **ACF podpora** - Zpracování všech ACF polí (text, textarea, wysiwyg, select, checkbox, radio)
* **Matematické výrazy** - Správné formátování matematických operací
* **Pokročilá regex pravidla** - Přes 15 různých typografických pravidel
* **Debug režim** - Pro vývojáře s vizuálním označením pevných mezer
* **Filtrování obsahu** - Možnost vypnutí pro konkrétní části webu

= Typografická pravidla =

Plugin automaticky aplikuje následující pravidla:

* **Jednopísmenné předložky:** k, s, v, z, o, u, a, i + mezera → pevná mezera
* **Matematické výrazy:** Číslice + operátor + číslice s pevnými mezerami
* **Jednotky:** Automatické spojení čísel s jednotkami (kg, m, °C, Kč, %, atd.)
* **Tituly:** JUDr., Ph.D, Mgr., Bc., Ing., prof., doc. a další
* **Zkratky:** např., atd., apod., tj., tzn., tzv., resp. a další
* **Speciální znaky:** Pomlčky, §, tři tečky, úhlové stupně

= WooCommerce integrace =

* Názvy produktů (respektuje product post type)
* Krátké a dlouhé popisy produktů
* Recenze produktů
* Názvy položek v košíku
* Přehled objednávky

= ACF podpora =

* Text pole (type=text)
* Textarea (type=textarea)
* WYSIWYG editor (type=wysiwyg)
* Select (type=select)
* Checkbox (type=checkbox)
* Radio (type=radio)

= Výkon a optimalizace =

* **Inteligentní cache** - zpracovaný obsah se cachuje na 12 hodin
* **Lazy loading** - WooCommerce a ACF integrace se načítají pouze pokud jsou potřeba
* **HTML parsing** - plugin zpracovává pouze textový obsah, respektuje HTML strukturu

== Installation ==

1. Nahrajte soubory pluginu do adresáře `/wp-content/plugins/pevne-mezery/`
2. Aktivujte plugin přes menu 'Pluginy' ve WordPressu
3. Plugin začne automaticky aplikovat typografická pravidla

== Frequently Asked Questions ==

= Ovlivňuje plugin výkon webu? =

Ne, plugin používá pokročilý cache systém. Každý text se zpracuje pouze jednou a pak se cachuje na 12 hodin.

= Mohu vypnout plugin pro konkrétní části webu? =

Ano, můžete použít WordPress filtry:

`add_filter('pevne_mezery_enable_woocommerce', '__return_false');`
`add_filter('pevne_mezery_enable_acf', '__return_false');`

= Podporuje plugin jiné jazyky než češtinu? =

Plugin je optimalizován pro česká typografická pravidla, ale regex vzory lze upravit pro jiné jazyky.

= Je plugin kompatibilní s page buildery? =

Ano, plugin pracuje na úrovni WordPress filtrů a je kompatibilní s většinou page builderů.

== Screenshots ==

1. Před a po aplikování pevných mezer
2. WooCommerce produkty s správnou typografií
3. Debug režim pro vývojáře

== Changelog ==

= 1.3.1 =
* Přidána podpora českého jazyka
* Vylepšena struktura pluginu pro překlady
* Aktualizováno značení z BitSpecter na MusilTech
* Vylepšen cache systém
* Rozšířena WooCommerce integrace
* Přidána podpora pro více ACF typů polí

= 1.3 =
* První stabilní verze
* Základní typografická pravidla
* WordPress filtry integrace

== Upgrade Notice ==

= 1.3.1 =
Doporučená aktualizace s vylepšeným výkonem a rozšířenou funkcionalitou pro WooCommerce a ACF.