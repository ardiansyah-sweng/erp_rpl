**NIM** **NAMA**
| Refactor | Model Test | Controller Test | API Test | Browser Test | Approved |
|--------------|---------------|------------|-----------------|-----------|---------------|
| Model: updateBranch(). *Pindahkan validasi ke Controller* | Create warehouse with valid data | Update branch with invalid data | Update branch with valid data | Delete with confirmation dialog | 0/5 |
|  | View all warehouses (without search) | Update branch name to duplicate | Update branch name to duplicate | Cancel deletion | 0/5 |

**NIM** **NAMA**
| Refactor | Model Test | Controller Test | API Test | Browser Test | Approved |
|--------------|---------------|------------|-----------------|-----------|---------------|
| Model: addWarehouse() | Search warehouse with empty results | Update branch status to inactive | Update branch status to inactive | Delete branch with references | 0/5 |
|  | Search warehouse by phone | Delete non-existing branch | Update branch with invalid data | Delete non-existing branch | 0/5 |

**NIM** **NAMA**
| Refactor | Model Test | Controller Test | API Test | Browser Test | Approved |
|--------------|---------------|------------|-----------------|-----------|---------------|
| Model: addWarehouse() | Search warehouse by name | Update branch status to inactive | Delete non-existing branch | Update branch name to duplicate | 0/5 |
|  | Search warehouse by address | Create warehouse with valid data | Delete unused branch | Update branch status to inactive | 0/5 |

**NIM** **NAMA**
| Refactor | Model Test | Controller Test | API Test | Browser Test | Approved |
|--------------|---------------|------------|-----------------|-----------|---------------|
|  | View warehouse detail | View all warehouses (without search) | Get branch statistics | Update branch name to duplicate | 0/5 |
|  | View non-existing warehouse | Search warehouse with empty results | Create warehouse with valid data | Update branch status to inactive | 0/5 |

**NIM** **NAMA**
| Refactor | Model Test | Controller Test | API Test | Browser Test | Approved |
|--------------|---------------|------------|-----------------|-----------|---------------|
|  | Update warehouse with valid data | View warehouse detail | Delete branch with references | Update branch with invalid data | 0/5 |
|  | Update warehouse name to duplicate | View non-existing warehouse |  | Create warehouse with valid data | 0/5 |