<?php 
/**
 * Bootstrap 5 Pagination Template
 * Preserves query parameters (sort, per_page, etc.)
 */

$pager->setSurroundCount(2);

// Get current query parameters and preserve them
$queryParams = $_GET ?? [];
unset($queryParams['page']); // Remove page param as it's handled by pagination
$queryString = !empty($queryParams) ? '&' . http_build_query($queryParams) : '';
?>

<style>
.pagination-sm .page-item {
    margin: 0 2px;
}
.pagination-sm .page-item:first-child {
    margin-left: 0;
}
.pagination-sm .page-item:last-child {
    margin-right: 0;
}
.pagination-sm .page-link {
    min-width: 32px;
    text-align: center;
    border-radius: 4px;
}
.pagination-sm .page-item.active .page-link {
    font-weight: 600;
}
</style>

<nav aria-label="Page navigation">
    <ul class="pagination pagination-sm justify-content-center mb-0">
        
        <!-- First Page -->
        <?php if ($pager->hasPreviousPage()): ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() . $queryString ?>" class="page-link" aria-label="First">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-angle-double-left"></i></span>
            </li>
        <?php endif ?>

        <!-- Previous Page -->
        <?php if ($pager->hasPreviousPage()): ?>
            <li class="page-item">
                <a href="<?= $pager->getPreviousPage() . $queryString ?>" class="page-link" aria-label="Previous">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-angle-left"></i></span>
            </li>
        <?php endif ?>

        <!-- Page Numbers -->
        <?php foreach ($pager->links() as $link): ?>
            <?php if ($link['active']): ?>
                <li class="page-item active" aria-current="page">
                    <span class="page-link"><?= esc($link['title']) ?></span>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a href="<?= $link['uri'] . $queryString ?>" class="page-link"><?= esc($link['title']) ?></a>
                </li>
            <?php endif ?>
        <?php endforeach ?>

        <!-- Next Page -->
        <?php if ($pager->hasNextPage()): ?>
            <li class="page-item">
                <a href="<?= $pager->getNextPage() . $queryString ?>" class="page-link" aria-label="Next">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-angle-right"></i></span>
            </li>
        <?php endif ?>

        <!-- Last Page -->
        <?php if ($pager->hasNextPage()): ?>
            <li class="page-item">
                <a href="<?= $pager->getLast() . $queryString ?>" class="page-link" aria-label="Last">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-angle-double-right"></i></span>
            </li>
        <?php endif ?>

    </ul>
</nav>
