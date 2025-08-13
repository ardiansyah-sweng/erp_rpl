# PANDUAN INSTRUCTOR: MODUL PRAKTIKUM TESTING
## Unit Testing & Integration Testing dengan Laravel

### **OVERVIEW UNTUK INSTRUCTOR**

Modul ini dirancang untuk memberikan pengalaman hands-on dalam implementasi testing menggunakan Laravel dan PHPUnit, dengan fokus pada:
- **Boundary Value Analysis** sebagai teknik testing yang efektif
- **Data Provider Pattern** untuk testing yang scalable
- **Test Optimization** berdasarkan prinsip 80/20

---

## **PERSIAPAN SEBELUM PRAKTIKUM**

### **1. Environment Setup Checklist** ✅

**Mahasiswa harus memiliki:**
- Laravel 10/11 terinstall
- PHPUnit configured
- Database SQLite atau MySQL
- VS Code dengan extension PHP/Laravel

**Verifikasi Command:**
```bash
php artisan --version
vendor/bin/phpunit --version
php artisan test --help
```

### **2. Pre-requisite Knowledge**

**Mahasiswa harus memahami:**
- Basic Laravel (Model, Controller, Migration)
- HTTP Request/Response cycle
- Database relationships
- Basic PHP OOP concepts

### **3. Repository Setup**

```bash
# Clone repository template
git clone [repository-url]
cd erp_rpl

# Install dependencies
composer install
cp .env.example .env.testing

# Setup database
php artisan migrate --env=testing
```

---

## **TIMELINE DAN MILESTONE DETAIL**

### **⏱️ FASE 1: Setup (20 menit)**

**Instructor Activities:**
- [ ] Demonstrasi konfigurasi `.env.testing`
- [ ] Penjelasan konsep testing database
- [ ] Live demo: `php artisan test` first run

**Student Activities:**
- [ ] Configure testing environment
- [ ] Verify database connection
- [ ] Run existing tests (if any)

**Common Issues:**
- Database permission errors → Fix with chmod
- SQLite not found → Install sqlite3
- Memory limit → Increase in php.ini

### **⏱️ FASE 2: Unit Testing (40 menit)**

**Learning Objectives:**
- Understand Model testing
- Implement basic CRUD tests
- Use Factory pattern effectively

**Instructor Talking Points:**
```php
// Explain AAA Pattern
// Arrange - Setup test data
$branchData = ['name' => 'Test Branch'];

// Act - Execute the operation
$branch = Branch::create($branchData);

// Assert - Verify the result
$this->assertInstanceOf(Branch::class, $branch);
```

**Guided Implementation:**
1. **Demo**: Create first model test (10 min)
2. **Practice**: Students implement remaining tests (25 min)
3. **Review**: Common patterns and best practices (5 min)

### **⏱️ FASE 3: Integration Testing (60 menit)**

**Learning Objectives:**
- Understand HTTP testing
- Implement boundary value testing
- Use Data Provider pattern

**Critical Concepts to Emphasize:**

#### **Boundary Value Analysis**
```
"80% of bugs occur at boundaries"
- Min boundary: exactly at limit
- Max boundary: exactly at limit  
- Just below min: should fail
- Just above max: should fail
```

#### **Data Provider Benefits**
- DRY principle
- Comprehensive coverage
- Easy to maintain
- Clear test documentation

**Guided Implementation Timeline:**
- **0-15 min**: Explain boundary testing theory
- **15-35 min**: Implement basic CRUD tests
- **35-50 min**: Add boundary value tests with Data Provider
- **50-60 min**: Implement edge cases and optimization

### **⏱️ FASE 4: Optimization (20 menit)**

**Focus Areas:**
- Test execution performance
- Coverage analysis
- Redundancy elimination

**Metrics to Track:**
```
Before Optimization:
- 26 test cases
- ~15 seconds execution
- Some redundant scenarios

After Optimization:
- 17 test cases
- ~6 seconds execution
- 90% bug detection maintained
```

### **⏱️ FASE 5: Evaluation (10 menit)**

**Activities:**
- Generate test reports
- Analyze coverage metrics
- Complete reflection document

---

## **RUBRIK PENILAIAN DETAIL**

### **EXCELLENT (90-100 poin) - Grade A**

**Test Implementation (40 poin):**
- ✅ Semua 17 essential tests implemented
- ✅ Boundary testing comprehensive dan akurat
- ✅ Data Provider pattern digunakan dengan benar
- ✅ Edge cases real-world dipertimbangkan
- ✅ No failing tests

**Code Quality (25 poin):**
- ✅ Clean, readable code dengan proper naming
- ✅ Consistent AAA pattern di semua tests
- ✅ Proper use of assertions
- ✅ Good comments dan documentation
- ✅ No code duplication

**Understanding (20 poin):**
- ✅ Dapat menjelaskan boundary testing dengan jelas
- ✅ Memahami kapan menggunakan Data Provider
- ✅ Dapat mengidentifikasi critical test scenarios
- ✅ Memahami trade-off optimization

**Optimization (15 poin):**
- ✅ Test suite execution < 8 seconds
- ✅ Eliminasi redundant tests dengan reasoning
- ✅ Maintained high bug detection rate
- ✅ Performance-aware implementation

### **GOOD (80-89 poin) - Grade B**

**Test Implementation (32 poin):**
- ✅ 15-16 tests implemented successfully
- ✅ Boundary testing hampir lengkap
- ✅ Data Provider digunakan tapi belum optimal
- ✅ Beberapa edge cases covered
- ❌ 1-2 minor failing tests

**Code Quality (20 poin):**
- ✅ Code readable dan functional
- ✅ AAA pattern mostly consistent
- ✅ Basic assertions used correctly
- ❌ Some naming issues atau minor duplication

**Understanding (16 poin):**
- ✅ Memahami konsep dasar boundary testing
- ✅ Dapat menggunakan Data Provider
- ❌ Penjelasan kurang mendalam
- ❌ Beberapa konsep masih unclear

**Optimization (12 poin):**
- ✅ Test suite execution < 12 seconds
- ✅ Some optimization attempts
- ❌ Masih ada beberapa redundant tests

### **FAIR (70-79 poin) - Grade C**

**Test Implementation (28 poin):**
- ✅ 12-14 tests implemented
- ✅ Basic boundary testing
- ❌ Data Provider implementation incomplete
- ❌ Limited edge case coverage
- ❌ Several failing tests

**Code Quality (15 poin):**
- ✅ Code functional tapi kurang clean
- ❌ Inconsistent patterns
- ❌ Basic assertions only
- ❌ Poor naming atau excessive duplication

**Understanding (12 poin):**
- ✅ Basic understanding of testing concepts
- ❌ Limited boundary testing knowledge
- ❌ Cannot explain Data Provider benefits clearly

**Optimization (8 poin):**
- ✅ Test suite runs < 20 seconds
- ❌ No significant optimization efforts

### **NEEDS IMPROVEMENT (< 70 poin) - Grade D/F**

**Common Issues:**
- Major implementation gaps
- Failing test suite
- Poor code organization
- Limited understanding of concepts
- No optimization efforts

---

## **INSTRUCTOR INTERVENTION POINTS**

### **Checkpoint 1 (20 min mark)**
**Red Flags:**
- Database connection errors
- Cannot run basic `php artisan test`
- Environment setup issues

**Intervention:**
- Group troubleshooting session
- Pair programming untuk setup
- Provide pre-configured environment

### **Checkpoint 2 (60 min mark)**
**Red Flags:**
- No working unit tests
- Cannot create basic model tests
- Confusion about AAA pattern

**Intervention:**
- Live coding demonstration
- One-on-one guidance
- Simplified examples

### **Checkpoint 3 (120 min mark)**
**Red Flags:**
- No boundary testing implementation
- Data Provider not working
- Feature tests all failing

**Intervention:**
- Code review session
- Debug common issues together
- Provide working code snippets

---

## **COMMON STUDENT MISTAKES & SOLUTIONS**

### **1. Database Issues**

**Mistake:**
```php
// Using production database for testing
DB_CONNECTION=mysql
DB_DATABASE=erp_production
```

**Solution:**
```php
// Proper testing configuration
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### **2. Test Data Conflicts**

**Mistake:**
```php
// Not cleaning up between tests
public function test_create_branch()
{
    Branch::create(['name' => 'Test']); // Will fail on second run
}
```

**Solution:**
```php
// Proper cleanup
protected function setUp(): void
{
    parent::setUp();
    Branch::query()->truncate();
}
```

### **3. Boundary Testing Confusion**

**Mistake:**
```php
// Testing middle values instead of boundaries
'name_test' => ['Medium Length Name', true]
```

**Solution:**
```php
// Proper boundary testing
'name_min_boundary' => ['ABC', true],     // Exactly at min limit
'name_below_min' => ['AB', false],        // Just below min
'name_max_boundary' => [str_repeat('A', 50), true], // At max
'name_above_max' => [str_repeat('A', 51), false],   // Above max
```

### **4. Data Provider Syntax Errors**

**Mistake:**
```php
// Wrong attribute syntax
#[DataProvider]
public static function boundaryTestProvider() // Missing attribute parameter
```

**Solution:**
```php
// Correct syntax
#[\PHPUnit\Framework\Attributes\DataProvider]
public static function boundaryTestProvider(): array
```

---

## **ASSESSMENT TOOLS**

### **1. Automated Grading Script**

```bash
#!/bin/bash
# Grade test implementation automatically

echo "Running automated grading..."

# Test execution
php artisan test tests/Unit/Models/BranchTest.php --json > unit_results.json
php artisan test tests/Feature/Controllers/BranchControllerTest.php --json > feature_results.json

# Performance measurement
time php artisan test tests/Feature/Controllers/BranchControllerTest.php > performance.log

# Coverage analysis
php artisan test --coverage-text > coverage.log

echo "Grading complete. Check results files."
```

### **2. Manual Review Checklist**

**Code Quality Review:**
- [ ] Proper test method naming (`test_` prefix)
- [ ] Clear AAA pattern structure
- [ ] Appropriate assertions used
- [ ] No hardcoded values in assertions
- [ ] Proper use of factories

**Boundary Testing Review:**
- [ ] Min/max boundaries tested
- [ ] Invalid boundaries tested
- [ ] Edge cases included
- [ ] Data Provider properly implemented
- [ ] Test cases well-documented

### **3. Peer Review Activity**

**Instructions:**
1. Pair students untuk review kode masing-masing
2. Gunakan checklist untuk systematic review
3. Diskusi findings dalam kelompok kecil
4. Present best practices yang ditemukan

---

## **EXTENSION ACTIVITIES**

### **For Advanced Students**

**1. Performance Optimization Challenge**
- Implement parallel testing
- Database transaction optimization
- Memory usage analysis

**2. Advanced Testing Scenarios**
- Testing dengan external API calls
- File upload testing
- Email sending tests

**3. CI/CD Integration**
- Setup GitHub Actions untuk automated testing
- Generate automated test reports
- Integration dengan code coverage tools

### **For Struggling Students**

**1. Simplified Implementation**
- Focus pada 10 essential tests saja
- Provide more guided examples
- Pair programming dengan advanced students

**2. Conceptual Reinforcement**
- Additional theory explanation
- Visual diagrams of testing flow
- Step-by-step debugging sessions

---

## **RESOURCES UNTUK INSTRUCTOR**

### **Presentation Materials**
- Slide deck tentang boundary testing theory
- Code examples untuk live demonstration
- Common mistakes showcase

### **Demo Scripts**
```php
// Quick demo: Boundary testing effectiveness
$scenarios = [
    'normal_input' => ['Medium Name', true],
    'boundary_min' => ['ABC', true],
    'boundary_max' => [str_repeat('A', 50), true],
    'invalid_short' => ['AB', false],
    'invalid_long' => [str_repeat('A', 51), false],
];

foreach ($scenarios as $name => $test) {
    echo "Testing {$name}: " . ($test[1] ? 'PASS' : 'FAIL') . "\n";
}
```

### **Assessment Templates**
- Rubrik penilaian digital
- Feedback form template
- Progress tracking spreadsheet

---

## **POST-PRAKTIKUM ACTIVITIES**

### **Follow-up Assignments**
1. **Extend Testing**: Implement tests untuk Warehouse dan Merk models
2. **Research Report**: Compare Laravel testing dengan framework lain
3. **Best Practices**: Document testing guidelines untuk team project

### **Project Integration**
- Apply testing practices ke final project
- Minimum test coverage requirements
- Code review process inclusion

---

**🎯 Instructor Success Metrics:**
- 90% students complete all phases
- Average execution time < 10 seconds
- 85% students achieve grade B or better
- High engagement dalam peer review activities
