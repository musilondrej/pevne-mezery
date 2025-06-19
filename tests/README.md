# Testing Documentation

## Přehled testů

Plugin Pevné Mezery obsahuje kompletní test suite s unit testy, integration testy a automatizovanou CI/CD pipeline.

## Struktura testů

```
tests/
├── bootstrap.php              # PHPUnit bootstrap
├── helpers/
│   └── class-test-helper.php  # Pomocné funkce pro testy
├── unit/                      # Unit testy
│   ├── ContentHandlerTest.php
│   ├── CacheHandlerTest.php
│   └── UtilsTest.php
├── integration/               # Integration testy
│   ├── WordPressIntegrationTest.php
│   ├── WooCommerceIntegrationTest.php
│   └── ACFIntegrationTest.php
├── fixtures/                  # Test data
│   └── sample-data.php
└── phpstan-bootstrap.php      # PHPStan bootstrap
```

## Spuštění testů

### Lokální prostředí

1. **Instalace závislostí:**
   ```bash
   composer install
   ```

2. **Nastavení WordPress test prostředí:**
   ```bash
   # MySQL databáze (doporučeno)
   bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
   
   # SQLite (alternativa)
   bash bin/install-wp-tests.sh wordpress_test '' '' localhost latest true
   ```

3. **Spuštění všech testů:**
   ```bash
   composer test
   ```

4. **Spuštění konkrétních test suite:**
   ```bash
   # Pouze unit testy
   composer test:unit
   
   # Pouze integration testy
   composer test:integration
   
   # S coverage reportem
   composer test:coverage
   ```

### GitHub Actions

Testy se automaticky spouští při:
- Push do `main` nebo `develop` větve
- Pull requestech do `main`
- Vytvoření release tagu

**Test matice:**
- PHP verze: 8.0, 8.1, 8.2, 8.3
- WordPress verze: 6.0, 6.1, 6.2, 6.3, 6.4, latest

## Unit testy

### ContentHandlerTest

Testuje hlavní logiku zpracování typografických pravidel:

- ✅ Jednopísmenné předložky
- ✅ Jednotky a měrné jednotky
- ✅ Tituly a akademické hodnosti
- ✅ Zkratky
- ✅ Matematické výrazy
- ✅ Pomlčky a speciální znaky
- ✅ HTML preservation
- ✅ Debug režim
- ✅ Výkonnostní testy
- ✅ Edge cases

**Příklad testu:**
```php
public function test_single_letter_prepositions(): void {
    $test_data = [
        'k domu' => 'k&nbsp;domu',
        's přáteli' => 's&nbsp;přáteli',
        'v práci' => 'v&nbsp;práci'
    ];
    
    foreach ($test_data as $input => $expected) {
        $result = ContentHandler::process_content($input);
        $this->assertEquals($expected, $result);
    }
}
```

### CacheHandlerTest

Testuje cache systém:

- ✅ Ukládání a načítání z cache
- ✅ Generování cache klíčů
- ✅ Mazání cache
- ✅ Výkonnost cache operací
- ✅ Cache s velkým obsahem
- ✅ Speciální znaky v cache

### UtilsTest

Testuje pomocné funkce:

- ✅ Regex escapování
- ✅ Zpracování zkratek
- ✅ Word boundaries
- ✅ Výkonnost

## Integration testy

### WordPressIntegrationTest

Testuje integraci s WordPress:

- ✅ `the_title` filtr
- ✅ `the_content` filtr
- ✅ `the_excerpt` filtr
- ✅ Comment filtry
- ✅ Widget filtry
- ✅ Bloginfo filtry
- ✅ Customizace filtrů
- ✅ Cache integrace
- ✅ Výkonnost s více příspěvky

**Příklad integration testu:**
```php
public function test_the_title_filter(): void {
    $post_id = TestHelper::create_test_post([
        'post_title' => 'Test k domu s 10 kg'
    ]);
    
    $title = get_the_title($post_id);
    
    $this->assertStringContains('k&nbsp;domu', $title);
    $this->assertStringContains('10&nbsp;kg', $title);
}
```

### WooCommerceIntegrationTest

Testuje WooCommerce integraci:

- ✅ Product titles
- ✅ Product descriptions
- ✅ Cart item names
- ✅ Checkout integration
- ✅ Enable/disable filters
- ✅ HTML preservation
- ✅ Výkonnost

### ACFIntegrationTest

Testuje ACF integraci:

- ✅ Text fields
- ✅ Textarea fields
- ✅ WYSIWYG fields
- ✅ Select fields
- ✅ Checkbox fields
- ✅ Radio fields
- ✅ Non-string values
- ✅ Empty values
- ✅ Enable/disable filters

## Test pomocníci

### TestHelper třída

Poskytuje společné funkce pro testy:

```php
// Test data
$data = TestHelper::get_typography_test_data();
$woo_data = TestHelper::get_woocommerce_test_data();
$acf_data = TestHelper::get_acf_test_data();

// Mock objekty
$post_id = TestHelper::create_test_post();
$product_id = TestHelper::create_test_product();

// Debug režim
TestHelper::enable_debug_mode();
TestHelper::disable_debug_mode();

// Cleanup
TestHelper::cleanup_test_data();
```

## Code Quality

### PHP CodeSniffer

```bash
# Kontrola kódovacích standardů
composer cs:check

# Automatické opravy
composer cs:fix
```

**Standardy:**
- WordPress Coding Standards
- PSR-1, PSR-2 (částečně)
- Custom rules pro plugin

### PHPStan

```bash
# Statická analýza (level 5)
composer analyze

# Strict analýza (level max)
composer analyze:strict
```

**Kontroluje:**
- Type hints
- Unused variables
- Dead code
- WordPress specific issues

### Všechny quality checks najednou

```bash
composer quality
```

## CI/CD Pipeline

### Tests Workflow

Spouští se při každém push/PR:

1. **Matrix testing** - PHP 8.0-8.3 × WordPress 6.0-latest
2. **Unit tests** - Rychlé testy bez WordPress
3. **Integration tests** - S WordPress test prostředím
4. **Coverage report** - Pro PHP 8.1 + WordPress latest
5. **Code quality** - PHPCS + PHPStan
6. **Security audit** - Composer audit

### Release Workflow

Spouští se při vytvoření tagu:

1. **Kompletní testy** - Všechny kombinace
2. **Build** - Vytvoření release balíčku
3. **GitHub Release** - Automatické vydání

## Psaní nových testů

### Unit test template

```php
<?php
namespace MusilTech\PevneMezery\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MusilTech\PevneMezery\YourClass;

class YourClassTest extends TestCase {
    
    protected function setUp(): void {
        parent::setUp();
        // Setup před každým testem
    }
    
    public function test_your_functionality(): void {
        // Arrange
        $input = 'test input';
        $expected = 'expected output';
        
        // Act
        $result = YourClass::method($input);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
}
```

### Integration test template

```php
<?php
namespace MusilTech\PevneMezery\Tests\Integration;

use WP_UnitTestCase;
use MusilTech\PevneMezery\Tests\TestHelper;

class YourIntegrationTest extends WP_UnitTestCase {
    
    protected function setUp(): void {
        parent::setUp();
        TestHelper::disable_debug_mode();
    }
    
    protected function tearDown(): void {
        TestHelper::cleanup_test_data();
        parent::tearDown();
    }
    
    public function test_wordpress_integration(): void {
        $post_id = TestHelper::create_test_post([
            'post_title' => 'Test k domu'
        ]);
        
        $title = get_the_title($post_id);
        
        $this->assertStringContains('k&nbsp;domu', $title);
    }
}
```

## Debugging testů

### Verbose output

```bash
# Detailní výstup
phpunit --testdox --verbose

# Pouze failed testy
phpunit --stop-on-failure

# Konkrétní test
phpunit --filter test_method_name
```

### Debug režim

```php
// V testu
TestHelper::enable_debug_mode();
$result = ContentHandler::process_content('k domu');
// $result bude obsahovat ⭕️ místo &nbsp;
```

### Coverage analýza

```bash
# HTML report
composer test:coverage
open tests/coverage-html/index.html

# Text report
phpunit --coverage-text
```

## Best Practices

### ✅ Dobré praktiky

- **Arrange-Act-Assert** pattern
- **Descriptive test names** - `test_processes_single_letter_prepositions`
- **Test isolation** - každý test je nezávislý
- **Data providers** pro multiple test cases
- **Cleanup** v tearDown metodách
- **Edge cases** - prázdné vstupy, chyby
- **Performance tests** pro critical paths

### ❌ Co se vyhnout

- **Testing implementation details** místo behavior
- **Složité test setup** - použijte helpers
- **Hardcoded values** - použijte fixtures
- **Ignorování failed testů**
- **Missing assertions** - každý test musí něco testovat

## Troubleshooting

### Časté problémy

1. **MySQL connection failed**
   ```bash
   # Zkuste SQLite
   bash bin/install-wp-tests.sh wordpress_test '' '' localhost latest true
   ```

2. **Class not found errors**
   ```bash
   # Přegenerujte autoloader
   composer dump-autoload
   ```

3. **WordPress functions not available**
   ```bash
   # Zkontrolujte bootstrap
   export WP_TESTS_DIR=/tmp/wordpress-tests-lib
   ```

4. **Memory limit exceeded**
   ```bash
   # Zvyšte limit
   php -d memory_limit=512M vendor/bin/phpunit
   ```

### Debug informace

```bash
# Informace o prostředí
composer show --platform
php --version
mysql --version

# WordPress test prostředí
ls -la /tmp/wordpress-tests-lib/
cat /tmp/wordpress-tests-lib/wp-tests-config.php
```
