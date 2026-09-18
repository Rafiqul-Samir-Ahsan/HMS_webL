<main class="main-content">
    <div class="page-heading">
        <h1>My Bills</h1>
        <p>Invoices and payment history</p>
    </div>
    <?php render_flash(); ?>

    <div class="stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 260px));">
        <div class="card stat-card">
            <div class="stat-label">Outstanding Balance</div>
            <div class="stat-value">$<?php echo number_format($totalDue, 2); ?></div>
        </div>
    </div>

    <div class="card">
        <table>
            <tr><th>Bill #</th><th>Date</th><th>Amount</th><th>Discount</th><th>Paid</th><th>Balance</th><th>Status</th></tr>
            <?php if (mysqli_num_rows($bills) === 0): ?>
                <tr><td colspan="7" class="empty-state">No bills on record.</td></tr>
            <?php endif; ?>
            <?php while ($b = mysqli_fetch_assoc($bills)): ?>
            <?php $balance = $b['amount'] - $b['discount'] - $b['paid_amount']; ?>
            <tr>
                <td>#<?php echo $b['bill_id']; ?></td>
                <td><?php echo date('M j, Y', strtotime($b['bill_date'])); ?></td>
                <td>$<?php echo number_format($b['amount'], 2); ?></td>
                <td>$<?php echo number_format($b['discount'], 2); ?></td>
                <td>$<?php echo number_format($b['paid_amount'], 2); ?></td>
                <td>$<?php echo number_format($balance, 2); ?></td>
                <td><?php echo status_badge($b['payment_status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
