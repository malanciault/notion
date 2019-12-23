<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Project dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/pulse/bootstrap.min.css">
</head>
<body style="padding: 2rem">
<div class="container">
    <h1>Project Dashboard</h1>
    <section class="card-grid">
        <?php statistic('Current sprint', $sprint->title); ?>
        <?php statistic('Deadline', $sprint->ends->format('Y-m-d')); ?>
        <?php statistic('Issues groomed', $issuesGroomed->count()); ?>
        <?php statistic('Pending proposals', function () use ($pendingProposals): void {
            ?>
            <div class="list-group list-group-flush flex-fill">
                <?php foreach ($pendingProposals as $proposal) { ?>
                    <a class="list-group-item list-group-item-action" href=" <?= $proposal->getUrl(); ?>"
                       target="_blank   ">
                        <?= $proposal->title; ?>
                        by <strong><?= $proposal->author; ?></strong>
                    </a>
                <?php } ?>
            </div>
            <?php
        }); ?>
    </section>
</div>
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>