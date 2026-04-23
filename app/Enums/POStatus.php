<?php

namespace App\Enums;

enum POStatus: string
{
    case Draft = 'Draft';
    case Submitted = 'Submitted';
    case InReview = 'In Review';
    case Revised = 'Revised';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Cancelled = 'Cancelled';
    case Closed = 'Closed';
    case PL = 'Partially Delivered';
    case FD = 'Fully Delivered';
}

// Status PO: 
// 1. Draft: PO masih dalam proses pembuatan dan belum diajukan untuk direview.
// 2. Submitted: PO sudah diajukan untuk direview oleh pimpinan purchasing, seperti yang Anda sebutkan.
// 3. In Review: PO sedang direview oleh pimpinan purchasing, seperti yang Anda sebutkan.
// 4. Revised: PO diminta direvisi oleh pimpinan purchasing, seperti yang Anda sebutkan.
// 5. Approved: PO disetujui oleh pimpinan purchasing, seperti yang Anda sebutkan.
// 6. Rejected: PO ditolak oleh pimpinan purchasing.
// 7. Cancelled: PO dibatalkan karena alasan tertentu.
// 8. Closed: PO sudah selesai dan tidak dapat diubah lagi.
// 9. Partially Delivered: PO sebagian sudah dikirim oleh supplier.
// 10. Fully Delivered: PO sudah dikirim secara keseluruhan oleh supplier.