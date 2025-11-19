<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Todo</title>
</head>
<body>
    <h1>Welcom to Mini Todo Framework</h1>
    <p><?php echo $message ?? 'No message'; ?></p>

    <h2>新しいタスクを追加</h2>
    <form action="/tasks/add" method="POST">
        <input type="text" name="title" placeholder="タスク名" required>
        <button type="submit">追加</button>
    </form>
    <hr>
    <h2>Todo リスト</h2>
    <ul>
        <?php foreach($todos as $todo): ?>
            <li>
                <?= $todo->id ?> :
                <!-- htmlspecialchars() -->
                <!-- HTMLとして解釈しないような形式に変換 -->
                <?= htmlspecialchars($todo->title, ENT_QUOTES,'UTF-8') ?>

                <?php if ($todo->done): ?>
                    【完了】
                <?php else: ?>
                    <form action="/tasks/<?= $todo->id ?>/done" method="POST" style="display:inline;">
                        <button type="submit">完了にする</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>