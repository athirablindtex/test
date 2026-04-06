<div class="main-panel">

    <!-- ===== TIMELINE STYLES ===== -->
    <style>
        .timeline {
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        /* Vertical line */
        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
            padding-left: 35px;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 12px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        .dot-update {
            background-color: #28a745;
        }

        .dot-delete {
            background-color: #dc3545;
        }

        .timeline-content {
            background-color: #f9f9f9;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #eee;
            font-size: 14px;
        }

        .timeline-note {
            margin-top: 5px;
            color: #444;
        }

        .timeline-time {
            font-size: 12px;
            white-space: nowrap;
            text-align: right;
        }
    </style>
    <!-- ===== /TIMELINE STYLES ===== -->

    <div class="content">
        <div class="page-inner">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <h4 class="page-title"><?= @$page; ?></h4>
            </div>

            <!-- CONTENT -->
            <div class="row">
                <div class="col-md-12">

                    <div class="container mt-4">
                        <h5 class="mb-4">Activity Timeline</h5>

                        <div class="timeline">

                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>

                                    <?php
                                        $isDelete  = ($log['action'] === 'DELETE');
                                        $dotClass = $isDelete ? 'dot-delete' : 'dot-update';
                                        $textClass = $isDelete ? 'text-danger' : 'text-success';
                                    ?>

                                    <div class="timeline-item">
                                        <div class="timeline-dot <?= $dotClass ?>"></div>

                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-start">

                                                <div>
                                                    <strong class="<?= $textClass ?>">
                                                        <?= ucfirst(strtolower($log['action'])) ?>
                                                    </strong>

                                                    <div class="timeline-note">
                                                        <?= $log['note'] ?>
                                                    </div>
                                                </div>

                                                <div class="timeline-time text-muted">
                                                    <?= date('d M Y', strtotime($log['created_at'])) ?><br>
                                                    <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No activity found.</p>
                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            </div>
            <!-- /CONTENT -->

        </div>
    </div>

</div>
