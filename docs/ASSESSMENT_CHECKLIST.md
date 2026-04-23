# CHECKLIST & ASSESSMENT SHEET
## Modul Praktikum: Unit Testing & Integration Testing Laravel

---

## **STUDENT CHECKLIST** ✅

### **PERSIAPAN (Sebelum Praktikum)**

- [ ] **Environment Setup**
  - [ ] Laravel 10/11 terinstall dan berfungsi
  - [ ] PHPUnit configured (`vendor/bin/phpunit --version`)
  - [ ] Database SQLite atau MySQL ready
  - [ ] VS Code dengan extension PHP/Laravel
  - [ ] Git repository initialized

- [ ] **Knowledge Prerequisites**
  - [ ] Memahami basic Laravel (Model, Controller, Migration)
  - [ ] Familiar dengan HTTP Request/Response
  - [ ] Basic understanding PHP OOP
  - [ ] Familiar dengan command line/terminal

---

### **FASE 1: SETUP TESTING ENVIRONMENT** ⏱️ 20 menit

- [ ] **Database Configuration**
  - [ ] File `.env.testing` dibuat dan dikonfigurasi
  - [ ] Database connection menggunakan SQLite `:memory:`
  - [ ] `phpunit.xml` dikonfigurasi dengan benar
  - [ ] Test database connection berhasil

- [ ] **Verification**
  - [ ] Command `php artisan test` berjalan tanpa error
  - [ ] No existing test failures
  - [ ] Database migrations berjalan di testing environment

**✅ Checkpoint 1**: Environment testing ready untuk digunakan

---

### **FASE 2: UNIT TESTING - BRANCH MODEL** ⏱️ 40 menit

- [ ] **File Structure**
  - [ ] File `tests/Unit/Models/BranchTest.php` dibuat
  - [ ] Proper namespace dan class structure
  - [ ] RefreshDatabase trait digunakan

- [ ] **Basic Model Tests**
  - [ ] `it_can_create_branch_with_valid_data()` - Test model creation
  - [ ] `it_has_correct_default_values()` - Test default values
  - [ ] `it_can_update_branch_information()` - Test model update
  - [ ] `it_can_delete_branch()` - Test model deletion
  - [ ] `it_can_be_created_using_factory()` - Test factory usage

- [ ] **Test Quality**
  - [ ] AAA pattern (Arrange-Act-Assert) diikuti
  - [ ] Proper assertions used (`assertInstanceOf`, `assertDatabaseHas`)
  - [ ] Good test method naming
  - [ ] No hardcoded values in assertions

**✅ Checkpoint 2**: Unit tests untuk Branch model berjalan sukses

---

### **FASE 3: INTEGRATION TESTING - BRANCH CONTROLLER** ⏱️ 60 menit

- [ ] **File Structure**
  - [ ] File `tests/Feature/Controllers/BranchControllerTest.php` dibuat
  - [ ] Proper imports (BranchColumns, Branch model)
  - [ ] setUp() method dengan database cleanup

- [ ] **CRUD Endpoint Tests**
  - [ ] `test_index_returns_all_branches()` - GET /api/branches
  - [ ] `test_store_with_valid_data()` - POST /api/branches
  - [ ] `test_show_existing_branch()` - GET /api/branches/{id}
  - [ ] `test_update_with_valid_data()` - PUT /api/branches/{id}
  - [ ] `test_delete_existing_branch()` - DELETE /api/branches/{id}

- [ ] **Boundary Value Testing**
  - [ ] Data Provider `essentialBoundaryTestProvider()` implemented
  - [ ] `test_store_essential_boundaries()` method dengan @dataProvider
  - [ ] 12 essential boundary test cases covered:
    - [ ] NAME: valid min (3), invalid short (2), valid max (50), invalid long (51), empty
    - [ ] ADDRESS: valid min (3), invalid short (2), valid max (100), invalid long (101)
    - [ ] TELEPHONE: valid min (3), invalid short (2), invalid long (31)

- [ ] **Edge Case Testing**
  - [ ] Data Provider `edgeCaseTestProvider()` implemented
  - [ ] `test_store_edge_cases()` method with real-world scenarios:
    - [ ] Duplicate name handling
    - [ ] Special characters in input
    - [ ] International phone format
    - [ ] Status false scenario

- [ ] **Error Handling Tests**
  - [ ] `test_show_nonexistent_branch()` - 404 error
  - [ ] `test_delete_nonexistent_branch()` - 404 error
  - [ ] `test_validation_messages_in_indonesian()` - Validation errors

**✅ Checkpoint 3**: Feature tests untuk BranchController berjalan sukses

---

### **FASE 4: OPTIMASI TEST SUITE** ⏱️ 20 menit

- [ ] **Performance Analysis**
  - [ ] Test execution time measured (`time php artisan test`)
  - [ ] Test count optimized (target: 17 essential tests)
  - [ ] Redundant tests identified dan dihapus

- [ ] **Code Quality**
  - [ ] Helper methods untuk code reusability
  - [ ] Constants digunakan untuk field names
  - [ ] Database cleanup optimal (transactions vs truncate)

- [ ] **Coverage Analysis**
  - [ ] Test coverage report generated
  - [ ] Critical code paths covered
  - [ ] Edge cases adequately tested

**✅ Checkpoint 4**: Test suite optimal dengan execution time < 10 detik

---

### **FASE 5: DOKUMENTASI & EVALUASI** ⏱️ 10 menit

- [ ] **Test Reports**
  - [ ] Screenshots of test results
  - [ ] Coverage report saved
  - [ ] Performance metrics documented

- [ ] **Reflection Document** (500 kata minimum)
  - [ ] Analisis effectiveness boundary testing
  - [ ] Challenges encountered selama praktikum
  - [ ] Lessons learned dan best practices
  - [ ] Saran improvement untuk future testing

**✅ Final Checkpoint**: Semua deliverables completed

---

## **INSTRUCTOR ASSESSMENT SHEET** 📊

### **STUDENT INFORMATION**
- **Nama**: ________________________________
- **NPM**: ________________________________
- **Tanggal**: ________________________________
- **Waktu Mulai**: _________ **Waktu Selesai**: _________

---

### **PENILAIAN DETAIL**

#### **1. TEST IMPLEMENTATION (40 poin)**

| Criteria | Excellent (36-40) | Good (32-35) | Fair (28-31) | Poor (0-27) | Score |
|----------|-------------------|--------------|--------------|-------------|-------|
| **Unit Tests** | Semua 5 unit tests berjalan sukses, proper AAA pattern | 4-5 tests sukses, mostly proper pattern | 3-4 tests sukses, basic pattern | < 3 tests sukses | ___/10 |
| **Feature Tests** | Semua CRUD tests berjalan sukses | 4-5 CRUD tests sukses | 3 CRUD tests sukses | < 3 CRUD tests sukses | ___/10 |
| **Boundary Testing** | 12 boundary tests implemented dengan benar | 10-11 boundary tests | 8-9 boundary tests | < 8 boundary tests | ___/10 |
| **Edge Cases** | 5 edge cases covered dengan realistic scenarios | 4 edge cases covered | 3 edge cases covered | < 3 edge cases | ___/10 |

**Total Implementation Score**: ___/40

#### **2. CODE QUALITY (25 poin)**

| Criteria | Excellent (23-25) | Good (20-22) | Fair (17-19) | Poor (0-16) | Score |
|----------|-------------------|--------------|--------------|-------------|-------|
| **Code Organization** | Clean structure, proper namespaces, good file organization | Mostly clean, minor issues | Basic organization, some issues | Poor organization | ___/8 |
| **Naming Conventions** | Excellent test method names, descriptive variables | Good naming, mostly consistent | Basic naming, some inconsistency | Poor naming | ___/5 |
| **Test Patterns** | Consistent AAA pattern, proper assertions | Mostly consistent patterns | Basic patterns used | Inconsistent patterns | ___/7 |
| **Code Reusability** | Helper methods, constants, DRY principle | Some reusability efforts | Limited reusability | No reusability considerations | ___/5 |

**Total Code Quality Score**: ___/25

#### **3. UNDERSTANDING & EXPLANATION (20 poin)**

| Criteria | Excellent (18-20) | Good (16-17) | Fair (14-15) | Poor (0-13) | Score |
|----------|-------------------|--------------|--------------|-------------|-------|
| **Boundary Testing Concept** | Clear explanation of BVA, can identify critical boundaries | Good understanding, minor gaps | Basic understanding | Limited understanding | ___/8 |
| **Data Provider Usage** | Excellent explanation of benefits and usage | Good understanding | Basic usage | Poor understanding | ___/6 |
| **Testing Strategy** | Can explain test selection rationale | Understands basic strategy | Limited strategy knowledge | No clear strategy | ___/6 |

**Total Understanding Score**: ___/20

#### **4. OPTIMIZATION & PERFORMANCE (15 poin)**

| Criteria | Excellent (14-15) | Good (12-13) | Fair (10-11) | Poor (0-9) | Score |
|----------|-------------------|--------------|--------------|-------------|-------|
| **Execution Time** | < 8 seconds | 8-12 seconds | 12-20 seconds | > 20 seconds | ___/5 |
| **Test Count Optimization** | 17 essential tests, well-reasoned optimization | 15-18 tests, good optimization | 19-22 tests, some optimization | > 22 tests, no optimization | ___/5 |
| **Resource Usage** | Optimal database cleanup, efficient setup | Good resource management | Basic resource management | Poor resource management | ___/5 |

**Total Optimization Score**: ___/15

---

### **SUMMARY ASSESSMENT**

| Component | Score | Weight | Weighted Score |
|-----------|-------|--------|----------------|
| Test Implementation | ___/40 | 40% | ___/40 |
| Code Quality | ___/25 | 25% | ___/25 |
| Understanding | ___/20 | 20% | ___/20 |
| Optimization | ___/15 | 15% | ___/15 |
| **TOTAL** | | **100%** | **___/100** |

### **GRADE ASSIGNMENT**

| Score Range | Grade | Description |
|-------------|-------|-------------|
| 90-100 | **A** | Excellent - Exceptional understanding and implementation |
| 80-89 | **B** | Good - Strong understanding with minor gaps |
| 70-79 | **C** | Fair - Basic understanding, needs improvement |
| 60-69 | **D** | Poor - Significant gaps in understanding |
| < 60 | **F** | Fail - Major deficiencies |

**Final Grade**: ___

---

### **DETAILED FEEDBACK**

#### **Strengths Observed:**
- ________________________________
- ________________________________
- ________________________________

#### **Areas for Improvement:**
- ________________________________
- ________________________________
- ________________________________

#### **Specific Recommendations:**
- ________________________________
- ________________________________
- ________________________________

#### **Additional Comments:**
________________________________________________________________
________________________________________________________________
________________________________________________________________

---

## **PEER REVIEW CHECKLIST** 👥

### **Instructions untuk Peer Review:**
1. Pasangkan students untuk review kode masing-masing
2. Gunakan checklist ini untuk systematic review
3. Diskusi findings dalam kelompok kecil
4. Present best practices yang ditemukan

### **Peer Review Questions:**

#### **Code Readability**
- [ ] Apakah test method names jelas dan descriptive?
- [ ] Apakah AAA pattern konsisten digunakan?
- [ ] Apakah ada comments yang membantu understanding?

#### **Test Coverage**
- [ ] Apakah boundary values sudah covered dengan baik?
- [ ] Apakah edge cases realistic dan valuable?
- [ ] Apakah ada test yang redundant atau bisa dihapus?

#### **Implementation Quality**
- [ ] Apakah Data Provider digunakan dengan efektif?
- [ ] Apakah assertions appropriate untuk setiap test?
- [ ] Apakah ada code duplication yang bisa dihindari?

#### **Best Practices**
- [ ] Apakah database cleanup handled dengan benar?
- [ ] Apakah constants digunakan untuk field names?
- [ ] Apakah test isolation maintained?

### **Peer Feedback Form:**

**Reviewer**: _________________ **Code Author**: _________________

**What I learned from this code:**
________________________________________________________________

**Suggestions for improvement:**
________________________________________________________________

**Best practices I observed:**
________________________________________________________________

---

## **COMMON ISSUES TRACKING** 🐛

### **Issue Tracking Sheet untuk Instructor**

| Issue | Frequency | Students Affected | Solution Applied | Resolution Time |
|-------|-----------|-------------------|------------------|-----------------|
| Database connection error | ___/30 | _________________ | ________________ | _______ min |
| Data Provider syntax error | ___/30 | _________________ | ________________ | _______ min |
| Unique constraint violation | ___/30 | _________________ | ________________ | _______ min |
| Test execution timeout | ___/30 | _________________ | ________________ | _______ min |
| Validation message mismatch | ___/30 | _________________ | ________________ | _______ min |
| Factory not working | ___/30 | _________________ | ________________ | _______ min |

### **Success Metrics**

| Metric | Target | Actual | Notes |
|--------|--------|--------|-------|
| Students completing all phases | 90% | ___% | _________________ |
| Average execution time | < 10s | ___s | _________________ |
| Students achieving grade B+ | 85% | ___% | _________________ |
| Zero failing test suites | 100% | ___% | _________________ |
| Peer review participation | 100% | ___% | _________________ |

---

## **POST-PRAKTIKUM FOLLOW-UP** 📋

### **Student Submission Requirements**

**Due Date**: _________________

**Deliverables Checklist:**
- [ ] **Test Files**
  - [ ] `tests/Unit/Models/BranchTest.php`
  - [ ] `tests/Feature/Controllers/BranchControllerTest.php`
  
- [ ] **Documentation**
  - [ ] Screenshot test results (all tests passing)
  - [ ] Coverage report (HTML atau text)
  - [ ] Performance metrics (execution time)
  
- [ ] **Reflection Document** (500+ words)
  - [ ] Boundary testing analysis
  - [ ] Challenges faced dan solutions
  - [ ] Lessons learned
  - [ ] Improvement suggestions

- [ ] **Code Repository**
  - [ ] Git repository dengan commit history
  - [ ] Clean code dengan proper comments
  - [ ] README dengan testing instructions

### **Grading Timeline**
- **Day 1**: Collect submissions
- **Day 2-3**: Automated testing of student code
- **Day 4-5**: Manual code review dan grading
- **Day 6**: Feedback distribution
- **Day 7**: Grade consultation sessions

---

**🎯 Assessment sheet ini memastikan evaluasi yang fair, comprehensive, dan constructive untuk semua mahasiswa!**
